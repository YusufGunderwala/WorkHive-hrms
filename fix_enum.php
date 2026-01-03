<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\DB::statement("ALTER TABLE leaves MODIFY COLUMN type ENUM('sick', 'casual', 'earned', 'unpaid') NOT NULL");
    echo "Success: ENUM updated.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
