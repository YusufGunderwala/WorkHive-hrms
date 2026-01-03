<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Admin View: All attendance
    public function adminIndex()
    {
        $attendances = Attendance::with('user')->latest()->paginate(20);
        return view('admin.attendance.index', compact('attendances'));
    }

    // Employee View: My attendance
    public function index()
    {
        $attendances = Auth::user()->attendances()->latest()->paginate(10);
        return view('employee.attendance.index', compact('attendances'));
    }

    public function checkIn()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $existing = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if ($existing) {
            return back()->with('error', 'You have already checked in today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => Carbon::now()->format('H:i:s'),
            'status' => 'present',
        ]);

        return back()->with('success', 'Checked in successfully.');
    }

    public function checkOut()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if (!$attendance) {
            return back()->with('error', 'You have not checked in today.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'You have already checked out.');
        }

        $attendance->update([
            'check_out' => Carbon::now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Checked out successfully.');
    }
}
