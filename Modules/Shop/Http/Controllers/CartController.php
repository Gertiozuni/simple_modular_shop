<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Shop\Services\CartService;
use Modules\Admin\Entities\Car;
use Modules\Shop\Entities\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /** @var CartService $service */
    protected $cartService;

    public function __construct(CartService $service)
    {
        $this->cartService = $service;
    }

    public function add(Car $car)
    {
        try {
            $this->cartService->add($car, [
                'quantity' => 1,
                'name' => $car->title,
                'price' => $car->price,
                'total' => $car->price,
                'product_id' => $car->id
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function remove(CartItem $cartItem)
    {
        try {
            $this->cartService->removeItem($cartItem);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            foreach ($request->qty as $itemId => $qty) {
                $car = CartItem::find($itemId)->product;
                $this->cartService->updateItem($car, ['quantity' => $qty], $itemId);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function destroy()
    {
        try {
            $this->cartService->destroy();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}
