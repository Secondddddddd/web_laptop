<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    function buyNow(Request $request, $product_id){
        $product = Product::findOrFail($product_id);
        $quantity = $request->input('quantity', 1);
        $addresses = UserAddress::with(['province', 'district', 'ward'])
            ->where('user_id', auth()->id())
            ->get();

        return view('user.checkout', [
            'product' => $product,
            'quantity' => $quantity,
            'addresses' => $addresses,
        ]);
    }

    public function checkoutSubmitBuyNow(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:cod,banking',
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Lấy địa chỉ đầy đủ
        $address = UserAddress::with(['province', 'district', 'ward'])->findOrFail($request->address_id);
        $fullAddress = $address->address_detail . ', ' .
            ($address->ward->full_name ?? '') . ', ' .
            ($address->district->full_name ?? '') . ', ' .
            ($address->province->full_name ?? '');

        // Tạo mã OTP ngẫu nhiên (6 số)
        $otp_code = mt_rand(100000, 999999);

        // Lấy sản phẩm từ database
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $unit_price = $product->price;
        $subtotal = $unit_price * $quantity;

        // Tạo đơn hàng mới
        $order = Order::create([
            'user_id' => auth()->id(),
            'payment_method' => $request->payment_method,
            'address' => $fullAddress,
            'otp_code' => $otp_code,
            'order_status' => 'pending',
            'total_price' => $subtotal
        ]);

        // Thêm sản phẩm vào chi tiết đơn hàng
        OrderDetail::create([
            'order_id' => $order->order_id,
            'product_id' => $product->product_id,
            'quantity' => $quantity,
            'unit_price' => $unit_price
        ]);

        // Chuyển hướng với thông báo thành công
        return view('user.success');
    }

    public function ProcessCheckoutCart(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $cart = session()->get('cart', []);
        $address = UserAddress::with(['province', 'district', 'ward'])->findOrFail($request->input('address_id'));
        $fullAddress = $address->address_detail . ', ' .
            ($address->ward->full_name ?? '') . ', ' .
            ($address->district->full_name ?? '') . ', ' .
            ($address->province->full_name ?? '');

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $selectedProducts = collect($request->input('products', []));
        if ($selectedProducts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không có sản phẩm nào được chọn để thanh toán.');
        }

        DB::beginTransaction();

        try {
            // Tính tổng giá trị đơn hàng
            $totalPrice = $selectedProducts->sum(function ($product) use ($cart) {
                return $cart[$product['id']]['price'] * $cart[$product['id']]['quantity'];
            });

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => auth()->id(),
                'payment_method' => $request->input('payment_method'),
                'otp_code' => rand(100000, 999999), // Tạo mã OTP ngẫu nhiên
                'address' => $fullAddress,
                'total_price' => $totalPrice,
                'order_status' => 'pending'
            ]);

            // Thêm chi tiết đơn hàng và cập nhật số lượng sản phẩm
            foreach ($selectedProducts as $product) {
                $productData = $cart[$product['id']] ?? null;
                if ($productData) {
                    // Lấy sản phẩm từ database
                    $dbProduct = Product::find($product['id']);

                    // Kiểm tra nếu sản phẩm có tồn tại và đủ số lượng
                    if ($dbProduct && $dbProduct->stock_quantity >= $product['quantity']) {
                        OrderDetail::create([
                            'order_id' => $order->order_id,
                            'product_id' => $product['id'],
                            'quantity' => $product['quantity'],
                            'unit_price' => $productData['price']
                        ]);

                        // Trừ số lượng sản phẩm
                        $dbProduct->stock_quantity -= $product['quantity'];
                        $dbProduct->save();

                        // Xóa sản phẩm này khỏi giỏ hàng
                        unset($cart[$product['id']]);
                    } else {
                        DB::rollBack();
                        return redirect()->route('cart.index')->with('error', "Sản phẩm {$dbProduct->name} không đủ số lượng!");
                    }
                }
            }

            // Cập nhật lại giỏ hàng trong session
            session()->put('cart', $cart);

            DB::commit();

            return view('user.success');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi xử lý thanh toán: ' . $e->getMessage());
        }
    }


    public function show($order_id)
    {
        $order = Order::with(['user', 'orderDetails.product'])->findOrFail($order_id);
        return view('admin.order.show', compact('order'));
    }

    public function acceptOrder($order_id)
    {
        // Lấy đơn hàng theo ID
        $order = Order::where('order_id', $order_id)->where('order_status', 'pending')->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng hoặc đơn hàng không ở trạng thái chờ xác nhận.');
        }

        // Cập nhật trạng thái đơn hàng thành "processing"
        $order->update(['order_status' => 'processing']);

        return redirect()->back()->with('success', 'Đơn hàng đã được chấp nhận và đang xử lý.');
    }

    public function showDetail($id)
    {
        $order = Order::where('user_id', Auth::id())->with('orderDetails.product')->findOrFail($id);
        return view('user.order-detail', compact('order'));
    }

    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())->where('order_status', 'pending')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Cập nhật trạng thái đơn hàng thành "cancelled"
            $order->update(['order_status' => 'cancelled']);

            // Hoàn trả lại số lượng sản phẩm
            foreach ($order->orderDetails as $detail) {
                $detail->product->increment('stock_quantity', $detail->quantity);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đơn hàng đã bị hủy.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
