<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'job_id',
        'name',
        'email',
        'phone',
        'amount',
        'status',
        'transaction_id',
        'pidx',
        'tidx',
        'order_name',
    ];

     // A payment belongs to a user.
     
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
     // A payment belongs to a job.
     
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }



}
