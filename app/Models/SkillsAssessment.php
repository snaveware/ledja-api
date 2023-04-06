<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class SkillsAssessment extends Model
{
    use HasFactory;

    // Get all assessments for a particular job
    public function scopeJob($query, $job_id)
    {
        return $query->whereHas('jobs', $job_id);
    }

    public function scopeUserId($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeTitle($query, $title)
    {
        return $query->where('title', 'like', '%'.$title.'%');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }


    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    protected $fillable = [
        'user_id',
        'title',
    ];
}
