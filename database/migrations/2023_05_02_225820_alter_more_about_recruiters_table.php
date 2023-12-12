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
        Schema::table('more_about_recruiters', function (Blueprint $table) {
            //
            $table->longText('company_intro')->nullable()->change();
            $table->longText('company_culture')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('more_about_recruiters', function (Blueprint $table) {
            //
        });
    }
};
