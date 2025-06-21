<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yeuthich', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoidung_id')->constrained('nguoidung')->onDelete('cascade');
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->timestamps();

            // Add unique constraint to prevent duplicates
            $table->unique(['nguoidung_id', 'sanpham_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yeuthich');
    }
};
