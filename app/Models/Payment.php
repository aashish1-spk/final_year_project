<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
