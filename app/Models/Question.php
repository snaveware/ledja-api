<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills_assessment(): BelongsTo
    {
        return $this->belongsTo(SkillsAssessment::class);
    } 

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    protected $fillable = [
        'skills_assessment_id',
        'user_id',
        'content',
        'choice_a',
        'choice_b',
        'choice_c',
        'choice_d',
        'marks',
    ];
}
