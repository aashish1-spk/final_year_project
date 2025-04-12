<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'skills',
        'experience',
        'education',
        'objective',
        'certifications',
        'languages',
        'references',
        'github_link',       // Added field
        'linkedin_link',     // Added field
        'user_id',           // Add this to the fillable property
        'cv_file_path',      // Add cv_file_path here
    ];
}
