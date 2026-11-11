<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return view('frontend.cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;
        $variantKey = '0';
        $price = $product->current_price;
        $variantInfo = null;

        if ($request->filled('variant_id')) {
            $variant = ProductVariant::with(['color', 'size'])->find($request->variant_id);
            if ($variant) {
                $variantKey = $variant->id;
                $price += $variant->price_adjustment;
                $variantInfo = $variant->display_info;
            }
        }

        $cartKey = $product->id . '_' . $variantKey;
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id'   => $product->id,
                'variant_id'   => $variant ? $variant->id : null,
                'name'         => $product->name,
                'price'        => $price,
                'regular_price'=> $product->regular_price,
                'quantity'     => $request->quantity,
                'image'        => ($variant && $variant->image_url) ? $variant->image_url : $product->thumbnail_url,
                'variant_info' => $variantInfo,
                'slug'         => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $itemCount = array_sum(array_column($cart, 'quantity'));
            $total = 0;
            foreach ($cart as $it) {
                $total += $it['price'] * $it['quantity'];
            }
            return response()->json([
                'success'    => true,
                'message'    => 'Item added to bag successfully!',
                'cartCount'  => $itemCount,
                'cartTotal'  => number_format($total, 2),
                'cart'       => $cart,
                'added_item' => [
                    'id'       => $product->id,
                    'name'     => $product->name,
                    'price'    => (float) $price,
                    'quantity' => (int) $request->quantity,
                    'category' => $product->category->name ?? 'Boutique',
                ],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to bag successfully!');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $key = $request->key;
        $quantity = max(1, (int)$request->quantity);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $itemCount = array_sum(array_column($cart, 'quantity'));
            $total = 0;
            foreach ($cart as $it) {
                $total += $it['price'] * $it['quantity'];
            }
            return response()->json([
                'success'   => true,
                'cartCount' => $itemCount,
                'cartTotal' => number_format($total, 2),
                'itemTotal' => isset($cart[$key]) ? number_format($cart[$key]['price'] * $cart[$key]['quantity'], 2) : 0,
            ]);
        }

        return back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $itemCount = array_sum(array_column($cart, 'quantity'));
            $total = 0;
            foreach ($cart as $it) {
                $total += $it['price'] * $it['quantity'];
            }
            return response()->json([
                'success'   => true,
                'cartCount' => $itemCount,
                'cartTotal' => number_format($total, 2),
            ]);
        }

        return back()->with('success', 'Item removed from bag!');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared!');
    }
}
