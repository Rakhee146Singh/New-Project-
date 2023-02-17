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
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->string('name', 51);
            $table->string('description', 151);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->char('created_by', 36)->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->char('updated_by', 36)->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->char('deleted_by', 36)->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->boolean('is_deleted')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
