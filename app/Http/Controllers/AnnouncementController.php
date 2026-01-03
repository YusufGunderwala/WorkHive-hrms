<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        // Fetch distinct departments from employees table
        $departments = Employee::select('department')->distinct()->pluck('department');
        return view('admin.announcements.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'department' => 'required|string',
            'type' => 'required|in:info,warning,urgent',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'department' => $request->department,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully!');
    }
}
