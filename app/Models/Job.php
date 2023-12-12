<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class Job extends Model
{
    use HasFactory;

    public function scopeJobTypes($query, $types)
    {
        
        $all_types = [];
        $types = explode(",", $types);
       
        $query->whereHas('job_types', function($q) use ($types) {
                    if (count($types) > 0 )
                    {
                      
                            if(count($types) == 1)
                            {
                                
                                return $q->where('title', 'LIKE', '%'.$types[0].'%');
                                
                            }

                            if(count($types) == 2)
                            {
                                
                                return $q->where('title', 'LIKE', '%'.$types[0].'%')
                                            ->orWhere('title', 'LIKE', '%'.$types[1].'%') ;
                                
                            }

                            if(count($types) == 3)
                            {
                                return $q->where('title', 'LIKE', '%'.$types[0].'%')
                                ->orWhere('title', 'LIKE', '%'.$types[1].'%') 
                                ->orWhere('title', 'LIKE', '%'.$types[2].'%'); 
                                
                            }

                            if(count($types) > 3)
                            {
                                return $q->where('title', 'LIKE', '%'.$types[0].'%')
                                ->orWhere('title', 'LIKE', '%'.$types[1].'%') 
                                ->orWhere('title', 'LIKE', '%'.$types[2].'%'); 
                                
                            }                
                    }

                });
    }

    public function scopeSalary($query, $salary)
    {
        return $query->where( 'salary' , 'like', '%'.$salary.'%');
    }

    public function scopeTitle($query, $title)
    {
        return $query->where('title', 'like', '%'.$title.'%');
    }

    public function scopeExperienceLevel($query, $experience_level)
    {
        return $query->where('experience_level', 'like', '%'.$experience_level.'%');
    }

    public function scopeDatePosted($query, $date_posted)
    {
        return $query->where('created_at', 'like', '%'.$date_posted.'%');
    }

    public function scopeLocation($query, $location)
    {
        return $query->where('location', 'like', '%'.$location.'%');
    }
   
    

    protected $fillable = [
        'user_id',
        'skills_assessment_id',
        'job_category_id',
        'company_id',
        'hiring_company',
        'job_status',
        'category',
        'company_industry',
        'company_sub_industry',
        'title',
        'application_link',
        'location',
        'description',
        'salary',
        'experience_level',
        'no_of_hires',
        'hiring_speed',
        'own_completion',
        'with_recommendation',
        'with_resume',
        'communication_preferences',
        'apply_method',
        'send_to_email',
        'requirements',
        'responsibilites',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function skills_assessment(): BelongsTo
    {
        return $this->belongsTo(SkillsAssessment::class);
    }

    public function job_category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function job_types(): BelongsToMany
    {
        return $this->belongsToMany(JobType::class);
    }

    public function saved_jobs(): BelongsToMany
    {
        return $this->belongsToMany(JobType::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }


}
