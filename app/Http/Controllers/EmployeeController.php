<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource (Admin).
     */
    public function index()
    {
        $employees = Employee::with('user')->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $designations = \App\Models\Designation::all();
        return view('admin.employees.create', compact('designations'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'department' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'base_salary' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee',
            ]);

            // Verify uniqueness
            $uniqueIdRequested = $request->employee_id ?? 'EMP-' . str_pad(User::count() + 1, 3, '0', STR_PAD_LEFT);
            if (Employee::where('employee_id', $uniqueIdRequested)->exists()) {
                $uniqueIdRequested = 'EMP-' . str_pad(User::count() + rand(10, 99), 3, '0', STR_PAD_LEFT);
            }

            Employee::create([
                'user_id' => $user->id,
                'employee_id' => $uniqueIdRequested,
                'department' => $request->department,
                'designation' => $request->designation,
                'joining_date' => $request->joining_date,
                'phone' => $request->phone ?? '', // Validate if mandatory
                'address' => $request->address ?? '',
                'base_salary' => $request->base_salary,
            ]);
        });

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $designations = \App\Models\Designation::all();
        return view('admin.employees.edit', compact('employee', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'designation' => 'required|string',
            'base_salary' => 'required|numeric',
        ]);

        $employee->user->update(['name' => $request->name]);
        $employee->update($request->only(['department', 'designation', 'phone', 'address', 'base_salary']));

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->user->delete(); // Cascade deletes employee
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    // Employee Self Profile
    public function showProfile()
    {
        $employee = Auth::user()->employee;
        return view('employee.profile', compact('employee'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Update User Email
        $user->update(['email' => $request->email]);

        // Update Employee details
        $user->employee->update($request->only(['phone', 'address']));

        return back()->with('success', 'Profile updated successfully.');
    }
}
