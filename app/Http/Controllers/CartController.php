<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CoinPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();
        $total = Cart::getTotalAmount(Auth::id());

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'coin_package_id' => 'required|exists:coin_packages,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $package = CoinPackage::findOrFail($request->coin_package_id);

        if (!$package->is_active) {
            return back()->with('error', 'This package is not available');
        }

        Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'coin_package_id' => $request->coin_package_id,
            ],
            [
                'quantity' => $request->quantity ?? 1,
            ]
        );

        return back()->with('success', 'Package added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'subtotal' => $cartItem->subtotal,
            'total' => Cart::getTotalAmount(Auth::id()),
        ]);
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'total' => Cart::getTotalAmount(Auth::id()),
        ]);
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart')->with('success', 'Cart cleared!');
    }
}
