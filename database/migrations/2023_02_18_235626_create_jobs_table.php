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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_category_id');
            $table->string('job_status');
            $table->string('company_industry');
            $table->string('company_sub_industry');
            $table->string('title');
            $table->string('location');
            $table->string('description');
            $table->string('salary');
            $table->string('experience_level');
            $table->string('type');
            $table->integer('no_of_hires');
            $table->string('hiring_speed');
            $table->boolean('own_completion');
            $table->boolean('with_recommendation');
            $table->boolean('with_resume');
            $table->string('communication_preferences');
            $table->string('skills_assessment');
            $table->string('apply_method');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('job_category_id')->references('id')->on('job_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
