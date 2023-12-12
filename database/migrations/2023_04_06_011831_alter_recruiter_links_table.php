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
        Schema::table('recruiter_links', function (Blueprint $table) {
            $table->string('websites')->nullable()->after('user_id')->change();
            $table->string('linked_in')->nullable()->after('user_id')->change();
            $table->string('twitter')->nullable()->after('user_id')->change();
            $table->string('facebook')->nullable()->after('user_id')->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruiter_links', function (Blueprint $table) {
            //
        });
    }
};
