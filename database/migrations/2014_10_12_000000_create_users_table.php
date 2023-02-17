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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->string('first_name', 51);
            $table->string('last_name', 51)->nullable();
            $table->string('email', 51)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_first_login')->default(true);
            $table->string('code', 6)->nullable();
            $table->enum('type', ['superadmin', 'admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_deleted')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
