<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
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

    public function basic_info_jobseekers(): HasMany
    {
        return $this->hasMany(BasicInfoJobseeker::class);
    }

    public function basic_info_recruiters(): HasMany
    {
        return $this->hasMany(BasicInfoRecruiter::class);
    }

    public function job_seeker_links(): HasMany
    {
        return $this->hasMany(JobSeekerLink::class);
    }

    public function recruiter_links(): HasMany
    {
        return $this->hasMany(RecruiterLink::class);
    }

    public function about_job_seekers(): HasMany
    {
        return $this->hasMany(AboutJobSeeker::class);
    }

    public function about_recruiters(): HasMany
    {
        return $this->hasMany(AboutRecruiter::class);
    }

    public function more_about_recruiters(): HasMany
    {
        return $this->hasMany(MoreAboutRecruiter::class);
    }

    public function upload_jobs(): HasMany
    {
        return $this->hasMany(UploadJob::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
