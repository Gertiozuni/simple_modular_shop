<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Car;
use Modules\Admin\Http\Requests\CarRequest;
use Modules\Admin\Services\CarService;

class CarController extends Controller
{
    protected $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $cars = Car::paginate(12);

        return view('admin::cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::cars.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CarRequest $request)
    {
        $this->carService->create($request);

        return response()->json([
            'message' => 'Created'
        ]);
    }

    /**
     * Show the specified resource.
     * @param Car $car
     * @return Response
     */
    public function show(Car $car)
    {
        return view('admin::cars.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param Car $car
     * @return Response
     */
    public function edit(Car $car)
    {
        return view('admin::cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param Car $car
     * @return Response
     * @throws \Exception
     */
    public function destroy(Car $car)
    {
        $deleted = $car->delete();
        return response()->json(compact('deleted'));
    }

    public function toggleActivation(Car $car)
    {
        $car->update(['active' => !$car->active]);

        return redirect()->back();
    }
}
