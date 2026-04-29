<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminBillingController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $search = trim((string) $request->input('search'));

        $bills = Bill::with('user')
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

    public function createWaterBill(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $residents = $this->approvedResidents();
        $selectedResidentId = $request->integer('resident_id');

        return view('admin.Create.createNewWaterBill', [
            'residents' => $residents,
            'selectedResidentId' => $selectedResidentId,
        ]);
    }

    public function storeWaterBill(Request $request)
    {
        return $this->storeBillsForAllResidents($request, 'Water');
    }

    public function createElectricityBill(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $residents = $this->approvedResidents();
        $selectedResidentId = $request->integer('resident_id');

        return view('admin.Create.createNewElectricityBill', [
            'residents' => $residents,
            'selectedResidentId' => $selectedResidentId,
        ]);
    }

    public function storeElectricityBill(Request $request)
    {
        return $this->storeBillsForAllResidents($request, 'Electricity');
    }

    public function updateStatus(Request $request, Bill $bill)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Overdue', 'Paid'])],
        ]);

        $status = $validated['status'];
        $bill->status = $status;
        $bill->is_done = $status === 'Paid';

        if ($status === 'Paid') {
            $bill->paid_at = $bill->paid_at ?? now();

            if (empty($bill->payment_reference)) {
                $bill->payment_reference = $this->generatePaymentReference($bill->utility_type);
            }
        } else {
            $bill->paid_at = null;
        }

        $bill->save();

        return back()->with('success', 'Bill status updated successfully.');
    }

    protected function storeBillsForAllResidents(Request $request, string $utilityType)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $validated = $request->validate([
            'resident_id' => ['required', 'integer', 'exists:users,id'],
            'previous_reading' => ['required', 'numeric', 'min:0'],
            'current_reading' => ['required', 'numeric', 'gte:previous_reading'],
            'reading_date' => ['required', 'date'],
            'billing_period_start' => ['required', 'date'],
            'billing_period_end' => ['required', 'date', 'after_or_equal:billing_period_start'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'service_fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['Pending', 'Overdue', 'Paid'])],
        ]);

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

        $previousReading = (float) $validated['previous_reading'];
        $currentReading = (float) $validated['current_reading'];
        $consumption = max($currentReading - $previousReading, 0);
        $pricePerUnit = (float) $validated['price_per_unit'];
        $serviceFee = (float) ($validated['service_fee'] ?? 0);
        $totalBill = ($consumption * $pricePerUnit) + $serviceFee;
        $meter = $resident->property?->meters
            ?->firstWhere('utility_type', $utilityType);

        $meterNo = $meter?->hardware_meter_number
            ?? $meter?->serial_number
            ?? sprintf('%s-%d', strtoupper(substr($utilityType, 0, 3)), $resident->id);

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
            'total_bill' => $totalBill,
            'status' => $validated['status'],
            'is_done' => $validated['status'] === 'Paid',
            'payment_reference' => $this->generatePaymentReference($utilityType),
            'paid_at' => $validated['status'] === 'Paid' ? now() : null,
        ]);

        return redirect()
            ->route('admin.billingHistory')
            ->with(
                'success',
                "{$utilityType} bill created for {$resident->first_name} {$resident->last_name}."
            );
    }

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
