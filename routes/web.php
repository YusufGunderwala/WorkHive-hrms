<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shared Calendar Routes
    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [\App\Http\Controllers\CalendarController::class, 'events'])->name('calendar.events');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Attendance Management
        Route::get('attendance', [AttendanceController::class, 'adminIndex'])->name('attendance.index'); // View all

        // Leave Management
        Route::get('leaves', [LeaveController::class, 'adminIndex'])->name('leaves.index');
        Route::put('leaves/{leave}/status', [LeaveController::class, 'updateStatus'])->name('leaves.updateStatus');

        // Payroll Management
        Route::get('payroll', [PayrollController::class, 'adminIndex'])->name('payroll.index');
        Route::post('payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
        Route::patch('payroll/{payroll}/status', [PayrollController::class, 'updateStatus'])->name('payroll.updateStatus');

        // Designation Management
        Route::resource('designations', \App\Http\Controllers\DesignationController::class)->only(['index', 'store', 'destroy']);

        // Announcement Management
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->except(['show', 'edit', 'update']);
    });

    // Employee Routes
    Route::middleware('role:employee')->prefix('employee')->as('employee.')->group(function () {
        // Employee Dashboard
        Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('dashboard');

        // My Team
        Route::get('/team', [\App\Http\Controllers\TeamController::class, 'index'])->name('team.index');

        // My Payroll
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('/payroll/{payroll}/download', [PayrollController::class, 'download'])->name('payroll.download');

        // Attendance

        // My Profile
        Route::get('profile', [EmployeeController::class, 'showProfile'])->name('profile');
        Route::put('profile', [EmployeeController::class, 'updateProfile'])->name('profile.update');
        Route::get('id-card', [\App\Http\Controllers\IDCardController::class, 'show'])->name('id-card');

        // My Attendance
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
        Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut');

        // My Leaves
        Route::resource('leaves', LeaveController::class)->only(['index', 'create', 'store']);

        // My Payroll
        Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    });
});
