<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class OtherDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'upload_job_id',
        'document',
        'document_url'
    ];

    public function upload():BelongsTo
    {
        return $this->belongsTo(UploadJob::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
