<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (!auth()->check()) {
            return back()->with('error', 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $product = Product::findOrFail($productId);
            $cart[$productId] = [
                'name' => $product->name,
                'image' => $product->image_url,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }


    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.cart', compact('cart'));
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }


}
