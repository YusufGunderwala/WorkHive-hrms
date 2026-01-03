<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    // Admin: View all & Generate
    public function adminIndex()
    {
        $payrolls = Payroll::with('user')->latest()->paginate(20);
        return view('admin.payroll.index', compact('payrolls'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|string', // e.g. "2024-01" or "January 2024"
        ]);

        $employees = Employee::with('user')->get();

        foreach ($employees as $emp) {
            // Check if payroll already exists
            $exists = Payroll::where('user_id', $emp->user_id)->where('month', $request->month)->exists();
            if ($exists)
                continue;

            $components = $this->calculatePayrollComponents($emp, $request->month);

            Payroll::create([
                'user_id' => $emp->user_id,
                'month' => $request->month,
                'basic_salary' => $emp->base_salary,
                'overtime_hours' => $components['overtime_hours'],
                'bonus' => $components['bonus'],
                'late_instances' => $components['late_instances'],
                'deductions' => $components['deductions'],
                'net_salary' => $emp->base_salary + $components['bonus'] - $components['deductions'],
                'status' => 'unpaid',
            ]);
        }

        return back()->with('success', 'Payroll generated for ' . $request->month);
    }

    private function calculatePayrollComponents(Employee $employee, $month)
    {
        // Parse the month string "YYYY-MM"
        $startDate = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
        $endDate = \Carbon\Carbon::parse($month . '-01')->endOfMonth();

        // Fetch attendance records for this employee in the given month
        $attendances = \App\Models\Attendance::where('user_id', $employee->user_id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $overtimeHours = 0;
        $lateInstances = 0;

        // Settings (Could be moved to a settings table later)
        $standardWorkHours = 9;
        $lateTimeThreshold = '09:30:00';
        $lateFineAmount = 100; // Flat fine per late instance

        foreach ($attendances as $attendance) {
            // 1. Calculate Late Instances
            if ($attendance->check_in) {
                $checkInTime = \Carbon\Carbon::parse($attendance->check_in);
                // Only count as late if check-in is after 9:30 AM
                $threshold = \Carbon\Carbon::parse($attendance->date . ' ' . $lateTimeThreshold);

                if ($checkInTime->gt($threshold)) {
                    $lateInstances++;
                }
            }

            // 2. Calculate Overtime
            if ($attendance->check_in && $attendance->check_out) {
                $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                $checkOut = \Carbon\Carbon::parse($attendance->check_out);

                // Calculate hours worked (decimal)
                $hoursWorked = $checkOut->diffInMinutes($checkIn) / 60;

                if ($hoursWorked > $standardWorkHours) {
                    $overtimeHours += ($hoursWorked - $standardWorkHours);
                }
            }
        }

        // Calculate Financials
        // Hourly Rate = (Base Salary / 30 days / 9 hours)
        $hourlyRate = ($employee->base_salary / 30) / $standardWorkHours;

        // Bonus (Overtime) calculation: 1.5x hourly rate
        $bonus = $overtimeHours * ($hourlyRate * 1.5);

        // Deductions calculation: (Late Fines)
        // Note: Unpaid leaves logic requires Leave model integration. For now, we focus on late fines.
        $deductions = $lateInstances * $lateFineAmount;

        return [
            'overtime_hours' => round($overtimeHours, 2),
            'bonus' => round($bonus, 2),
            'late_instances' => $lateInstances,
            'deductions' => round($deductions, 2),
        ];
    }

    // Employee: My Pay-slips
    public function index()
    {
        $payrolls = Auth::user()->payrolls()->latest()->paginate(10);
        return view('employee.payroll.index', compact('payrolls'));
    }

    public function download(Payroll $payroll)
    {
        // Enforce ownership check
        if ($payroll->user_id !== Auth::id()) {
            abort(403);
        }

        return view('employee.payroll.pdf', compact('payroll'));
    }

    public function updateStatus(Request $request, Payroll $payroll)
    {
        $request->validate(['status' => 'required|in:paid,unpaid']);
        $payroll->update(['status' => $request->status]);

        return back()->with('success', 'Payment status updated successfully!');
    }
}
