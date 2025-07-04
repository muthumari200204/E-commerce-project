<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orderitem extends Model
{
    protected $fillable =[
    'orderid',
    'product_id',
    'quantity',
    'unit_amount',
    'total_amount',
];

public function  order(){
    return $this->belongsTo(order::class);
}
public function  product(){
    return $this->belongsTo(product::class);
}
}
