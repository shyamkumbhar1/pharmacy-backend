<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@pharmacy.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'subscription_status' => 'active',
        ]);

        // Create Sample Pharmacist
        User::create([
            'name' => 'Test Pharmacist',
            'email' => 'pharmacist@pharmacy.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
            'trial_started_at' => Carbon::now(),
            'trial_ends_at' => Carbon::now()->addDays(7),
            'subscription_status' => 'trial',
            'is_active' => true,
        ]);
    }
}

