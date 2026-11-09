<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technologies', function (Blueprint $table) {
            // Menambahkan kolom name setelah kolom id
            $table->string('name')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('technologies', function (Blueprint $table) {
            // Menghapus kolom name jika migrasi di-rollback
            $table->dropColumn('name');
        });
    }
};
