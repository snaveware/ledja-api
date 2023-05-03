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
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('job_status')->nullable()->change();
            $table->string('company_industry')->nullable()->change();
            $table->string('company_sub_industry')->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('description')->nullable()->change();
            $table->integer('no_of_hires')->nullable()->change();
            $table->string('hiring_speed')->nullable()->change();
            $table->boolean('own_completion')->nullable()->change();
            $table->boolean('with_recommendation')->nullable()->change();
            $table->string('communication_preferences')->nullable()->change();
            $table->string('apply_method')->nullable()->change();
            $table->string('salary')->nullable()->change();
            $table->string('experience_level')->nullable()->change();
            $table->string('category')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
};
