<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\FinancialSetting;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Handles AdminFinancialController responsibilities.
 */
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

    /**
     * Update.
     */
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

        $previousSettings = [
            'electricity_service_fee' => FinancialSetting::getAmount('electricity_service_fee', 100),
            'water_service_fee' => FinancialSetting::getAmount('water_service_fee', 100),
            'electricity_price_per_unit' => FinancialSetting::getAmount('electricity_price_per_unit', 0),
            'water_price_per_unit' => FinancialSetting::getAmount('water_price_per_unit', 0),
        ];

        $updatedSettings = [
            'electricity_service_fee' => (float) $validated['electricity_service_fee'],
            'water_service_fee' => (float) $validated['water_service_fee'],
            'electricity_price_per_unit' => (float) $validated['electricity_price_per_unit'],
            'water_price_per_unit' => (float) $validated['water_price_per_unit'],
        ];

        foreach ($updatedSettings as $key => $value) {
            FinancialSetting::setAmount($key, $value);
        }

        $changedSettings = $this->changedFinancialSettings($previousSettings, $updatedSettings);
        $changedGroups = $this->changedFinancialSettingGroups($changedSettings);

        AuditLogger::log(
            $user,
            'financial_settings_updated',
            $this->financialSettingsAuditDescription($changedGroups),
            [
                'previous' => $previousSettings,
                'updated' => $updatedSettings,
                'changed' => $changedSettings,
                'changed_groups' => array_values(array_keys($changedGroups)),
            ],
            'financials',
            $request
        );

        return back()->with('success', 'Financial service fees and unit prices saved successfully.');
    }

    /**
     * Changed financial settings.
     */
    protected function changedFinancialSettings(array $previousSettings, array $updatedSettings): array
    {
        $labels = [
            'electricity_service_fee' => 'Electricity service fee',
            'water_service_fee' => 'Water service fee',
            'electricity_price_per_unit' => 'Electricity price per unit',
            'water_price_per_unit' => 'Water price per unit',
        ];

        $changedSettings = [];

        foreach ($updatedSettings as $key => $updatedValue) {
            $previousValue = (float) ($previousSettings[$key] ?? 0);

            if (round($previousValue, 2) === round((float) $updatedValue, 2)) {
                continue;
            }

            $changedSettings[$key] = [
                'label' => $labels[$key] ?? str_replace('_', ' ', $key),
                'previous' => $previousValue,
                'updated' => (float) $updatedValue,
            ];
        }

        return $changedSettings;
    }

    /**
     * Changed financial setting groups.
     */
    protected function changedFinancialSettingGroups(array $changedSettings): array
    {
        $groups = [];

        foreach ($changedSettings as $key => $change) {
            $group = str_contains($key, 'service_fee') ? 'service_fees' : 'utility_prices';
            $groups[$group][] = $change['label'];
        }

        return $groups;
    }

    /**
     * Financial settings audit description.
     */
    protected function financialSettingsAuditDescription(array $changedGroups): string
    {
        if (empty($changedGroups)) {
            return 'Saved financial defaults with no value changes.';
        }

        $parts = [];

        if (isset($changedGroups['service_fees'])) {
            $parts[] = 'service fees ('.implode(', ', $changedGroups['service_fees']).')';
        }

        if (isset($changedGroups['utility_prices'])) {
            $parts[] = 'utility prices ('.implode(', ', $changedGroups['utility_prices']).')';
        }

        if (count($parts) === 1 && isset($changedGroups['service_fees'])) {
            return 'Updated financial defaults: service fees only - '.implode(', ', $changedGroups['service_fees']).'.';
        }

        if (count($parts) === 1 && isset($changedGroups['utility_prices'])) {
            return 'Updated financial defaults: utility prices only - '.implode(', ', $changedGroups['utility_prices']).'.';
        }

        return 'Updated financial defaults: '.implode('; ', $parts).'.';
    }
}
