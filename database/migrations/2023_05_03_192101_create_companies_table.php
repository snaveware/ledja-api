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
        Schema::create('companies', function (Blueprint $table) {


                /*  
                    'company_name',
                    'industry',
                    'headquarters',
                    'company_size',
                    'revenue',
                    'founded_on',
                    'company_avatar',
                    'company_avatar_url', 
                */

            $table->id();
            $table->string('company_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('headquarters')->nullable();
            $table->string('company_size')->nullable();
            $table->string('revenue')->nullable();
            $table->string('founded_on')->nullable();
            $table->string('company_avatar')->nullable();
            $table->string('company_avatar_url')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
