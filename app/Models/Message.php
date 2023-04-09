<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'jobseeker_id',
        'application_id',
        'job_id',
        'status',
        'recruiter_message',
        'jobseeker_message',
        'has_jobseeker_read',
        'has_recruiter_read'
    ];
}
