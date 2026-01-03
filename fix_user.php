<?php

use App\Models\User;
use App\Models\Employee;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'taher_angel@gmail.com')->first();
if ($user) {
    echo "Found user: " . $user->name . "\n";
    $employee = Employee::updateOrCreate(
        ['user_id' => $user->id],
        [
            'employee_id' => 'EMP-' . (1000 + $user->id),
            'department' => 'Sales',
            'designation' => 'Sales Executive',
            'joining_date' => now()->subYear(),
            'status' => 'active',
            'salary' => 50000 // default
        ]
    );
    echo "Updated Employee Dept: " . $employee->department . "\n";
} else {
    echo "User not found.\n";
}
