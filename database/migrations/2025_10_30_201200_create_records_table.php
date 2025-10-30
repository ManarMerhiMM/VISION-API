<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');


            $table->decimal('spo2', 5, 2)->nullable(); //90–100%
            $table->unsignedSmallInteger('heart_rate')->nullable(); //40–200 bpm
            $table->decimal('galvanic_skin_resistance', 8, 3)->nullable(); //2–20 µS (microsiemens) typical, may rise up to 50
            $table->decimal('relative_humidity', 5, 2)->nullable(); //20–90% depending on the environment.

    
            $table->timestamp('recorded_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};