<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'title',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'bool'
    ];

    protected $with = [
        'attributes'
    ];

    public function getTitleAttribute()
    {
        return ucfirst($this->attributes['title']);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withPivot('value');
    }

    public function getTags()
    {
        $tags = $this->attributes()->where('name', 'tags')->first()->pivot->value ?? "";

        return array_filter(explode(';', $tags));
    }

    public function hasSufficientQuantity()
    {
        return true; // TODO stock
    }

    public function get($key)
    {
        if ($attribute = $this->attributes()->where('name', $key)->first()) {
            return $attribute->pivot->value;
        }

        return null;
    }
}
