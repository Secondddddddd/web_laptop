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
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.'], 403);
        }

        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $quantity = max(1, (int) ($request->quantity ?? 1));

        $product = Product::findOrFail($productId);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'image' => $product->image_url,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Sản phẩm đã thêm vào giỏ hàng!', 'totalQuantity' => array_sum(array_column($cart, 'quantity'))]);
        }

        return back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }




    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.cart', compact('cart'));
    }

    public function removeFromCart($id, Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.', 'totalQuantity' => array_sum(array_column($cart, 'quantity'))]);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }



}
