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
        Schema::table('job_seeker_links', function (Blueprint $table) {
            $table->string('websites')->nullable()->change();
            $table->string('linked_in')->nullable()->change();
            $table->string('twitter')->nullable()->change();
            $table->string('facebook')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_seeker_links', function (Blueprint $table) {
            //
        });
    }
};
