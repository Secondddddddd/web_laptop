<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipperOrderController extends Controller
{
    // Hiển thị danh sách đơn hàng có thể nhận
    public function index()
    {
        $orders = Order::where('order_status', 'processing')
            ->orWhere('shipper_id', Auth::id())
            ->with('user')
            ->get();

        return view('shipper.index', compact('orders'));
    }

    public function getOrdersByStatus(Request $request)
    {
        $status = $request->query('status', 'processing');
        $shipperId = Auth::id();

        // Trường hợp 'processing' -> Đơn hàng đang chờ shipper nhận (shipper_id = null)
        if ($status === 'processing') {
            $orders = Order::whereNull('shipper_id')
                ->where('order_status', 'processing')
                ->with('user')
                ->get();
        }
        // Trường hợp 'shipped' -> Đơn hàng shipper đã nhận nhưng chưa giao
        elseif ($status === 'shipped') {
            $orders = Order::where('shipper_id', $shipperId)
                ->where('order_status', 'shipped')
                ->with('user')
                ->get();
        }
        // Trường hợp 'delivered' -> Đơn hàng shipper đã giao
        elseif ($status === 'delivered') {
            $orders = Order::where('shipper_id', $shipperId)
                ->where('order_status', 'delivered')
                ->with('user')
                ->get();
        }
        // Nếu không hợp lệ, trả về mảng rỗng
        else {
            return response()->json([]);
        }

        // Thêm thông tin khách hàng vào kết quả trả về
        $orders->each(function ($order) {
            $order->user_full_name = $order->user ? $order->user->full_name : 'Không rõ';
        });

        return response()->json($orders);
    }



    // Nhận đơn hàng
    public function accept(Order $order)
    {
        // Kiểm tra nếu shipper hiện có đơn hàng chưa hoàn thành
        $activeOrder = Order::where('shipper_id', Auth::id())
            ->whereNotIn('order_status', ['delivered', 'cancelled'])
            ->first();

        if ($activeOrder) {
            return back()->with('error', 'Bạn hiện đang có đơn hàng chưa hoàn thành. Vui lòng hoàn thành đơn hàng trước khi nhận đơn mới.');
        }

        // Kiểm tra trạng thái đơn hàng có hợp lệ để shipper nhận hay không
        if ($order->order_status !== 'processing') {
            return back()->with('error', 'Đơn hàng này không thể nhận.');
        }

        // Cập nhật đơn hàng: gán shipper và chuyển trạng thái đơn hàng thành "shipped"
        $order->update([
            'shipper_id' => Auth::id(),
            'order_status' => 'shipped'
        ]);

        return back()->with('success', 'Bạn đã nhận đơn hàng thành công.');
    }

    // Hiển thị đơn hàng đang giao
    public function currentOrder()
    {
        $order = Order::where('shipper_id', Auth::id())
            ->where('order_status', 'shipped')
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('shipper.orders')->with('error', 'Không có đơn hàng nào đang giao.');
        }

        return view('shipper.order_detail', compact('order'));
    }

    // Xử lý xác nhận giao hàng qua OTP
    public function confirmDelivery(Request $request)
    {
        $request->validate([
            'otp_code' => 'required',
        ]);

        $order = Order::where('shipper_id', Auth::id())
            ->where('order_status', 'shipped')
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('shipper.orders.current')->with('error', 'Không có đơn hàng nào để xác nhận.');
        }

        if ($order->otp_code == $request->otp_code) {
            $order->order_status = 'delivered';
            $order->save();
            return redirect()->route('shipper.orders')->with('success', 'Xác nhận giao hàng thành công.');
        }

        return redirect()->route('shipper.orders.current')->with('error', 'Mã OTP không chính xác.');
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        // Kiểm tra nếu user không phải shipper hoặc không phải shipper được chỉ định
        if ($user->role !== 'shipper') {
            return response()->json(['error' => 'Bạn không có quyền xem đơn hàng này.'], 403);
        }

        // Load thông tin khách hàng và sản phẩm trong đơn hàng
        $order->load(['user', 'orderDetails.product']);

        // Trả về dữ liệu
        return response()->json([
            'order_id' => $order->order_id,
            'user_full_name' => $order->user->full_name ?? 'Không rõ',
            'total_price' => $order->total_price,
            'order_status' => $order->order_status,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'orderDetails' => $order->orderDetails->map(function ($detail) {
                return [
                    'product_id' => $detail->product->product_id,
                    'product_name' => $detail->product->name,
                    'image_url' => $detail->product->image_url ?? 'default.jpg',
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                ];
            })
        ]);
    }


}

