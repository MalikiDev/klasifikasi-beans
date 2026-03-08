<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL, kita perlu mengubah kolom enum
        DB::statement("ALTER TABLE coffee_beans MODIFY COLUMN upload_mode ENUM('single', 'batch', 'folder') DEFAULT 'single'");
    }

    public function down(): void
    {
        // Rollback: kembalikan ke enum lama (single, batch)
        DB::statement("ALTER TABLE coffee_beans MODIFY COLUMN upload_mode ENUM('single', 'batch') DEFAULT 'single'");
    }
};
