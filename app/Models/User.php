<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_type():BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    public function saved_jobs():BelongsToMany
    {
        return $this->belongsToMany(SavedJob::class);
    }

    public function basic_info_jobseeker(): HasOne
    {
        return $this->hasOne(BasicInfoJobseeker::class);
    }

    public function basic_info_recruiter(): HasOne
    {
        return $this->hasOne(BasicInfoRecruiter::class);
    }

    public function job_seeker_link(): HasOne
    {
        return $this->hasOne(JobSeekerLink::class);
    }

    public function recruiter_link(): HasOne
    {
        return $this->hasOne(RecruiterLink::class);
    }

    public function about_job_seeker(): HasOne
    {
        return $this->hasOne(AboutJobSeeker::class);
    }

    public function about_recruiter(): HasOne
    {
        return $this->hasOne(AboutRecruiter::class);
    }

    public function more_about_recruiter(): HasOne
    {
        return $this->hasOne(MoreAboutRecruiter::class);
    }

    public function upload_job(): HasOne
    {
        return $this->hasOne(UploadJob::class);
    }

    public function other_documents(): HasMany
    {
        return $this->hasMany(OtherDocument::class);
    }

    public function education(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function work_experiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function skills_assessments(): HasMany
    {
        return $this->hasMany(SkillAssessment::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }


}
