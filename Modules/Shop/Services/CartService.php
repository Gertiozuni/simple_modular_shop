<?php

namespace Modules\Shop\Services;

use Modules\Shop\Entities\Cart;
use Modules\Shop\Entities\CartItem;
use Modules\Admin\Entities\Car;

class CartService
{
    public function __construct()
    {
    }

    /**
     * Create new cart instance.
     *
     * @param Car  $id
     * @param array $data
     *
     * @return bool
     */
    public function create($car, $data, $qty = 1)
    {
        $cartData = ['items_count' => 1];

        if (auth()->check()) {
            $cartData['customer_id'] = auth()->user()->id;
            $cartData['is_guest'] = 0;
            $cartData['customer_name'] = auth()->user()->first_name;
            $cartData['customer_email'] = auth()->user()->email;
        } else {
            $cartData['is_guest'] = 1;
        }

        $cart = Cart::create($cartData);

        $this->putCart($cart);

        return $this->createItem($car, $data);
    }

    /**
     * Add Items in a cart with some cart and item details.
     *
     * @param int   $id
     * @param array $data
     */
    public function add($car, $data)
    {
        if ($this->getCart()) {
            $ifExists = $this->checkIfItemExists($car->id, $data);
            if ($ifExists) {
                $item = CartItem::findOrFail($ifExists);
                $data['quantity'] = $data['quantity'] + $item->quantity;
                $result = $this->updateItem($car, $data, $ifExists);
            } else {
                $result = $this->createItem($car, $data);
            }
        } else {
            $result =  $this->create($car, $data);
        }

        $this->collectTotals();

        return $result;
    }

    /**
     * To check if the items exists in the cart or not.
     *
     * @return bool
     */
    public function checkIfItemExists($id, $data)
    {
        return $this->getCart()->items()->where('product_id', $id)->first()->id ?? 0;
    }

    /**
     * Create the item based on the type of product whether simple or configurable.
     *
     * @return mixed Array $item || Error
     */
    public function createItem($car, $data)
    {
        $canAdd = $car->hasSufficientQuantity($data['quantity']);

        if (! $canAdd) {
            return false;
        }

        return CartItem::create([
            'quantity' => $data['quantity'],
            'cart_id' => $this->getCart()->id,
            'name' => $car->title,
            'price' => $car->get('price'),
            'total' => $car->get('price') * $data['quantity'],
            'product_id' => $car->id
        ]);
    }

    /**
     * Update the cartItem on cart checkout page and if already added item is added again.
     *
     * @param $id product_id of cartItem instance
     * @param $data new requested quantities by customer
     * @param $itemId is id from cartItem instance
     *
     * @return bool
     */
    public function updateItem($car, $data, $itemId)
    {
        $item = CartItem::find($itemId);

        if (!$car->hasSufficientQuantity($data['quantity'])) {
            return false;
        }

        $quantity = $data['quantity'];

        if ($quantity) {
            $result = $item->update([
                'quantity' => $quantity,
                'total' => $car->get('price') * $quantity
            ]);
        } else {
            $result = $item->delete();
        }

        $this->collectTotals();

        return $result ? $item : false;
    }

    /**
     * Remove the item from the cart.
     *
     * @return response
     */
    public function removeItem($cartItem)
    {
        if ($cart = $this->getCart()) {
            $cartItem->delete();
            if ($cart->items()->get()->count() == 0) {
                $cart->delete();
                if (session()->has('cart')) {
                    session()->forget('cart');
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Save cart.
     *
     * @return mixed
     */
    public function putCart($cart)
    {
        if (!auth()->check()) {
            session()->put('cart', $cart);
        }
    }

    /**
     * Returns cart.
     *
     * @return mixed
     */
    public function getCart()
    {
        if (auth()->check()) {
            $cart = Cart::where([
                'customer_id' => auth()->user()->id
            ])->first();
        } elseif (session()->has('cart')) {
            $cart = Cart::find(session()->get('cart')->id);
        }

        return $cart ?? null;
    }

    /**
     * Updates cart totals.
     */
    public function collectTotals()
    {
        if (!$cart = $this->getCart()) {
            return false;
        }

        $cart->total = 0;
        foreach ($cart->items()->get() as $item) {
            $cart->total = (float) $cart->total + $item->total;
        }

        $quantities = 0;
        foreach ($cart->items as $item) {
            $quantities = $quantities + $item->quantity;
        }

        $cart->items_count = $cart->items->count();
        $cart->items_qty = $quantities;
        $cart->save();

        if (!$cart->total && !$cart->items_qty) {
            $cart->delete();
        }
    }

    public function itemCount()
    {
        if (!$this->getCart()) {
            return 0;
        }

        return $this->getCart()->items()->get()->sum('quantity');
    }

    public function total()
    {
        return (float) optional($this->getCart())->total;
    }

    public function items()
    {
        if (! $this->getCart()) {
            return collect([]);
        }

        return $this->getCart()->items()->get() ?? collect([]);
    }


    public function mergeCart()
    {
        if (session()->has('cart')) {
            $cart = Cart::where(['customer_id' => auth()->user()->id])->first();
            $guestCart = session()->get('cart');

            //when the logged in customer is not having any of the cart instance previously and are active.
            if (! $cart) {
                $guestCart->update([
                    'customer_id' => auth()->user()->id,
                    'is_guest' => 0
                ]);

                session()->forget('cart');

                return true;
            }

            $cartItems = $cart->items;
            $guestCartItems = $guestCart->items;

            foreach ($guestCartItems as $key => $guestCartItem) {
                foreach ($cartItems as $cartItem) {
                    if ($cartItem->product_id == $guestCartItem->product_id) {
                        $prevQty = $cartItem->quantity;
                        $newQty = $guestCartItem->quantity;

                        $product = Car::find($cartItem->product_id);

                        if (! $product->hasSufficientQuantity($prevQty + $newQty)) {
                            $guestCartItem->delete();
                            continue;
                        }
                        $data['quantity'] = $newQty + $prevQty;
                        $this->updateItem($product, $data, $cartItem->id);
                        $guestCartItem->delete();
                    }
                }
            }

            foreach ($guestCartItems as $guestCartItem) {
                $guestCartItem->update(['cart_id' => $cart->id]);
            }

            $guestCart->delete();

            session()->forget('cart');

            $this->collectTotals();

            return true;
        } else {
            return true;
        }
    }

    public function destroy()
    {
        $this->getCart()->items()->delete();

        return $this->getCart()->delete();
    }
}
