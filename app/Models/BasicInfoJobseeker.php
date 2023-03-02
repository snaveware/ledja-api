<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BasicInfoJobseeker extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'phone_no',
        'position',
        'location',
        'avatar'
    ];

}
