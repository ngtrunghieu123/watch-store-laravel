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
        Schema::create('sanpham_khuyenmai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->foreignId('khuyenmai_id')->constrained('khuyenmai')->onDelete('cascade');
            $table->timestamps();

            // Thêm ràng buộc unique để tránh trùng lặp
            $table->unique(['sanpham_id', 'khuyenmai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanpham_khuyenmai');
    }
};
