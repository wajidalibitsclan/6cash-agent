<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $casts = [
        'unique_id' => 'integer',
        'sender' => 'integer',
        'receiver' => 'integer',
        'receiver_type' => 'string',
        'amount' => 'float:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
