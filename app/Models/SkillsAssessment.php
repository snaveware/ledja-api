<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class SkillsAssessment extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        'job_id',
        'title',
    ];
}
