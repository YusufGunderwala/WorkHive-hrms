<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@dayflow.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Employee
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@dayflow.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        Employee::create([
            'user_id' => $user->id,
            'employee_id' => 'EMP-001',
            'department' => 'IT',
            'designation' => 'Software Engineer',
            'joining_date' => '2023-01-15',
            'phone' => '123-456-7890',
            'address' => '123 Main St, Tech City',
            'base_salary' => 5000.00,
        ]);

        // 3. Create another Employee
        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@dayflow.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        Employee::create([
            'user_id' => $user2->id,
            'employee_id' => 'EMP-002',
            'department' => 'HR',
            'designation' => 'HR Manager',
            'joining_date' => '2023-03-01',
            'phone' => '987-654-3210',
            'address' => '456 HR Lane, Admin City',
            'base_salary' => 6000.00,
        ]);

        // 4. Sample Attendance
        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::now()->subDay(),
            'check_in' => '09:00:00',
            'check_out' => '18:00:00',
            'status' => 'present',
        ]);
    }
}
