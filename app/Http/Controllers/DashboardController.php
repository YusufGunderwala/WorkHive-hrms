<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('employee.dashboard');
    }

    public function adminDashboard()
    {
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $data[] = \App\Models\Attendance::whereDate('date', $date)->where('status', 'present')->count();
        }

        return view('admin.dashboard', compact('labels', 'data'));
    }

    public function employeeDashboard()
    {
        // Fetch Holidays via Service
        $upcomingHolidays = \App\Services\HolidayService::getCurrentMonthHolidays();

        // Leave Balances
        $user = \Illuminate\Support\Facades\Auth::user();
        $leaves = $user->leaves()->where('status', 'approved')->get();

        $leaveBalances = [
            'Sick' => ['used' => $leaves->where('type', 'sick')->count(), 'total' => 10],
            'Casual' => ['used' => $leaves->where('type', 'casual')->count(), 'total' => 10],
            'Earned' => ['used' => $leaves->whereIn('type', ['earned', 'paid'])->count(), 'total' => 15], // Map 'paid' to 'Earned'
            'Unpaid' => ['used' => $leaves->where('type', 'unpaid')->count(), 'total' => 0] // No limit usually
        ];

        // Fetch Announcements
        $announcements = \App\Models\Announcement::active()
            ->where(function ($query) use ($user) {
                $query->where('department', 'All');
                if ($user->employee && $user->employee->department) {
                    $query->orWhere('department', $user->employee->department);
                }
            })
            ->latest()
            ->get();

        // Check-in Status for Reminder
        $todayAttendance = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', \Carbon\Carbon::today())
            ->first();
        $hasCheckedIn = $todayAttendance ? true : false;

        return view('employee.dashboard', compact('upcomingHolidays', 'leaveBalances', 'announcements', 'hasCheckedIn'));
    }
}
