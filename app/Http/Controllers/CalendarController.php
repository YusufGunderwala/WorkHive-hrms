<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Leave;

class CalendarController extends Controller
{
    public function index()
    {
        return view('shared.calendar');
    }

    public function events(Request $request)
    {
        $user = Auth::user();
        $events = [];

        // 1. Fetch Attendance (For Employee only usually, or Admin if requested?)
        // Let's show OWN attendance for everyone.
        $attendances = Attendance::where('user_id', $user->id)
            ->whereDate('date', '>=', $request->start)
            ->whereDate('date', '<=', $request->end)
            ->get();

        foreach ($attendances as $att) {
            $events[] = [
                'title' => 'Present (' . substr($att->check_in, 0, 5) . ')',
                'start' => $att->date,
                'backgroundColor' => '#10b981', // green
                'borderColor' => '#10b981',
                'allDay' => true
            ];
        }

        // 2. Fetch Leaves
        $leaves = Leave::where('user_id', $user->id)
            ->get(); // Simplified date range filtering for demo

        foreach ($leaves as $leave) {
            $color = match ($leave->status) {
                'approved' => '#6366f1', // primary
                'rejected' => '#ef4444', // red
                default => '#f59e0b', // warning
            };

            $events[] = [
                'title' => ucfirst($leave->type) . ' Leave',
                'start' => $leave->start_date,
                'end' => $leave->end_date, // FullCalendar end is exclusive
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        }

        // 3. Fetch Holidays via Service
        $holidays = \App\Services\HolidayService::getHolidays();

        foreach ($holidays as $h) {
            $events[] = [
                'title' => '🎉 ' . $h['name'],
                'start' => $h['date'],
                'allDay' => true,
                'backgroundColor' => '#ec4899', // Pink/Festive
                'borderColor' => '#ec4899',
                'className' => 'holiday-event'
            ];
        }

        return response()->json($events);
    }
}
