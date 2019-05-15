<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'items_count',
        'items_qty',
        'total',
        'is_guest',
        'customer_id',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function () {
            if (session()->has('cart')) {
                session()->forget('cart');
            }
        });
    }
}
