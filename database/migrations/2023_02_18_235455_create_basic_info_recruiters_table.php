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
        Schema::create('basic_info_recruiters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('company_name');
            $table->string('industry');
            $table->string('headquarters');
            $table->string('company_size');
            $table->unsignedBigInteger('revenue');
            $table->string('founded_on');
            $table->string('ceo');
            $table->string('avatar')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('company_avatar')->nullable();
            $table->string('company_avatar_url')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_info_recruiters');
    }
};
