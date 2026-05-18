<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\FinancialSetting;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Handles bill listing, creation, updates, and status transitions for admins.
 */
class AdminBillingController extends Controller
{
    /**
     * Shows paid billing history with optional resident-name search.
     */
    public function list(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        Bill::markPastDueAsOverdue();

        $search = trim((string) $request->input('search'));

        $bills = Bill::with('user')
            ->where('status', 'Paid')
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                    });
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.billingHistory', [
            'bills' => $bills,
        ]);
    }

    /**
     * Loads the water bill creation page with defaults and latest readings.
     */
    public function createWaterBill(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $residents = $this->approvedResidents();
        $selectedResidentId = $request->integer('resident_id');

        return view('admin.Create.createNewWaterBill', [
            'residents' => $residents,
            'selectedResidentId' => $selectedResidentId,
            'defaultServiceFee' => FinancialSetting::getAmount('water_service_fee', 100),
            'defaultPricePerUnit' => FinancialSetting::getAmount('water_price_per_unit', 0),
            'latestReadingsByResident' => $this->latestCurrentReadingsByResident('Water'),
        ]);
    }

    /**
     * Creates a water bill for the selected resident.
     */
    public function storeWaterBill(Request $request)
    {
        return $this->storeBillsForAllResidents($request, 'Water');
    }

    /**
     * Loads the electricity bill creation page with defaults and latest readings.
     */
    public function createElectricityBill(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $residents = $this->approvedResidents();
        $selectedResidentId = $request->integer('resident_id');

        return view('admin.Create.createNewElectricityBill', [
            'residents' => $residents,
            'selectedResidentId' => $selectedResidentId,
            'defaultServiceFee' => FinancialSetting::getAmount('electricity_service_fee', 100),
            'defaultPricePerUnit' => FinancialSetting::getAmount('electricity_price_per_unit', 0),
            'latestReadingsByResident' => $this->latestCurrentReadingsByResident('Electricity'),
        ]);
    }

    /**
     * Creates an electricity bill for the selected resident.
     */
    public function storeElectricityBill(Request $request)
    {
        return $this->storeBillsForAllResidents($request, 'Electricity');
    }

    /**
     * Updates bill status and recomputes penalty/payment fields when needed.
     */
    public function updateStatus(Request $request, Bill $bill)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Overdue', 'Paid'])],
        ]);

        // Align user-selected status with due-date business rules.
        $oldStatus = $bill->status;
        $status = Bill::statusForDueDate($validated['status'], $bill->billing_period_end);
        $bill->status = $status;
        $bill->is_done = $status === 'Paid';

        // Recalculate penalty-related fields when moving into or out of overdue/paid states.
        if (! in_array($status, ['Overdue', 'Paid'], true)) {
            $bill->penalty_days_applied = 0;
            $bill->total_bill = $bill->base_total_bill ?? $bill->total_bill;
        } elseif ($status === 'Overdue') {
            $amounts = Bill::calculateAmounts((float) ($bill->base_total_bill ?? $bill->total_bill), $bill->billing_period_end, $status);
            $bill->penalty_days_applied = $amounts['penalty_days'];
            $bill->total_bill = $amounts['total_before_vat'];
        }

        // Manage payment timestamp/reference based on final status.
        if ($status === 'Paid') {
            $bill->paid_at = $bill->paid_at ?? now();

            if (empty($bill->payment_reference)) {
                $bill->payment_reference = $this->generatePaymentReference($bill->utility_type);
            }
        } else {
            $bill->paid_at = null;
        }

        $bill->save();
        $bill->notifyResidentIfOverdue($admin->id);
        $bill->loadMissing('user:id,first_name,last_name');
        $residentName = trim(($bill->user?->first_name ?? '').' '.($bill->user?->last_name ?? ''));
        $residentLabel = $residentName !== '' ? $residentName : "Resident #{$bill->user_id}";
        AuditLogger::log(
            $admin,
            'bill_status_updated',
            "Updated {$bill->utility_type} bill for {$residentLabel} from {$oldStatus} to {$status}.",
            [
                'bill_id' => $bill->id,
                'resident_id' => $bill->user_id,
                'utility_type' => $bill->utility_type,
                'old_status' => $oldStatus,
                'new_status' => $status,
            ],
            'billing',
            $request
        );

        return back()->with('success', 'Bill status updated successfully.');
    }

    /**
     * Opens the utility-specific edit form for an existing bill.
     */
    public function edit(Request $request, Bill $bill)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        Bill::markPastDueAsOverdue();

        $bill->load('user.property.meters');

        $view = match ($bill->utility_type) {
            'Electricity' => 'admin.updateelectricity',
            'Water' => 'admin.updatewaterbill',
            default => abort(404),
        };

        $meter = $bill->user?->property?->meters
            ?->firstWhere('utility_type', $bill->utility_type);

        return view($view, [
            'bill' => $bill,
            'resident' => $bill->user,
            'meter' => $meter,
        ]);
    }

    /**
     * Saves edited bill readings, recalculates totals, and logs the change.
     */
    public function update(Request $request, Bill $bill)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $validated = $request->validate([
            'previous_reading' => ['required', 'numeric', 'min:0'],
            'current_reading' => ['required', 'numeric', 'gte:previous_reading'],
            'reading_date' => ['required', 'date'],
            'billing_period_start' => ['required', 'date'],
            'billing_period_end' => ['required', 'date', 'after_or_equal:billing_period_start'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'service_fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['Pending', 'Overdue', 'Paid'])],
            'is_done' => ['nullable', 'boolean'],
        ]);

        // Recompute bill amounts from validated meter readings and pricing inputs.
        $previousReading = (float) $validated['previous_reading'];
        $currentReading = (float) $validated['current_reading'];
        $consumption = max($currentReading - $previousReading, 0);
        $pricePerUnit = (float) $validated['price_per_unit'];
        $serviceFee = (float) ($validated['service_fee'] ?? 0);
        $status = Bill::statusForDueDate($validated['status'], $validated['billing_period_end']);
        $baseTotalBill = ($consumption * $pricePerUnit) + $serviceFee;
        $amounts = Bill::calculateAmounts($baseTotalBill, $validated['billing_period_end'], $status);

        $bill->fill([
            'previous_reading' => $previousReading,
            'current_reading' => $currentReading,
            'consumption' => $consumption,
            'reading_date' => $validated['reading_date'],
            'billing_period_start' => $validated['billing_period_start'],
            'billing_period_end' => $validated['billing_period_end'],
            'price_per_unit' => $pricePerUnit,
            'service_fee' => $serviceFee,
            'base_total_bill' => $amounts['base_amount'],
            'total_bill' => $amounts['total_before_vat'],
            'penalty_days_applied' => $amounts['penalty_days'],
            'status' => $status,
            'is_done' => $request->boolean('is_done') || $status === 'Paid',
            'paid_at' => $status === 'Paid' ? ($bill->paid_at ?? now()) : null,
        ]);

        if ($status === 'Paid' && empty($bill->payment_reference)) {
            $bill->payment_reference = $this->generatePaymentReference($bill->utility_type);
        }

        $bill->save();
        $bill->notifyResidentIfOverdue($admin->id);
        $bill->loadMissing('user:id,first_name,last_name');
        $residentName = trim(($bill->user?->first_name ?? '').' '.($bill->user?->last_name ?? ''));
        $residentLabel = $residentName !== '' ? $residentName : "Resident #{$bill->user_id}";
        AuditLogger::log(
            $admin,
            'bill_updated',
            "Updated {$bill->utility_type} bill for {$residentLabel}.",
            [
                'bill_id' => $bill->id,
                'resident_id' => $bill->user_id,
                'status' => $status,
                'total_bill' => (float) $bill->total_bill,
            ],
            'billing',
            $request
        );

        return redirect()
            ->route('admin.residentInfo', $bill->user_id)
            ->with('success', "{$bill->utility_type} bill updated successfully.");
    }

    /**
     * Shared bill-creation flow used by both water and electricity endpoints.
     */
    protected function storeBillsForAllResidents(Request $request, string $utilityType)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $validated = $request->validate([
            'resident_id' => ['required', 'integer', 'exists:users,id'],
            'previous_reading' => ['nullable', 'numeric', 'min:0'],
            'current_reading' => ['required', 'numeric', 'min:0'],
            'reading_date' => ['required', 'date'],
            'billing_period_start' => ['required', 'date'],
            'billing_period_end' => ['required', 'date', 'after_or_equal:billing_period_start'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'service_fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['Pending', 'Overdue', 'Paid'])],
        ]);

        // Only approved renters can receive newly generated bills.
        $resident = User::query()
            ->where('role', 'renter')
            ->where('id', $validated['resident_id'])
            ->when(
                Schema::hasColumn('users', 'status'),
                fn ($query) => $query->where('status', 'approved')
            )
            ->with(['property.meters'])
            ->first();

        if (! $resident) {
            return back()->with('error', 'Selected resident is not approved or does not exist.');
        }

        // If there is a previous saved bill, force continuity from its latest reading.
        $latestCurrentReading = $this->latestCurrentReadingForResident($resident->id, $utilityType);
        $previousReading = $latestCurrentReading ?? (isset($validated['previous_reading']) ? (float) $validated['previous_reading'] : null);

        if ($previousReading === null) {
            throw ValidationException::withMessages([
                'previous_reading' => 'The previous reading is required when this resident has no saved bill for this utility yet.',
            ]);
        }

        $currentReading = (float) $validated['current_reading'];

        // Prevent negative usage due to an invalid current reading.
        if ($currentReading < $previousReading) {
            throw ValidationException::withMessages([
                'current_reading' => 'The current reading must be greater than or equal to the latest saved reading for this resident.',
            ]);
        }

        $consumption = max($currentReading - $previousReading, 0);
        $pricePerUnit = (float) $validated['price_per_unit'];
        $serviceFee = (float) ($validated['service_fee'] ?? 0);
        $totalBill = ($consumption * $pricePerUnit) + $serviceFee;
        $meter = $resident->property?->meters
            ?->firstWhere('utility_type', $utilityType);

        // Resolve meter identifier fallback when hardware/serial numbers are absent.
        $meterNo = $meter?->hardware_meter_number
            ?? $meter?->serial_number
            ?? sprintf('%s-%d', strtoupper(substr($utilityType, 0, 3)), $resident->id);

        $status = Bill::statusForDueDate($validated['status'], $validated['billing_period_end']);
        $amounts = Bill::calculateAmounts($totalBill, $validated['billing_period_end'], $status);

        $bill = Bill::create([
            'user_id' => $resident->id,
            'utility_type' => $utilityType,
            'billing_period_start' => $validated['billing_period_start'],
            'billing_period_end' => $validated['billing_period_end'],
            'meter_no' => $meterNo,
            'previous_reading' => $previousReading,
            'current_reading' => $currentReading,
            'consumption' => $consumption,
            'reading_date' => $validated['reading_date'],
            'price_per_unit' => $pricePerUnit,
            'service_fee' => $serviceFee,
            'base_total_bill' => $amounts['base_amount'],
            'total_bill' => $amounts['total_before_vat'],
            'penalty_days_applied' => $amounts['penalty_days'],
            'status' => $status,
            'is_done' => $status === 'Paid',
            'payment_reference' => $this->generatePaymentReference($utilityType),
            'paid_at' => $status === 'Paid' ? now() : null,
        ]);
        $bill->notifyResidentIfOverdue($admin->id);
        $residentName = trim(($resident->first_name ?? '').' '.($resident->last_name ?? ''));
        $residentLabel = $residentName !== '' ? $residentName : "Resident #{$resident->id}";
        AuditLogger::log(
            $admin,
            'bill_created',
            "Created {$utilityType} bill for {$residentLabel}.",
            [
                'bill_id' => $bill->id,
                'resident_id' => $resident->id,
                'utility_type' => $utilityType,
                'status' => $status,
                'total_bill' => (float) $bill->total_bill,
            ],
            'billing',
            $request
        );

        return redirect()
            ->route('admin.residentInfo', $resident->id)
            ->with(
                'success',
                "{$utilityType} bill created for {$resident->first_name} {$resident->last_name}."
            );
    }

    /**
     * Returns the most recent saved reading for one resident and utility.
     */
    protected function latestCurrentReadingForResident(int $residentId, string $utilityType): ?float
    {
        $reading = Bill::query()
            ->where('user_id', $residentId)
            ->where('utility_type', $utilityType)
            ->orderByDesc('reading_date')
            ->orderByDesc('id')
            ->value('current_reading');

        return $reading === null ? null : (float) $reading;
    }

    /**
     * Returns latest readings keyed by resident for a given utility type.
     */
    protected function latestCurrentReadingsByResident(string $utilityType)
    {
        return Bill::query()
            ->where('utility_type', $utilityType)
            ->orderByDesc('reading_date')
            ->orderByDesc('id')
            ->get(['user_id', 'current_reading'])
            ->unique('user_id')
            ->mapWithKeys(fn (Bill $bill) => [$bill->user_id => (float) $bill->current_reading]);
    }

    /**
     * Fetches approved renters for bill assignment dropdowns.
     */
    protected function approvedResidents()
    {
        return User::query()
            ->where('role', 'renter')
            ->when(
                Schema::hasColumn('users', 'status'),
                fn ($query) => $query->where('status', 'approved')
            )
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'email']);
    }

    /**
     * Generates a unique payment reference scoped by utility prefix and date.
     */
    protected function generatePaymentReference(string $utilityType): string
    {
        $prefix = strtoupper(substr($utilityType, 0, 3));

        do {
            $reference = sprintf(
                '%s-%s-%s',
                $prefix,
                now()->format('Ymd'),
                strtoupper(Str::random(6))
            );
        } while (Bill::where('payment_reference', $reference)->exists());

        return $reference;
    }
}
