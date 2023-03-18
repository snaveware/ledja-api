<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Job extends Model
{
    use HasFactory;

    public function scopeType($query, $type)
    {
        return $query->where('type', 'like', '%'.$type.'%');
    }
    public function scopeSalary($query, $salary)
    {
        return $query->where( 'salary' , 'like', '%'.$salary.'%');
    }
    public function scopeTitle($query, $title)
    {
        return $query->where('title', 'like', '%'.$title.'%');
    }
    public function scopeExperienceLevel($query, $experience_level)
    {
        return $query->where('experience_level', 'like', '%'.$experience_level.'%');
    }
    public function scopeDatePosted($query, $date_posted)
    {
        return $query->where('created_at', 'like', '%'.$date_posted.'%');
    }
    public function scopeLocation($query, $location)
    {
        return $query->where('location', 'like', '%'.$location.'%');
    }
    // public function scopeSalary($query, $salary)
    // {
    //     return $query->where('salary', 'like', '%'.$salary.'%');
    // }
   
    

    protected $fillable = [
        'user_id',
        'job_category_id',
        'job_status',
        'company_industry',
        'company_sub_industry',
        'title',
        'location',
        'description',
        'salary',
        'experience_level',
        'type',
        'no_of_hires',
        'hiring_speed',
        'own_completion',
        'with_recommendation',
        'with_resume',
        'communication_preferences',
        // 'skills_assessment',
        'apply_method',
        'send_to_email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job_category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }


}
