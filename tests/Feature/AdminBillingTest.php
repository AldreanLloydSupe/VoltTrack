<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBillingTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_bill_uses_latest_saved_current_reading_as_previous_reading(): void
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone_number' => '09000000001',
            'email' => 'admin@example.com',
            'house_number' => 'A1',
            'gender' => 'Male',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        $resident = User::create([
            'first_name' => 'Resident',
            'last_name' => 'User',
            'phone_number' => '09000000002',
            'email' => 'resident@example.com',
            'house_number' => 'R1',
            'gender' => 'Female',
            'password' => Hash::make('password'),
            'role' => 'renter',
            'status' => 'approved',
        ]);

        Bill::create([
            'user_id' => $resident->id,
            'meter_no' => 'ELEC-1',
            'utility_type' => 'Electricity',
            'previous_reading' => 100,
            'current_reading' => 150,
            'consumption' => 50,
            'reading_date' => '2026-04-01',
            'billing_period_start' => '2026-04-01',
            'billing_period_end' => '2026-04-30',
            'price_per_unit' => 10,
            'service_fee' => 100,
            'base_total_bill' => 600,
            'total_bill' => 600,
            'status' => 'Paid',
            'is_done' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.Create.storeNewElectricityBill'), [
                'resident_id' => $resident->id,
                'previous_reading' => 10,
                'current_reading' => 180,
                'reading_date' => '2026-05-01',
                'billing_period_start' => '2026-05-01',
                'billing_period_end' => '2026-05-31',
                'price_per_unit' => 10,
                'service_fee' => 100,
                'status' => 'Pending',
            ])
            ->assertRedirect(route('admin.residentInfo', $resident->id));

        $this->assertDatabaseHas('bills', [
            'user_id' => $resident->id,
            'utility_type' => 'Electricity',
            'previous_reading' => 150,
            'current_reading' => 180,
            'consumption' => 30,
            'total_bill' => 400,
        ]);
    }
}
