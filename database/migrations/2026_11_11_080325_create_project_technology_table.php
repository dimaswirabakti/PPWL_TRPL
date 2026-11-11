<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot many-to-many project <-> technology.
     */
    public function up(): void
    {
        Schema::create('project_technology', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained()->cascadeOnDelete();

            // Satu technology hanya boleh tercatat sekali per project
            $table->primary(['project_id', 'technology_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_technology');
    }
};
