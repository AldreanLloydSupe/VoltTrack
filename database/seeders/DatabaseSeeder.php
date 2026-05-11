<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@volttrack.com',
        ], [
            'first_name' => 'System',
            'last_name' => 'Admin',
            'phone_number' => '09170000000',
            'gender' => 'Male',
            'password' => Hash::make('12345'),
            'role' => 'admin',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => null,
        ]);
    }
}
