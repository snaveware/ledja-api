<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_category_id',
        'job_status',
        'company_industry',
        'company_sub_industry',
        'title',
        'location',
        'description',
        'type',
        'no_of_hires',
        'hiring_speed',
        'own_completion',
        'with_recommendation',
        'with_resume',
        'communication_preferences',
        'skills_assessment',
        'apply_method',
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
