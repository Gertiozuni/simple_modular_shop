<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Entities\Car;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $cars = Car::query();

        if ($tag = $request->get('tag')) {
            $cars->whereHas('attributes', function ($query) use ($tag) {
                $query->where('name', 'tags')->where('attribute_car.value', 'like', "%{$tag}%");
            });
        }
        $cars = $cars->paginate(12);

        return view('shop::index', compact('cars'));
    }
}
