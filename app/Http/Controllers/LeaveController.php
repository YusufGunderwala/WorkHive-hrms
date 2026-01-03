<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    // Admin: View all leaves
    public function adminIndex()
    {
        $leaves = Leave::with('user')->latest()->paginate(10);
        return view('admin.leaves.index', compact('leaves'));
    }

    // Admin: Approve/Reject
    public function updateStatus(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'nullable|string',
        ]);

        $leave->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
        ]);

        return back()->with('success', 'Leave status updated.');
    }

    // Employee: My Leaves
    public function index()
    {
        $leaves = Auth::user()->leaves()->latest()->paginate(5);
        return view('employee.leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('employee.leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sick,casual,earned,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        Auth::user()->leaves()->create($request->all());

        return redirect()->route('employee.leaves.index')->with('success', 'Leave request submitted.');
    }
}
