<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'exchange_rate'];
    protected $casts = [
        'country' => 'string',
        'currency_code' => 'string',
        'currency_symbol' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
