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
        Schema::create('khuyenmai', function (Blueprint $table) {
            $table->id();
            $table->string('tenkhuyenmai');
            $table->string('slug')->unique();
            $table->text('mota')->nullable();
            $table->date('ngaybatdau');
            $table->date('ngayketthuc');
            $table->integer('phantram');  // Phần trăm giảm giá
            $table->boolean('trangthai')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khuyenmai');
    }
};
