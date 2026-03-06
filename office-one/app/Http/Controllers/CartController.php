<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Show the shopping cart.
     */
    public function index()
    {
        $cartItems = Cart::with('product.photos')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum('subtotal');
        $vat = round($subtotal * 0.04, 2); // 4% VAT
        $total = $subtotal + $vat;

        return view('cart.index', compact('cartItems', 'subtotal', 'vat', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1|max:99',
        ]);

        $quantity = $request->input('quantity', 1);

        // Check if product is active
        if (!$product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        // Check if already in cart — if so, increase quantity
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
            ]);
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'quantity'   => $quantity,
            ]);
        }

        return back()->with('success', "{$product->name} has been added to your cart!");
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, Cart $cart)
    {
        // Ensure user owns this cart item
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared.');
    }

    /**
     * Checkout — convert cart items to completed orders.
     */
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        DB::transaction(function () use ($cartItems) {
            foreach ($cartItems as $item) {
                Order::create([
                    'user_id'         => Auth::id(),
                    'product_id'      => $item->product_id,
                    'quantity'        => $item->quantity,
                    'total_price'     => $item->quantity * $item->product->unit_price,
                    'status'          => 'completed',
                    'delivery_status' => 'processing',
                ]);
            }

            // Clear cart after checkout
            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully! Thank you for your purchase.');
    }

    /**
     * Show order history for the authenticated user.
     */
    public function orders()
    {
        $orders = Order::with('product.photos')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cart.orders', compact('orders'));
    }
}
