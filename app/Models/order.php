<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $fillable =[
        'user_id',
        'grand_total',
        'payement_method',
        'payment_ststus',
        'status',
        'currency',
        'shipping_amount',
        'shippimg_method',
        'notes',
    ];
       public function user(){
    return $this->belongsTo(user::class);
  }

       public function items(){
    return $this->hasMany(orderitem::class);
  }

  public function address(){
    return $this->hasOne(address::class);
  }
}


