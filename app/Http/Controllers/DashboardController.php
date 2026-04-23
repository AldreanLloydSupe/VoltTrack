<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
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

        $bills = Bill::query()
            ->where('user_id', $user->id)
            ->latest('billing_period_end')
            ->limit(5)
            ->get();

        $latestBill = $bills->first();
        $electricBill = $bills->firstWhere('utility_type', 'Electricity');
        $waterBill = $bills->firstWhere('utility_type', 'Water');

        $totalDue = $bills->sum('total_bill');
        $electricFixed = $electricBill?->service_fee ?? 120;
        $electricVariable = $electricBill
            ? max((float) $electricBill->total_bill - (float) $electricBill->service_fee, 0)
            : 85.60;
        $waterServiceFee = $waterBill?->service_fee ?? 40;

        $peakDemand = $electricBill?->consumption ?? 14.2;
        $sustainabilityScore = min(
            100,
            max(72, 100 - (int) round(($totalDue ?: 258.92) / 9))
        );

        return view('residents.dashboard', [
            'user' => $user,
            'bills' => $bills,
            'latestBill' => $latestBill,
            'electricBill' => $electricBill,
            'waterBill' => $waterBill,
            'totalDue' => $totalDue ?: 258.92,
            'electricFixed' => $electricFixed,
            'electricVariable' => $electricVariable,
            'waterServiceFee' => $waterServiceFee,
            'peakDemand' => $peakDemand,
            'sustainabilityScore' => $sustainabilityScore,
        ]);
    }
}
