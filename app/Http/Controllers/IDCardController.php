<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IDCardController extends Controller
{
    public function show()
    {
        $employee = Auth::user()->employee;
        return view('employee.id-card', compact('employee'));
    }
}
