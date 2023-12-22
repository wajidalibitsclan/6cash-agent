<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddMoney extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'user_id', 'amount', 'status'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
