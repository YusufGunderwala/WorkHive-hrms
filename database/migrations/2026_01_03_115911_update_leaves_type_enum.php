<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL for MariaDB/MySQL compatibility to update ENUM
        DB::statement("ALTER TABLE leaves MODIFY COLUMN type ENUM('sick', 'casual', 'earned', 'unpaid') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert not strictly necessary for this pivot
    }
};
