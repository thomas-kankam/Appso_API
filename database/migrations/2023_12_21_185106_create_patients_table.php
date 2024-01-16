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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable()->default(null);
            $table->string('middle_name')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('password');
            $table->string("gender")->nullable();
            $table->date("date_of_birth")->nullable()->default(null);
            $table->string('age')->nullable();
            $table->string('phone_number')->nullable()->default(null);
            $table->string('bio_info')->nullable()->default(null);
            $table->string('national_id')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('national_id_front_image')->nullable();
            $table->string('national_id_back_image')->nullable();
            $table->string('passport_picture')->nullable();
            $table->string('occupation')->nullable()->default(null);
            $table->string('account_status')->nullable()->default('active');
            $table->boolean('verified')->default(false);
            $table->timestamp("phone_verified_at")->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
