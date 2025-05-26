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
        'github_link',       
        'linkedin_link',    
        'user_id',           
        'cv_file_path',     
    ];
}
