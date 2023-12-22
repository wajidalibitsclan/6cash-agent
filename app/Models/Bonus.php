<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $casts = [
        'min_add_money_amount' => 'float',
        'max_bonus_amount' => 'float',
        'limit_per_user' => 'integer',
    ];
}
