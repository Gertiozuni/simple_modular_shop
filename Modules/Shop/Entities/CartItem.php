<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\Car;

class CartItem extends Model
{
    protected $fillable = [
        'quantity',
        'name',
        'price',
        'total',
        'product_id',
        'cart_id'
    ];

    protected $casts = [
        'price' => 'float',
        'total' => 'float'
    ];

    public function product()
    {
        return $this->belongsTo(Car::class, 'product_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
