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
        Schema::create('send_attempts_test', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->text('message');
            $table->string('status')->default('pending');
            $table->string('response_id')->nullable();
            $table->text('aditional_data')->nullable(); // el campo esta mal escrito
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('send_attempts_test');
    }
};
