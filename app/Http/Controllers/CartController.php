<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;

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

        // Lấy danh sách product_id từ cart
        $productIds = array_keys($cart);

        // Lấy danh sách sản phẩm và số lượng tồn kho
        $products = Product::whereIn('product_id', $productIds)->get()->keyBy('product_id');

        // Thêm số lượng tồn kho vào giỏ hàng
        foreach ($cart as $id => &$item) {
            if (isset($products[$id])) {
                $item['stock_quantity'] = $products[$id]->stock_quantity;
            } else {
                $item['stock_quantity'] = 0; // Nếu sản phẩm không tồn tại, giả sử hết hàng
            }
        }

        return view('cart.cart', compact('cart'));
    }


    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Sản phẩm đã bị xóa khỏi giỏ hàng.');
    }

    public function checkoutCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $selectedProducts = collect($request->input('products', []))->mapWithKeys(function ($id) use ($cart) {
            return isset($cart[$id]) ? [$id => $cart[$id]] : [];
        });

        $user = auth()->user();

        $phone = $user->phone;

        $addresses = $user->addresses ?? [];
        $addressDefault = $user->defaultAddress() ?? '';
        return view('cart.checkoutCart', compact('selectedProducts', 'addresses', 'phone', 'addressDefault'));
    }

    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
        }

        $quantity = $request->input('quantity');

        if ($quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Số lượng không hợp lệ.']);
        }

        $cart[$id]['quantity'] = $quantity;

        session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'Giỏ hàng đã được cập nhật.']);
    }

}
