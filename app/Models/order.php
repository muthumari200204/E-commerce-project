<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'payment_method',
        'payment_status',
        'status',
        'grand_total',
        'currency',
        'shipping_method',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ✅ Custom relation to access addresses through the user
    public function userAddresses(): HasManyThrough
    {
        return $this->hasManyThrough(
            Address::class,
            User::class,
            'id',        // User's id
            'user_id',   // Address's user_id
            'user_id',   // Order's user_id
            'id'         // User's id
        );
    }
}
