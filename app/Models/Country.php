<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'status'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function currency()
    {
        return $this->hasOne(Currency::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithActiveCity($query)
    {
        return $query->with('cities', function ($subquery) {
            $subquery->where('status', 'active');
        });
    }

    public function scopeWithACtiveCityAndCurrency($query)
    {
        return $query->with(['cities' => function ($query) {
            $query->where('status', 'active');
        }, 'currency'])->get();
    }
}
