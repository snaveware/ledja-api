<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Result extends Model
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

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    protected $fillable = [
        'skills_assessment_id',
        'user_id',
        'question_id',
        'answer',
        'points',
        'is_answer_correct'
    ];
}
