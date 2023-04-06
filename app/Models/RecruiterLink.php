<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruiterLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'websites',
        'linked_in',
        'twitter',
        'facebook',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
