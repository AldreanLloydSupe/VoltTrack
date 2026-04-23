<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected function residentBills(int $userId)
    {
        return Bill::query()
            ->where('user_id', $userId)
            ->latest('billing_period_end');
    }

    public function admin(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        return view('admin.dashboard');
    }

    public function resident(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        $bills = $this->residentBills($user->id)
            ->limit(5)
            ->get();

        $latestBill = $bills->first();
        $electricBill = $bills->firstWhere('utility_type', 'Electricity');
        $waterBill = $bills->firstWhere('utility_type', 'Water');

        $totalDue = $bills->sum('total_bill');
        $electricFixed = $electricBill?->service_fee ?? 0;
        $electricVariable = $electricBill
            ? max((float) $electricBill->total_bill - (float) $electricBill->service_fee, 0)
            : 0;
        $waterServiceFee = $waterBill?->service_fee ?? 0;

        $peakDemand = $electricBill?->consumption ?? 0;
        $sustainabilityScore = $bills->isNotEmpty()
            ? min(100, max(0, 100 - (int) round($totalDue / 9)))
            : 0;

        return view('residents.dashboard', [
            'user' => $user,
            'bills' => $bills,
            'latestBill' => $latestBill,
            'electricBill' => $electricBill,
            'waterBill' => $waterBill,
            'totalDue' => $totalDue,
            'electricFixed' => $electricFixed,
            'electricVariable' => $electricVariable,
            'waterServiceFee' => $waterServiceFee,
            'peakDemand' => $peakDemand,
            'sustainabilityScore' => $sustainabilityScore,
        ]);
    }

    public function residentHistory(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        $bills = $this->residentBills($user->id)->get();

        return view('residents.history', [
            'user' => $user,
            'bills' => $bills,
        ]);
    }
}

