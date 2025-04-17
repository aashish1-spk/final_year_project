<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // Define the fillable attributes for mass assignment protection
    protected $fillable = [
        'title', 'category_id', 'job_type_id', 'vacancy', 'salary', 'location', 'description', 
        'company_name', 'company_location', 'company_website', 'status', 'isFeatured', 'featured_request'
    ];

    public function jobType() {
        return $this->belongsTo(JobType::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function applications() {
        return $this->hasMany(JobApplication::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
