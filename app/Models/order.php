<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'payment_method',
        'grand_total',
        'payment_status',
        'status',
        'currency',
        'shipping_method',
        'notes',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
