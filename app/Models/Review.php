<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    
public function user() {
    return $this->belongsTo(User::class, 'reviewer_id'); 
}

public function job()
{
    return $this->belongsTo(Job::class);
}


public function replies()
{
    return $this->hasMany(ReviewReply::class);
}





}
