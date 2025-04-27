<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // App\Models\Review.php

// In the Review model (App\Models\Review)
public function user() {
    return $this->belongsTo(User::class, 'reviewer_id'); // Update to 'reviewer_id'
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
