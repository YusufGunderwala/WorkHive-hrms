<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure user has an employee profile
        if (!$user->employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Employee profile not found.');
        }

        $department = $user->employee->department;

        // Fetch colleagues in the same department, excluding self
        // Also eager load 'employee' relationship to get designation, photo, etc.
        $colleagues = User::whereHas('employee', function ($query) use ($department) {
            $query->where('department', $department);
        })->with('employee')
            ->where('id', '!=', $user->id)
            ->get();

        return view('employee.team.index', compact('colleagues', 'department'));
    }
}
