<?php

namespace Modules\Admin\Services;

use Illuminate\Http\Request;
use Modules\Admin\Entities\Attribute;
use Modules\Admin\Entities\Car;

class CarService
{
    public function create(Request $request)
    {
        $this->getAttributes($request);
        $car = Car::create($request->only('title', 'description'));
        /** @var Car $car */
        $car->attributes()->sync($this->getAttributes($request));
    }

    /**
     * Format car attributes
     *
     * @param Request $request
     * @return array
     */
    private function getAttributes(Request $request)
    {
        $sync = [];

        // Get attribute values
        $attributes = $request->get('attributes');
        Attribute::whereIn('name', array_keys($attributes))
            ->get()->pluck('name', 'id')->transform(function($name, $id) use(&$sync, $attributes) {
                $sync[$id] = [
                      'value' => collect($attributes)->get($name)
                ];
            });

        // Add tags attribute
        if ($tags = $request->get('tags')) {
            $id = Attribute::whereName('tags')->first()->id;
            $sync[$id] = ['value' => collect($tags)->pluck('text')->implode(';')];
        }

        return $sync;
    }
}