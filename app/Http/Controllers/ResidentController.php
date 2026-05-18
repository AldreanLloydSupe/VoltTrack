<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Throwable;

/**
 * Handles ResidentController responsibilities.
 */
class ResidentController extends Controller
{
    protected function residentBills(int $userId)
    {
        return Bill::query()
            ->where('user_id', $userId)
            ->latest('created_at');
    }

    /**
     * Dashboard.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        Bill::markPastDueAsOverdue();

        $bills = $this->residentBills($user->id)
            ->limit(5)
            ->get();

        $latestBill = $bills->first();
        $unpaidBills = Bill::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Overdue'])
            ->orderBy('billing_period_end', 'asc')
            ->get();

        $nextDueBill = $unpaidBills->first();
        $latestUnpaidElectricBill = $unpaidBills
            ->where('utility_type', 'Electricity')
            ->sortByDesc('billing_period_end')
            ->first();
        $latestUnpaidWaterBill = $unpaidBills
            ->where('utility_type', 'Water')
            ->sortByDesc('billing_period_end')
            ->first();

        $electricBill = $bills->firstWhere('utility_type', 'Electricity');
        $waterBill = $bills->firstWhere('utility_type', 'Water');

        // Sidebar summary should reflect unpaid obligations only.
        $totalDue = $unpaidBills->sum('amount_payable');
        $electricServiceFee = $latestUnpaidElectricBill?->service_fee ?? 0;
        $electricUsage = $latestUnpaidElectricBill
            ? max((float) $latestUnpaidElectricBill->amount_payable - (float) $latestUnpaidElectricBill->service_fee, 0)
            : 0;
        $waterUsage = $latestUnpaidWaterBill
            ? max((float) $latestUnpaidWaterBill->amount_payable - (float) $latestUnpaidWaterBill->service_fee, 0)
            : 0;
        $waterServiceFee = $latestUnpaidWaterBill?->service_fee ?? 0;

        $peakDemand = $electricBill?->consumption ?? 0;
        $sustainabilityScore = $bills->isNotEmpty()
            ? min(100, max(0, 100 - (int) round($totalDue / 9)))
            : 0;

        $adminReplies = Schema::hasTable('admin_notifications')
            && Schema::hasColumn('admin_notifications', 'reply_message')
            ? AdminNotification::query()
                ->with('repliedBy')
                ->where('resident_id', $user->id)
                ->whereNotNull('reply_message')
                ->latest('replied_at')
                ->limit(6)
                ->get()
            : collect();

        return view('residents.dashboard', [
            'user' => $user,
            'bills' => $bills,
            'latestBill' => $latestBill,
            'nextDueBill' => $nextDueBill,
            'electricBill' => $electricBill,
            'waterBill' => $waterBill,
            'totalDue' => $totalDue,
            'electricServiceFee' => $electricServiceFee,
            'electricUsage' => $electricUsage,
            'waterUsage' => $waterUsage,
            'waterServiceFee' => $waterServiceFee,
            'peakDemand' => $peakDemand,
            'sustainabilityScore' => $sustainabilityScore,
            'adminReplies' => $adminReplies,
        ]);
    }

    /**
     * History.
     */
    public function history(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        Bill::markPastDueAsOverdue();

        $bills = $this->residentBills($user->id)->get();

        return view('residents.history', [
            'user' => $user,
            'bills' => $bills,
        ]);
    }

    /**
     * Receipt.
     */
    public function receipt(Request $request, Bill $bill)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);
        abort_unless((int) $bill->user_id === (int) $user->id, 403);

        Bill::markPastDueAsOverdue();
        $bill->refresh();

        return view('residents.receipt', [
            'user' => $user,
            'bill' => $bill,
        ]);
    }

    /**
     * Settings.
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        return view('residents.settings', [
            'user' => $user,
        ]);
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'phone_number')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
        ]);

        $user->update($validated);

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Contact admin.
     */
    public function contactAdmin(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        return view('residents.contactAdmin', [
            'user' => $user,
        ]);
    }

    /**
     * Send contact admin.
     */
    public function sendContactAdmin(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->isResident(), 403);

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $admins = User::query()
            ->where('role', 'admin')
            ->get(['id', 'email']);

        if ($admins->isEmpty()) {
            return back()->withErrors([
                'subject' => 'No admin account is configured yet. Please contact support directly.',
            ])->withInput();
        }

        if (Schema::hasTable('admin_notifications')) {
            foreach ($admins as $admin) {
                AdminNotification::create([
                    'user_id' => $admin->id,
                    'resident_id' => $user->id,
                    'subject' => $validated['subject'],
                    'message' => $validated['message'],
                ]);
            }
        }

        $adminEmails = $admins
            ->pluck('email')
            ->filter()
            ->values()
            ->all();

        $emailSubject = '[VoltTrack] Resident Message: '.$validated['subject'];
        $emailBody = "Resident: {$user->first_name} {$user->last_name}\n"
            ."Email: {$user->email}\n"
            ."Phone: {$user->phone_number}\n\n"
            ."Message:\n{$validated['message']}";

        if (! empty($adminEmails)) {
            try {
                Mail::raw($emailBody, function ($message) use ($adminEmails, $emailSubject, $user) {
                    $message->to($adminEmails)
                        ->subject($emailSubject);

                    if (! empty($user->email)) {
                        $message->replyTo($user->email, trim("{$user->first_name} {$user->last_name}"));
                    }
                });
            } catch (Throwable $exception) {
                Log::error('Resident contact admin email failed.', [
                    'resident_id' => $user->id,
                    'error' => $exception->getMessage(),
                ]);

                return back()->with('success', 'Your message has been sent to admin. Email delivery failed, but admins were notified in-app.');
            }
        }

        return back()->with('success', 'Your message has been sent to admin.');
    }
}
