<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Designation;
use Carbon\Carbon;
use Illuminate\Support\Str;

class IndianDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Define Departments and dummy Indian names
        // Expanded to ensure 50+ employees total
        $departments = [
            'Sales' => [
                ['name' => 'Aarav Sharma', 'role' => 'Sales Manager'],
                ['name' => 'Vihaan Gupta', 'role' => 'Sales Associate'],
                ['name' => 'Aditya Patel', 'role' => 'Sales Associate'],
                ['name' => 'Diya Verma', 'role' => 'Sales Trainee'],
                ['name' => 'Rahul Verma', 'role' => 'Sales Executive'],
                ['name' => 'Pooja Rao', 'role' => 'Senior Sales Associate'],
                ['name' => 'Amit Singh', 'role' => 'Regional Manager'],
                ['name' => 'Sneha Patil', 'role' => 'Sales Coordinator'],
                ['name' => 'Karthik Nair', 'role' => 'Business Development'],
                ['name' => 'Riya Sen', 'role' => 'Sales Associate'],
            ],
            'Marketing' => [
                ['name' => 'Ishaan Kumar', 'role' => 'Marketing Head'],
                ['name' => 'Sai Reddy', 'role' => 'SEO Specialist'],
                ['name' => 'Mira Nair', 'role' => 'Content Writer'],
                ['name' => 'Ananya Singh', 'role' => 'Social Media Manager'],
                ['name' => 'Vikram Rathore', 'role' => 'Brand Manager'],
                ['name' => 'Anjali Desai', 'role' => 'Digital Marketing Lead'],
                ['name' => 'Rohan Joshi', 'role' => 'Graphic Designer'],
                ['name' => 'Meera Kapoor', 'role' => 'Content Strategist'],
                ['name' => 'Sanya Malhotra', 'role' => 'PR Specialist'],
            ],
            'IT' => [
                ['name' => 'Rohan Mehta', 'role' => 'Senior Developer'],
                ['name' => 'Vikram Malhotra', 'role' => 'DevOps Engineer'],
                ['name' => 'Neha Iyer', 'role' => 'UI/UX Designer'],
                ['name' => 'Priya Das', 'role' => 'QA Engineer'],
                ['name' => 'Arjun Kapoor', 'role' => 'Full Stack Developer'],
                ['name' => 'Sameer Khan', 'role' => 'System Administrator'],
                ['name' => 'Zoya Akhtar', 'role' => 'Frontend Developer'],
                ['name' => 'Kabir Bedi', 'role' => 'Backend Developer'],
                ['name' => 'Farhan Akhtar', 'role' => 'Product Manager'],
                ['name' => 'Aditi Rao', 'role' => 'Data Analyst'],
            ],
            'HR' => [
                ['name' => 'Kavya Joshi', 'role' => 'HR Manager'],
                ['name' => 'Manish Tiwari', 'role' => 'Recruiter'],
                ['name' => 'Suresh Raina', 'role' => 'HR Operations'],
                ['name' => 'Rahul Dravid', 'role' => 'Training Coordinator'],
                ['name' => 'Sanjay Dutt', 'role' => 'Employee Relations'],
                ['name' => 'Juhi Chawla', 'role' => 'Talent Acquisition'],
                ['name' => 'Anil Kapoor', 'role' => 'HR Executive'],
                ['name' => 'Madhuri Dixit', 'role' => 'Payroll Specialist'],
            ],
            'Finance' => [
                ['name' => 'Rajesh Gupta', 'role' => 'Finance Manager'],
                ['name' => 'Anita Roy', 'role' => 'Accountant'],
                ['name' => 'Suresh Kumar', 'role' => 'Auditor'],
                ['name' => 'Sunita Menon', 'role' => 'Tax Consultant'],
                ['name' => 'Tarun Tahiliani', 'role' => 'Financial Analyst'],
                ['name' => 'Nikhil Nanda', 'role' => 'Risk Analyst'],
            ],
            'Operations' => [
                ['name' => 'Deepak Chopra', 'role' => 'Ops Manager'],
                ['name' => 'Sunil Chhetri', 'role' => 'Logistics Coordinator'],
                ['name' => 'Mary Kom', 'role' => 'Facility Manager'],
                ['name' => 'Saina Nehwal', 'role' => 'Inventory Specialist'],
                ['name' => 'PV Sindhu', 'role' => 'Operations Executive'],
            ],
            'Legal' => [
                ['name' => 'Harish Salve', 'role' => 'Legal Advisor'],
                ['name' => 'Ram Jethmalani', 'role' => 'Corporate Lawyer'],
                ['name' => 'Fali Nariman', 'role' => 'Compliance Officer'],
            ]
        ];

        // Seed Designations first to avoid issues
        $allRoles = [];
        foreach ($departments as $dept => $employees) {
            foreach ($employees as $emp) {
                $allRoles[] = $emp['role'];
            }
        }
        foreach (array_unique($allRoles) as $roleName) {
            Designation::firstOrCreate(['name' => $roleName]);
        }

        foreach ($departments as $dept => $employees) {
            foreach ($employees as $empData) {
                // 1. Create User (Idempotent with Try-Catch)
                $firstName = explode(' ', $empData['name'])[0];
                $email = strtolower($firstName) . '.' . strtolower($dept) . '@workhive.com';

                try {
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $empData['name'],
                            'password' => Hash::make('WorkHive@2026'),
                            'role' => 'employee',
                        ]
                    );

                    // Force update password if user already exists
                    if (!$user->wasRecentlyCreated) {
                        $user->update(['password' => Hash::make('WorkHive@2026')]);
                    }
                } catch (\Exception $e) {
                    $user = User::where('email', $email)->first();
                    if (!$user)
                        continue; // Should not happen

                    // Ensure password is updated
                    $user->update(['password' => Hash::make('WorkHive@2026')]);
                }

                // 2. Create/Update Employee Profile
                // FIXED: Use Stable ID based on User ID to prevent collisions
                $employee = Employee::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_id' => 'EMP-' . (1000 + $user->id),
                        'department' => $dept, // Use $dept from the loop
                        'designation' => $empData['role'], // Use $empData['role']
                        'joining_date' => Carbon::now()->subMonths(rand(4, 36)), // Generate dynamically
                        'phone' => '98' . rand(10000000, 99999999), // Generate dynamically
                        'address' => $this->getRandomIndianAddress(), // Generate dynamically
                        'base_salary' => rand(30000, 120000),
                    ]
                );

                // 3. Generate Attendance (Last 3 Months)
                $this->generateAttendance($user);

                // 4. Generate Leaves (More mixed history)
                $this->generateLeaves($user);

                // 5. Generate Payroll (Last 3 Months)
                $this->generatePayroll($user);
            }
        }
    }

    private function generateAttendance($user)
    {
        $startDate = Carbon::now()->subDays(90); // 3 Months

        // Clean up existing attendance
        Attendance::where('user_id', $user->id)
            ->where('date', '>=', $startDate->format('Y-m-d'))
            ->delete();

        for ($i = 0; $i < 90; $i++) {
            $date = $startDate->copy()->addDays($i);

            if ($date->isSunday())
                continue;

            // Logic for TODAY: High presence (aiming for ~47/55)
            if ($date->isToday()) {
                if (rand(1, 100) <= 15)
                    continue; // 15% chance absent today (resulting in ~85% present)
            } else {
                // Normal days: 8% Chance of being absent 
                if (rand(1, 100) <= 8)
                    continue;
            }

            $status = 'present';
            $hourIn = 9;
            $minIn = rand(0, 15);
            $hourOut = 18;
            $minOut = rand(0, 30);

            // Scenarios
            $scenario = rand(1, 100);

            if ($scenario <= 10) {
                // Late Arrival
                $hourIn = 9;
                $minIn = rand(31, 59);
            } elseif ($scenario <= 20) {
                // Early Departure
                $hourOut = 16;
                $minOut = rand(0, 59);
            } elseif ($scenario <= 35) {
                // Overtime
                $hourOut = rand(19, 21);
                $minOut = rand(0, 30);
            } elseif ($scenario <= 40) {
                // Half Day (Simulated by short hours)
                $hourIn = 10;
                $hourOut = 14;
            }

            // For today, if it's currently working hours, checkout might be null or future
            // But for simplicity in this dummy data, we'll just set it as if they checked out or will check out.
            // Or correct logic: if today, check_out should be null if now < 18:00? 
            // The user just wants "Mark Present", usually implies they are currently essentially "checked in".
            // Let's leave check_out as is for simplicity, or we case on time.
            // Let's keep it complete for "records" sake as requested "records everywhere".

            try {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => sprintf("%02d:%02d:00", $hourIn, $minIn),
                    'check_out' => sprintf("%02d:%02d:00", $hourOut, $minOut),
                    'status' => $status,
                ]);
            } catch (\Exception $e) {
            }
        }
    }

    private function generateLeaves($user)
    {
        // Valid Types: 'paid', 'sick', 'unpaid'
        // Valid Status: 'pending', 'approved', 'rejected'
        $leaveTypes = ['paid', 'sick', 'unpaid'];

        // 1. One Rejected Leave
        if (rand(1, 100) <= 30) {
            Leave::create([
                'user_id' => $user->id,
                'type' => 'paid',
                'start_date' => Carbon::now()->subDays(rand(60, 90))->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(rand(60, 90))->format('Y-m-d'),
                'reason' => 'Urgent piece of work',
                'status' => 'rejected',
                'admin_comment' => 'Team capacity is tight',
            ]);
        }

        // 2. Approved Leaves
        for ($k = 0; $k < rand(1, 3); $k++) {
            Leave::create([
                'user_id' => $user->id,
                'type' => $leaveTypes[array_rand($leaveTypes)],
                'start_date' => Carbon::now()->subDays(rand(10, 50))->format('Y-m-d'),
                'end_date' => Carbon::now()->subDays(rand(10, 50))->addDays(rand(0, 2))->format('Y-m-d'),
                'reason' => 'Personal requirement',
                'status' => 'approved',
                'admin_comment' => 'Approved',
            ]);
        }

        // 3. Pending Leave (Future)
        if (rand(1, 100) <= 40) {
            Leave::create([
                'user_id' => $user->id,
                'type' => 'sick',
                'start_date' => Carbon::now()->addDays(rand(2, 10))->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(rand(2, 10))->format('Y-m-d'),
                'reason' => 'Planned medical checkup',
                'status' => 'pending',
            ]);
        }
    }

    private function generatePayroll($user)
    {
        // Generate for last 3 months
        for ($m = 1; $m <= 3; $m++) {
            $date = Carbon::now()->subMonths($m);
            $monthStr = $date->format('Y-m');

            // Avoid duplicate payroll for same month
            if (Payroll::where('user_id', $user->id)->where('month', $monthStr)->exists())
                continue;

            $base = $user->employee->base_salary;
            $bonus = rand(0, 100) < 20 ? rand(2000, 5000) : 0;
            $deductions = rand(0, 100) < 10 ? rand(500, 2000) : 0;
            $overtimeHours = rand(0, 8);
            $overtimePay = ($base / 240) * $overtimeHours * 1.5;
            $net = $base + $bonus + $overtimePay - $deductions;

            Payroll::create([
                'user_id' => $user->id,
                'month' => $monthStr,
                'basic_salary' => $base,
                'overtime_hours' => $overtimeHours,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'net_salary' => $net,
                'status' => 'paid',
            ]);
        }

        // Current Month (Unpaid/Pending)
        $currentMonth = Carbon::now()->format('Y-m');
        if (!Payroll::where('user_id', $user->id)->where('month', $currentMonth)->exists()) {
            Payroll::create([
                'user_id' => $user->id,
                'month' => $currentMonth,
                'basic_salary' => $user->employee->base_salary,
                'overtime_hours' => 0,
                'bonus' => 0,
                'deductions' => 0,
                'net_salary' => $user->employee->base_salary,
                'status' => 'unpaid',
            ]);
        }
    }

    private function getRandomIndianAddress()
    {
        $areas = ['Andheri West', 'Bandra', 'Koramangala', 'Indiranagar', 'Connaught Place', 'Salt Lake', 'Jubilee Hills'];
        $cities = ['Mumbai', 'Bangalore', 'Delhi', 'Kolkata', 'Hyderabad', 'Pune'];

        return $areas[array_rand($areas)] . ', ' . $cities[array_rand($cities)];
    }
}
