<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'industry',
        'headquarters',
        'company_size',
        'revenue',
        'founded_on',
        'avatar',
        'avatar_url',
        'company_avatar',
        'company_avatar_url',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }


}
