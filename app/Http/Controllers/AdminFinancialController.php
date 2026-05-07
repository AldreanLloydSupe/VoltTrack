<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\FinancialSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminFinancialController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);

        Bill::markPastDueAsOverdue();

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $billColumns = ['service_fee', 'total_bill', 'paid_at'];

        if (Schema::hasColumn('bills', 'base_total_bill')) {
            $billColumns[] = 'base_total_bill';
        }

        $paidThisMonth = Bill::query()
            ->where('status', 'Paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->get($billColumns);

        $serviceFeeCollected = $paidThisMonth->sum('service_fee');
        $penaltyCollected = $paidThisMonth->sum(function (Bill $bill) {
            return (float) $bill->penalty_amount;
        });
        $vatCollected = $paidThisMonth->sum(function (Bill $bill) {
            return (float) $bill->vat_amount;
        });

        $monthlyStart = now()->startOfMonth()->subMonths(11);
        $paidFinancialBills = Bill::query()
            ->where('status', 'Paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$monthlyStart, $monthEnd])
            ->get($billColumns);

        $monthlyFinancials = collect(range(0, 11))->map(function ($monthOffset) use ($monthlyStart, $paidFinancialBills) {
            $date = $monthlyStart->copy()->addMonths($monthOffset);
            $monthlyBills = $paidFinancialBills
                ->filter(fn (Bill $bill) => $bill->paid_at?->format('Y-m') === $date->format('Y-m'));
            $serviceFeeTotal = $monthlyBills->sum('service_fee');
            $penaltyTotal = $monthlyBills->sum(function (Bill $bill) {
                return (float) $bill->penalty_amount;
            });
            $vatTotal = $monthlyBills->sum(function (Bill $bill) {
                return (float) $bill->vat_amount;
            });

            return [
                'label' => $date->format('M Y'),
                'service_fee' => round((float) $serviceFeeTotal, 2),
                'penalty' => round((float) $penaltyTotal, 2),
                'vat' => round((float) $vatTotal, 2),
                'value' => round((float) $serviceFeeTotal + (float) $penaltyTotal + (float) $vatTotal, 2),
            ];
        })->values();

        return view('admin.financials', [
            'serviceFeeCollected' => round((float) $serviceFeeCollected, 2),
            'penaltyCollected' => round((float) $penaltyCollected, 2),
            'vatCollected' => round((float) $vatCollected, 2),
            'monthlyServiceFeeChart' => [
                'title' => 'Monthly Financial Income',
                'subtitle' => 'Paid bills only, service fee, penalty, and VAT charges for the last 12 months',
                'total' => round((float) $monthlyFinancials->sum('value'), 2),
                'serviceFeeTotal' => round((float) $monthlyFinancials->sum('service_fee'), 2),
                'penaltyTotal' => round((float) $monthlyFinancials->sum('penalty'), 2),
                'vatTotal' => round((float) $monthlyFinancials->sum('vat'), 2),
                'items' => $monthlyFinancials,
            ],
            'electricityServiceFee' => FinancialSetting::getAmount('electricity_service_fee', 100),
            'waterServiceFee' => FinancialSetting::getAmount('water_service_fee', 100),
            'electricityPricePerUnit' => FinancialSetting::getAmount('electricity_price_per_unit', 0),
            'waterPricePerUnit' => FinancialSetting::getAmount('water_price_per_unit', 0),
            'canSaveSettings' => Schema::hasTable('financial_settings'),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);
        abort_unless(Schema::hasTable('financial_settings'), 503, 'Please run the financial settings migration before saving defaults.');

        $validated = $request->validate([
            'electricity_service_fee' => ['required', 'numeric', 'min:0'],
            'water_service_fee' => ['required', 'numeric', 'min:0'],
            'electricity_price_per_unit' => ['required', 'numeric', 'min:0'],
            'water_price_per_unit' => ['required', 'numeric', 'min:0'],
        ]);

        FinancialSetting::setAmount('electricity_service_fee', (float) $validated['electricity_service_fee']);
        FinancialSetting::setAmount('water_service_fee', (float) $validated['water_service_fee']);
        FinancialSetting::setAmount('electricity_price_per_unit', (float) $validated['electricity_price_per_unit']);
        FinancialSetting::setAmount('water_price_per_unit', (float) $validated['water_price_per_unit']);

        return back()->with('success', 'Financial service fees and unit prices saved successfully.');
    }
}
