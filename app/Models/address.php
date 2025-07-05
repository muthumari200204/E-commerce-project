<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'order_id',
        'full_name',
        'phone',
        'city',
        'state',
        'zip_code',
        'street_address',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
