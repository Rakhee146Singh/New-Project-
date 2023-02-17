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
        Schema::create('module_permissions', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->char('permission_id', 36);
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->char('module_id', 36);
            $table->foreign('module_id')->references('id')->on('modules');
            $table->boolean('add_access')->nullable();
            $table->boolean('edit_access')->nullable();
            $table->boolean('delete_access')->nullable();
            $table->boolean('view_access')->nullable();
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
        Schema::dropIfExists('module_permissions');
    }
};
