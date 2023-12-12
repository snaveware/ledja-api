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
use App\Http\Controllers\JobTypeController;
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
use App\Http\Controllers\SkillsAssessmentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\OtherDocumentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VerificationController;


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

/* WebHook for receiving payments */
Route::post('/receive_payments', [PaymentController::class, 'receive_payments']);


/* Make jobs accessible everywhere */
Route::resource('jobs', JobController::class);
Route::get('/get_user_jobs/{user_id}', [JobController::class, 'get_user_jobs']);
Route::post('/filter_jobs', [JobController::class, 'filter_jobs']);

/* Make jobs accessible everywhere */
Route::get('/users', [RegisterController::class, 'index']);
Route::get('/users/{id}', [RegisterController::class, 'show']);

/* Password Reset Links */
Route::post('reset_password', [RegisterController::class, 'reset_password']);
Route::post('send_password_reset_mail',[MailController::class, 'store']);

Route::get('sendbasicemail',[MailController::class, 'basic_email']);
Route::get('sendhtmlemail',[MailController::class, 'html_email']);
Route::get('sendattachmentemail',[MailController::class, 'attachment_email']);


Route::post('send_reset_link', [PasswordResetLinkController::class, 'store']);

Route::get('/payments/success', [PaymentController::class, 'success']);

Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify'); 

Route::get('email/resend', [VerificationController::class,'resend'])->name('verification.resend')->middleware('auth:sanctum');



Route::middleware(['auth:sanctum'])->group( function () {


    Route::resource('user_types', UserTypeController::class);
    Route::post('/basic_infos/{id}', [BasicInfoJobseekerController::class, 'update']);
    Route::post('/recruiter_basic_infos/{id}', [BasicInfoRecruiterController::class, 'update']);
    Route::post('/applications/job/{job_id}', [ApplicationController::class, 'recruiter_applications']);
    Route::post('/applications/jobseeker/{user_id}', [ApplicationController::class, 'job_application_status']);
    Route::post('/upload_jobs/{id}', [UploadJobController::class, 'update']);
    Route::post('/saved_jobs/user/{user_id}/job/{job_id}', [SavedJobController::class, 'store']);
    Route::get('/get_user_saved_jobs/user/{user_id}', [SavedJobController::class, 'get_user_saved_jobs']);

    Route::get('/verify/{some_uuid}', [RegisterController::class, 'verify_email']);

    // PAYMENT ENDPOINTS
    Route::post('send_sms', [PaymentController::class, 'intiate_payment']);
    Route::post('/intiate_payment', [PaymentController::class, 'intiate_payment']);
    Route::post('/create_payment', [PaymentController::class, 'create_payment']);
    Route::post('/authorize_payment', [PaymentController::class, 'authorize_charge']);
    Route::post('/verify_payment/{transaction_id}', [PaymentController::class, 'verify_payment']);
    Route::post('/mpesa_pay', [PaymentController::class, 'mpesa_pay']);
    Route::post('/stk_push', [PaymentController::class, 'stk_push']);



    Route::delete('/users/{id}', [RegisterController::class, 'destroy']);

    Route::resource('basic_infos', BasicInfoJobseekerController::class);
    Route::resource('upload_jobs', UploadJobController::class);
    Route::resource('job_seeker_links', JobSeekerLinkController::class);
    Route::resource('about_job_seekers', AboutJobSeekerController::class);

    // Recruiter Endpoints 
    Route::post('/jobs/{id}', [JobController::class, 'update']);
    Route::post('/recruiter_basic_infos/{id}', [BasicInfoRecruiterController::class, 'update']);
    Route::post('/applications/{id}', [ApplicationController::class, 'update']);
    Route::get('get_active_applications/{job_id}', [ApplicationController::class, 'get_active_applications']);
   
    Route::post('/filter_assessments', [SkillsAssessmentController::class, 'filter_assessments']);
    Route::post('/transactions/{user_id}', [TransactionController::class, 'transact']);
    Route::get('/transactions/user/{user_id}', [TransactionController::class, 'get_transaction']);
    Route::get('/messages/user/{user_id}', [MessageController::class, 'get_user_message']);

    Route::resource('job_categories', JobCategoryController::class);
    Route::resource('job_types', JobTypeController::class);
    Route::resource('recruiter_basic_infos', BasicInfoRecruiterController::class);
    Route::resource('recruiter_links', RecruiterLinkController::class);
    Route::resource('more_about_recruiters', MoreAboutRecruiterController::class);
    Route::resource('about_recruiters', AboutRecruiterController::class);
    Route::resource('applications', ApplicationController::class);

    Route::resource('wallets', WalletController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('education', EducationController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('work_experiences', WorkExperienceController::class);
    Route::resource('skills_assessments', SkillsAssessmentController::class);
    Route::resource('questions', QuestionController::class);
    Route::resource('answers', AnswerController::class);
    Route::resource('results', ResultController::class);
    Route::resource('scores', ScoreController::class);
    Route::resource('saved_jobs', SavedJobController::class);
    Route::resource('other_documents', OtherDocumentController::class);
    Route::resource('messages', MessageController::class);
    Route::resource('companies', CompanyController::class);

    // Get individual wallet for user
    Route::get('wallets/user/{user_id}', [WalletController::class, 'get_user_wallet']);

    // Get results for the user
    Route::get('get_result/{user_id}/test/{test_id}', [ ResultController::class, 'get_result' ]);

    // Get score for the user
    Route::get('get_score/{user_id}/test/{test_id}', [ ScoreController::class, 'get_score' ]);
    Route::get('calculate_scores/{test_id}', [ ScoreController::class, 'calculate_scores' ]);


    /* PAYPAL INTEGRATION */
    Route::get('payment', 'PayPalController@payment')->name('payment');
    Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
    Route::get('payment/success', 'PayPalController@success')->name('payment.success');

});
