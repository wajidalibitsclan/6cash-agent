<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'plateform_fee',
        'sender_fee',
        'receiver_fee',
        'admin_fee',
        'receiver_amount',
        'receiver_amount_exchange',
        'customer_phone',
        'secret_pin',
        'status',
        'base_currency_code',
        'destination_currency_code',
        'sender_id',
        'receiver_id',
        'country_id',
        'city_id',
        'amount',
        'sender_customer_id',
        'receiver_customer_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(Country::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'transaction_detail_id');
    }

    public function senderCustomer()
    {
        return $this->belongsTo(Customer::class, 'sender_customer_id');
    }

    public function receiverCustomer()
    {
        return $this->belongsTo(Customer::class, 'receiver_customer_id');
    }
}
