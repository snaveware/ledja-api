<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class MoreAboutRecruiter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_intro',
        'company_culture'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
