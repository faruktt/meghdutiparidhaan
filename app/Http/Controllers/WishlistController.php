<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the wishlist page.
     */
    public function index()
    {
        $wishlistIds = $this->getWishlistIds();
        $products = Product::with(['category', 'variants'])
            ->whereIn('id', $wishlistIds)
            ->get();

        return view('frontend.wishlist', compact('products'));
    }

    /**
     * Toggle product in/out of wishlist.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = (int) $request->product_id;
        $product = Product::findOrFail($productId);
        $user = Auth::guard('web')->user();

        if ($user) {
            $existing = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                $existing->delete();
                $status = 'removed';
                $message = 'Removed from your wishlist';
            } else {
                Wishlist::create([
                    'user_id'    => $user->id,
                    'product_id' => $productId,
                ]);
                $status = 'added';
                $message = 'Added to your wishlist!';
            }

            $allIds = Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
            session()->put('wishlist', $allIds);
            $count = count($allIds);
        } else {
            $wishlist = session()->get('wishlist', []);
            if (in_array($productId, $wishlist)) {
                $wishlist = array_values(array_diff($wishlist, [$productId]));
                $status = 'removed';
                $message = 'Removed from your wishlist';
            } else {
                $wishlist[] = $productId;
                $status = 'added';
                $message = 'Added to your wishlist!';
            }
            session()->put('wishlist', $wishlist);
            $count = count($wishlist);
        }

        return response()->json([
            'success'      => true,
            'status'       => $status,
            'message'      => $message,
            'count'        => $count,
            'product_id'   => $productId,
            'product_name' => $product->name,
        ]);
    }

    /**
     * Remove an item from wishlist.
     */
    public function remove(Request $request, $productId)
    {
        $productId = (int) $productId;
        $user = Auth::guard('web')->user();

        if ($user) {
            Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->delete();

            $allIds = Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
            session()->put('wishlist', $allIds);
            $count = count($allIds);
        } else {
            $wishlist = session()->get('wishlist', []);
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            session()->put('wishlist', $wishlist);
            $count = count($wishlist);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist.',
                'count'   => $count,
            ]);
        }

        return back()->with('success', 'Product removed from your wishlist.');
    }

    /**
     * Clear all wishlist items.
     */
    public function clear(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            Wishlist::where('user_id', $user->id)->delete();
        }

        session()->forget('wishlist');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared.',
                'count'   => 0,
            ]);
        }

        return back()->with('success', 'Wishlist cleared.');
    }

    /**
     * Helper to get current user / session wishlist IDs.
     */
    protected function getWishlistIds(): array
    {
        $user = Auth::guard('web')->user();
        if ($user) {
            $ids = Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
            session()->put('wishlist', $ids);
            return $ids;
        }

        return session()->get('wishlist', []);
    }
}

