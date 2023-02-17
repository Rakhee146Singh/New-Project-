<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->string('module_code', 7);
            $table->string('name', 64);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_in_menu', 4)->nullable();
            $table->string('display_order', 5)->nullable();
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
        // DB::statement("CREATE TABLE `modules` (
        //     `id` char(36) NOT NULL,
        //     `module_code` varchar(7) NOT NULL,
        //     `name` varchar(64) NOT NULL,
        //     `is_active` tinyint(1) NOT NULL DEFAULT '1' ,
        //     `is_in_menu` tinyint(4) DEFAULT '0',
        //     `display_order` int(5) DEFAULT NULL,
        //     `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        //     `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        //     `created_by` char(36) FOREIGN KEY REFERENCES `users`(`id`) ,
        //     `updated_by` char(36) FOREIGN KEY REFERENCES `users`(`id`),
        //     `deleted_by` char(36) FOREIGN KEY REFERENCES `users`(`id`),
        //     `deleted_at` timestamp NULL DEFAULT NULL
        //   )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
