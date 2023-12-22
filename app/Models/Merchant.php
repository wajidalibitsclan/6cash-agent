<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchants';

    protected $casts = [
        'user_id' => 'integer',
        'store_name' => 'string',
        'callback' => 'string',
        'logo' => 'string',
        'address' => 'string',
        'bin' => 'string',
        'public_key' => 'string',
        'secret_key' => 'string',
        'merchant_number' => 'string',
    ];

    /**
     * @return BelongsTo
     */
    public function merchant_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
