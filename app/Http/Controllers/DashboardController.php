<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Handles DashboardController responsibilities.
 */
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
            ->get()
            ->sum('amount_payable');

        $pendingApprovals = $hasUserStatus
            ? User::where('role', 'renter')->where('status', 'pending')->count()
            : 0;

        $pendingBillsCount = Bill::query()
            ->whereIn('status', ['Pending', 'Overdue'])
            ->count();

        $pending_bills = Bill::with(['user.property'])
            ->whereIn('status', ['Pending', 'Overdue'])
            ->latest()
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $weeklyStart = now()->startOfDay()->subDays(6);
        $weeklyPaidBills = Bill::query()
            ->where('status', 'Paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$weeklyStart, now()->endOfDay()])
            ->get(['total_bill', 'base_total_bill', 'paid_at']);

        $weeklyCollections = collect(range(0, 6))->map(function ($daysAgo) use ($weeklyStart, $weeklyPaidBills) {
            $date = $weeklyStart->copy()->addDays($daysAgo);
            $total = $weeklyPaidBills
                ->filter(fn ($bill) => $bill->paid_at?->isSameDay($date))
                ->sum('amount_payable');

            return [
                'label' => $date->format('M d'),
                'value' => round((float) $total, 2),
            ];
        })->values();

        $dailyStart = now()->startOfDay();
        $dailyPaidBills = Bill::query()
            ->where('status', 'Paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$dailyStart, now()->endOfDay()])
            ->get(['total_bill', 'base_total_bill', 'paid_at']);

        $dailyCollections = collect(range(0, 23))->map(function ($hour) use ($dailyStart, $dailyPaidBills) {
            $slotStart = $dailyStart->copy()->addHours($hour);
            $slotEnd = $slotStart->copy()->endOfHour();
            $total = $dailyPaidBills
                ->filter(fn ($bill) => $bill->paid_at && $bill->paid_at->betweenIncluded($slotStart, $slotEnd))
                ->sum('amount_payable');

            return [
                'label' => $slotStart->format('ga'),
                'value' => round((float) $total, 2),
            ];
        })->values();

        $monthlyStart = now()->startOfMonth()->subMonths(5);
        $monthlyPaidBills = Bill::query()
            ->where('status', 'Paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$monthlyStart, now()->endOfMonth()])
            ->get(['total_bill', 'base_total_bill', 'paid_at']);

        $monthlyCollections = collect(range(0, 5))->map(function ($monthsAgo) use ($monthlyStart, $monthlyPaidBills) {
            $date = $monthlyStart->copy()->addMonths($monthsAgo);
            $total = $monthlyPaidBills
                ->filter(fn ($bill) => $bill->paid_at?->format('Y-m') === $date->format('Y-m'))
                ->sum('amount_payable');

            return [
                'label' => $date->format('M Y'),
                'value' => round((float) $total, 2),
            ];
        })->values();

        $yearlyStart = now()->startOfYear()->subYears(4);
        $yearlyPaidBills = Bill::query()
            ->where('status', 'Paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$yearlyStart, now()->endOfYear()])
            ->get(['total_bill', 'base_total_bill', 'paid_at']);

        $yearlyCollections = collect(range(0, 4))->map(function ($yearOffset) use ($yearlyStart, $yearlyPaidBills) {
            $date = $yearlyStart->copy()->addYears($yearOffset);
            $total = $yearlyPaidBills
                ->filter(fn ($bill) => $bill->paid_at?->format('Y') === $date->format('Y'))
                ->sum('amount_payable');

            return [
                'label' => $date->format('Y'),
                'value' => round((float) $total, 2),
            ];
        })->values();

        return view('admin.dashboard', [
            'pending_bills' => $pending_bills,
            'totalBoardingHouses' => $totalBoardingHouses,
            'activeRentals' => $activeRentals,
            'overduePayments' => $overduePayments,
            'collectedMonthly' => $collectedMonthly,
            'pendingApprovals' => $pendingApprovals,
            'approvedResidents' => $approvedResidents,
            'pendingBillsCount' => $pendingBillsCount,
            'collectionChart' => [
                'daily' => [
                    'title' => 'Daily Collection',
                    'subtitle' => 'Paid bills for today by hour',
                    'total' => round((float) $dailyCollections->sum('value'), 2),
                    'items' => $dailyCollections,
                ],
                'weekly' => [
                    'title' => 'Weekly Collection',
                    'subtitle' => 'Paid bills for the last 7 days',
                    'total' => round((float) $weeklyCollections->sum('value'), 2),
                    'items' => $weeklyCollections,
                ],
                'monthly' => [
                    'title' => 'Monthly Collection',
                    'subtitle' => 'Paid bills for the last 6 months',
                    'total' => round((float) $monthlyCollections->sum('value'), 2),
                    'items' => $monthlyCollections,
                ],
                'yearly' => [
                    'title' => 'Yearly Collection',
                    'subtitle' => 'Paid bills for the last 5 years',
                    'total' => round((float) $yearlyCollections->sum('value'), 2),
                    'items' => $yearlyCollections,
                ],
            ],
        ]);
    }
}
