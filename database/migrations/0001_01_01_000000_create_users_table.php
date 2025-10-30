<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_admin')->default(false);
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');

            $table->string('gender', 6)->nullable(); //'male' or 'female'
            $table->boolean('is_activated')->default(true);
            $table->date('date_of_birth')->nullable();
            $table->string('account_type', 17)->nullable(); //'visually impaired' or 'normal'

            $table->string('caretaker_phone_number', 32)->nullable();
            $table->string('caretaker_name', 100)->nullable();


            $table->unsignedTinyInteger('testimonial_rate')
                ->nullable()
                ->check('testimonial_rate BETWEEN 1 AND 5');

            $table->text('testimonial_message')->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
