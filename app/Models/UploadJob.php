<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UploadJob extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'other_docs' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'resume',
        'resume_url',
        'other_docs',
        'other_docs_urls',
    ];
}
