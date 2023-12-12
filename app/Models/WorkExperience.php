<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company',
        'duration',
        'description',
        'tasks',
        'start_date',
        'end_date',
        'is_current_position',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
