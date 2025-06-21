<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nguoidung_id')->nullable();
            $table->string('session_id');
            $table->text('message');
            $table->boolean('is_bot')->default(false);
            $table->timestamps();

            $table->foreign('nguoidung_id')->references('id')->on('nguoidung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_messages', function (Blueprint $table) {
            //
        });
    }
};
