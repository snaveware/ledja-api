<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordResetToken extends Model
{
    use HasFactory;

    public $timestamps = false;



    protected $fillable = [
        'email',
        'code',
        'token',
        'created_at',
    ];

    protected function tokenExpired($createdAt)
    {

       return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }
}
