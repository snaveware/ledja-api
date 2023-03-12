<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\BasicInfoJobseekerController;
use App\Http\Controllers\UploadJobController;
use App\Http\Controllers\JobSeekerLinkController;
use App\Http\Controllers\AboutJobSeekerController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\BasicInfoRecruiterController;
use App\Http\Controllers\RecruiterLinkController;
use App\Http\Controllers\MoreAboutRecruiterController;
use App\Http\Controllers\AboutRecruiterController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\WorkExperienceController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\SkillController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(RegisterController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');

});

Route::post('send_reset_link', [PasswordResetLinkController::class, 'store']);


Route::middleware('auth:sanctum')->group( function () {
    Route::resource('user_types', UserTypeController::class);
    Route::get('/users', [RegisterController::class, 'index']);
    Route::post('/basic_infos/{id}', [BasicInfoJobseekerController::class, 'update']);
    Route::post('/upload_jobs/{id}', [UploadJobController::class, 'update']);


    Route::get('/users/{id}', [RegisterController::class, 'show']);
    Route::delete('/users/{id}', [RegisterController::class, 'destroy']);

    Route::resource('basic_infos', BasicInfoJobseekerController::class);
    Route::resource('upload_jobs', UploadJobController::class);
    Route::resource('job_seeker_links', JobSeekerLinkController::class);
    Route::resource('about_job_seekers', AboutJobSeekerController::class);

    // Recruiter Endpoints 
    Route::post('/jobs/{id}', [JobController::class, 'update']);
    Route::post('/applications/{id}', [ApplicationController::class, 'update']);
    Route::get('/get_user_jobs/{user_id}', [JobController::class, 'get_user_jobs']);
    Route::post('/filter_jobs', [JobController::class, 'filter_jobs']);
    Route::post('/transactions/{user_id}', [TransactionController::class, 'transact']);

    Route::resource('job_categories', JobCategoryController::class);
    Route::resource('recruiter_basic_infos', BasicInfoRecruiterController::class);
    Route::resource('recruiter_links', RecruiterLinkController::class);
    Route::resource('more_about_recruiters', MoreAboutRecruiterController::class);
    Route::resource('about_recruiters', AboutRecruiterController::class);
    Route::resource('jobs', JobController::class);
    Route::resource('applications', ApplicationController::class);
    Route::resource('wallets', WalletController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('education', EducationController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('work_experiences', WorkExperienceController::class);

    // Get individual wallet for user
    Route::get('wallets/user/{user_id}', [WalletController::class, 'get_user_wallet']);

});
