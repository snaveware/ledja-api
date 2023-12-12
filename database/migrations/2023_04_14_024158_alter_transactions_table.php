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
            $table->string('type')->nullable()->change();
            $table->integer('amount')->nullable()->change();
            $table->integer('payment_mode')->nullable()->after('amount');
            $table->integer('trx_otp')->nullable()->after('amount');
            $table->integer('trx_status')->nullable()->after('amount');
            $table->integer('trx_ref')->nullable()->after('amount');
            $table->integer('trx_id')->nullable()->after('amount');

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
