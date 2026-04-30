<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        Bill::markPastDueAsOverdue();

        $hasUserStatus = Schema::hasColumn('users', 'status');

        $approvedResidents = User::query()
            ->where('role', 'renter')
            ->when($hasUserStatus, fn ($query) => $query->where('status', 'approved'))
            ->count();

        // Make dashboard counters react immediately when a resident is approved.
        $totalBoardingHouses = $approvedResidents;
        $activeRentals = $approvedResidents;

        $overduePayments = Bill::where('status', 'Overdue')->count();

        $collectedMonthly = Bill::query()
            ->where('status', 'Paid')
            ->whereBetween('reading_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total_bill');

        $pendingApprovals = $hasUserStatus
            ? User::where('role', 'renter')->where('status', 'pending')->count()
            : 0;

        $pendingBillsCount = Bill::query()
            ->whereIn('status', ['Pending', 'Overdue'])
            ->count();

        $pending_bills = Bill::with(['user.property'])
            ->whereIn('status', ['Pending', 'Overdue'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'pending_bills' => $pending_bills,
            'totalBoardingHouses' => $totalBoardingHouses,
            'activeRentals' => $activeRentals,
            'overduePayments' => $overduePayments,
            'collectedMonthly' => $collectedMonthly,
            'pendingApprovals' => $pendingApprovals,
            'approvedResidents' => $approvedResidents,
            'pendingBillsCount' => $pendingBillsCount,
        ]);
    }
}
