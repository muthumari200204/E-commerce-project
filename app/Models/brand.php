<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
     protected $fillable = [
        'name',
        'slug',
        'image',
        'is_active',
    ];
}
