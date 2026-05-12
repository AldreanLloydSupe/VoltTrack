<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bill;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminResidentController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        Bill::markPastDueAsOverdue();

        $search = trim((string) $request->input('search'));

        $residents = User::where('role', 'renter')
            ->when(
                Schema::hasColumn('users', 'status'),
                fn ($query) => $query->where('status', 'approved')
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($searchQuery) use ($search) {
                        $searchQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                    });
                }
            )
            ->with(['property', 'bills'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.residentList', [
            'residents' => $residents,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        Bill::markPastDueAsOverdue();

        $resident = User::with(['properties', 'bills'])
            ->findOrFail($id);

        $utility = (string) $request->query('utility', 'all');
        $months = (int) $request->query('months', 6);

        if (! in_array($utility, ['all', 'Water', 'Electricity'], true)) {
            $utility = 'all';
        }

        $allowedMonths = [1, 3, 6, 12];
        if (! in_array($months, $allowedMonths, true)) {
            $months = 6;
        }

        $residentBillsQuery = $resident->bills()
            ->when(
                $utility !== 'all',
                fn ($query) => $query->where('utility_type', $utility)
            )
            ->whereDate('billing_period_end', '>=', now()->subMonths($months)->startOfDay())
            ->latest();

        $residentBills = $residentBillsQuery
            ->paginate(10, ['*'], 'transactions_page')
            ->withQueryString();

        return view('admin.residentInfo', [
            'resident' => $resident,
            'residentBills' => $residentBills,
            'selectedUtility' => $utility,
            'selectedMonths' => $months,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $resident = User::with(['property', 'properties'])->findOrFail($id);

        return view('admin.Update.updateResident', [
            'resident' => $resident,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $resident = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => [
                'required',
                'digits:10',
                function (string $attribute, mixed $value, \Closure $fail) use ($resident) {
                    if (User::where('phone_number', '+63' . $value)->whereKeyNot($resident->id)->exists()) {
                        $fail('The phone number has already been taken.');
                    }
                },
            ],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($resident->id)],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
        ]);

        $validated['phone_number'] = '+63' . $validated['phone_number'];

        $residentNameBefore = trim("{$resident->first_name} {$resident->last_name}");
        $resident->fill($validated);

        $changes = collect($resident->getDirty())
            ->mapWithKeys(fn ($value, $field) => [
                $field => [
                    'from' => $resident->getOriginal($field),
                    'to' => $value,
                ],
            ])
            ->all();

        $resident->save();

        if ($changes !== []) {
            $residentNameAfter = trim("{$resident->first_name} {$resident->last_name}");

            AuditLogger::log(
                $user,
                'Updated resident account',
                "Updated resident account for {$residentNameAfter}.",
                [
                    'resident_id' => $resident->id,
                    'resident_name_before' => $residentNameBefore,
                    'resident_name_after' => $residentNameAfter,
                    'changes' => $changes,
                ],
                'Residents',
                $request
            );
        }

        return redirect()
            ->route('admin.residentInfo', $resident->id)
            ->with('success', 'Resident account updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        $resident = User::query()
            ->with(['properties', 'utilityAssignments', 'bills', 'adminNotifications'])
            ->where('role', 'renter')
            ->findOrFail($id);

        $residentName = trim("{$resident->first_name} {$resident->last_name}");

        DB::transaction(function () use ($resident) {
            $resident->properties()->update([
                'user_id' => null,
                'status' => 'Inactive',
            ]);

            $resident->delete();
        });

        AuditLogger::log(
            $user,
            'resident_deleted',
            "Moved resident account for {$residentName} to deleted records.",
            [
                'resident_id' => $resident->id,
                'resident_name' => $residentName,
            ],
            'Residents',
            $request
        );

        return redirect()
            ->route('admin.residentList')
            ->with('success', "{$residentName} account moved to deleted records.");
    }

    // Sorting Kung residential ang i show or commercial
    public function residentList(Request $request)
    {
        return $this->list($request);
    }
}
