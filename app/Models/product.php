<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'images',
        'price',
        'category_id',
        'brand_id',
        'in_stock',
        'is_featured',
        'on_sale',
    ];

    protected $casts = [
        'images' => 'array',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'on_sale' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}