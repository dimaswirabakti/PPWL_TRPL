<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel projects. Jika project dihapus, gambar juga terhapus
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('image'); // path untuk menyimpan file
            $table->string('caption')->nullable(); // boleh kosong
            $table->integer('sort_order')->default(0); // urutan tampil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_images');
    }
};
