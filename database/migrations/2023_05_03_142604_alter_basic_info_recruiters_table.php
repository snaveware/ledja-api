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
        Schema::table('basic_info_recruiters', function (Blueprint $table) {
            $table->string('company_name')->nullable()->change();
            $table->string('industry')->nullable()->change();
            $table->string('headquarters')->nullable()->change();
            $table->string('company_size')->nullable()->change();
            $table->unsignedBigInteger('revenue')->nullable()->change();
            $table->string('founded_on')->nullable()->change();
            $table->string('ceo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_info_recruiters', function (Blueprint $table) {
            //
        });
    }
};
