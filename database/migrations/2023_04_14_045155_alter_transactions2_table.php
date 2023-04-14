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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_mode')->nullable()->change();
            $table->integer('trx_otp')->nullable()->change();
            $table->string('trx_status')->nullable()->change();
            $table->string('trx_ref')->nullable()->change();
            $table->mediumText('trx_id')->nullable()->change();
            $table->string('mobile_no')->nullable()->after('amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
