<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'country_id', 'status'];


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeAuthCity($query, $id)
    {
        return $query->where('country_id', $id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
