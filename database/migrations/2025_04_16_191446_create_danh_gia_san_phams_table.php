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
        Schema::create('danhgiasanpham', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanpham_id')->constrained('sanpham')->onDelete('cascade');
            $table->foreignId('nguoidung_id')->constrained('nguoidung')->onDelete('cascade');
            $table->unsignedTinyInteger('sosao')->default(5);
            $table->text('binhluan')->nullable();
            $table->string('hinhanh')->nullable();
            $table->unsignedTinyInteger('kiemduyet')->default(0);
            $table->unsignedTinyInteger('kichhoat')->default(1);
            $table->timestamps();

            // Một người dùng chỉ được đánh giá một sản phẩm một lần
            $table->unique(['sanpham_id', 'nguoidung_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danhgiasanpham');
    }
};
