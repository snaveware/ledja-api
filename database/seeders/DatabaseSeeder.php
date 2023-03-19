<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        DB::table('user_types')->insert([
                'name' => 'jobseeker',
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('user_types')->insert([
                'name' => 'recruiter',
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_categories')->insert([
                'type' => 'basic',
                'cost' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_categories')->insert([
                'type' => 'standard',
                'cost' => 11000,
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_categories')->insert([
                'type' => 'premium',
                'cost' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('users')->insert([
                'user_type_id' => 1,
                'email' => 'jobseeker1@test.com',
                'password' => Hash::make('secret'),
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'user_type_id' => 2,
            'email' => 'recruiter1@test.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('wallets')->insert([
            'user_id' => 2,
            'amount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
