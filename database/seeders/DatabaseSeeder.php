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
        DB::table('user_types')->updateOrInsert([
                'name' => 'jobseeker',
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('user_types')->updateOrInsert([
                'name' => 'recruiter',
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_categories')->updateOrInsert([
                'type' => 'basic',
                'description' => 'Entry level,volunteer,internship roles.',
                'cost' => 2500,
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_categories')->updateOrInsert([
                'type' => 'standard',
                'description' => 'Highlighted relevant candidates, Embedded Skills Assessment Faster, more accurate shortlisting',
                'cost' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_categories')->updateOrInsert([
                'type' => 'premium',
                'description' => 'HR experts screen & interview,High-quality profiles delivered in 5 days,Dedicated HR expert support',
                'cost' => 11000,
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_categories')->updateOrInsert([
                'type' => 'link_out',
                'description' => 'Redirect applicants from our platform to yours, easily Increase your brand visibility with job seekers,Receive referral traffic from a trusted website,No integration required, easy one-click linkout',
                'cost' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('users')->updateOrInsert([
                'user_type_id' => 1,
                'email' => 'jobseeker1@test.com',
                'password' => Hash::make('secret'),
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('users')->updateOrInsert([
            'user_type_id' => 2,
            'email' => 'recruiter1@test.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('wallets')->updateOrInsert([
            'user_id' => 2,
            'amount' => 50000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('job_types')->updateOrInsert([
            'title' => 'Full-time',
            'description' => 'Full time job.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('job_types')->updateOrInsert([
                'title' => 'Part-time',
                'description' => 'Part time job.',
                'created_at' => now(),
                'updated_at' => now(),
        ]);

        DB::table('job_types')->updateOrInsert([
            'title' => 'Contract',
            'description' => 'Contractual job.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('job_types')->updateOrInsert([
            'title' => 'Temporary',
            'description' => 'Temporary job.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('job_types')->updateOrInsert([
            'title' => 'Internship',
            'description' => 'Internship job.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('job_types')->updateOrInsert([
            'title' => 'Seasonal',
            'description' => 'Seasonal job.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
       
    }
}
