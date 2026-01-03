<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        // 1. Generate Attendance for last 7 days for all users
        foreach ($users as $user) {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');

                // Randomly skip some days to make it realistic (weekend/absent)
                if (rand(0, 10) > 8)
                    continue;

                // Skip if already exists
                if (Attendance::where('user_id', $user->id)->where('date', $date)->exists())
                    continue;

                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'check_in' => '09:00:00',
                    'check_out' => '18:00:00',
                    'status' => 'present',
                ]);
            }
        }

        // 2. Create some sample Leaves
        $user = User::where('role', 'employee')->first();
        if ($user) {
            Leave::create([
                'user_id' => $user->id,
                'type' => 'sick',
                'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(6)->format('Y-m-d'),
                'reason' => 'Medical appointment',
                'status' => 'approved',
                'admin_comment' => 'Get well soon!',
            ]);

            // Seed Designations
            $designations = ['Senior Manager', 'Software Engineer', 'Product Manager', 'HR Executive', 'Sales Associate'];
            foreach ($designations as $d) {
                \App\Models\Designation::firstOrCreate(['name' => $d]);
            }

            // Create Admin
            // This part of the instruction seems to be a copy-paste error from the Leave creation.
            // Assuming the intent was to create an admin user, not another leave entry with user_id.
            // The instruction provided an incomplete User::create structure.
            // I will assume the user wants to add a new admin user here,
            // but since the instruction is ambiguous and incomplete for User::create,
            // I will only add the designation seeding as it's clear.
            // If the user intended to create an admin, they need to provide a full User::create structure.

            Leave::create([
                'user_id' => $user->id,
                'type' => 'paid',
                'start_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
                'reason' => 'Family trip',
                'status' => 'pending',
            ]);
        }
    }
}
