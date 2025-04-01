<?php

namespace App\Http\Controllers;

use App\Mail\ShipperActivatedMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StaffController extends Controller
{
    function index(){
        $user = Auth::user();
        $userCount = User::count();

        // Tính tổng số lượng sản phẩm thay vì chỉ đếm số loại sản phẩm
        $productCount = Product::sum('stock_quantity');

        $supplierCount = Supplier::count();
        $completedOrders = Order::where('order_status', 'delivered')->count();

        // Lấy doanh thu theo ngày trong tháng
        $monthlyRevenue = Order::where('order_status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('d/m');
            })
            ->map(function ($orders) {
                return [
                    'orders' => $orders->count(),
                    'revenue' => $orders->sum('total_price')
                ];
            });

        return view('staff.staff', compact(
            'userCount',
            'productCount', // Giờ đã là tổng số lượng sản phẩm
            'supplierCount',
            'completedOrders',
            'monthlyRevenue',
            'user'
        ));
    }

    function product_list()
    {
        return view('staff.product.product_list');
    }

    function category_list()
    {
        return view('staff.category.category_list');
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('staff.product.create_product', ['categories'=>$categories, 'suppliers'=>$suppliers]);
    }

    public function store(Request $request)
    {
//        dd($request);

        try {
            // Validate dữ liệu với thông báo tùy chỉnh
            $request->validate([
                'name' => 'required|unique:products,name|max:255',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|integer',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'name.required' => 'Vui lòng nhập tên sản phẩm.',
                'name.unique' => 'Sản phẩm này đã tồn tại trong hệ thống.',
                'price.required' => 'Vui lòng nhập giá sản phẩm.',
                'price.numeric' => 'Giá sản phẩm phải là một số.',
                'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
                'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
                'image.image' => 'Tệp tải lên phải là hình ảnh.',
                'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
                'image.max' => 'Dung lượng ảnh tối đa là 2MB.',
            ]);

            // Xử lý upload hình ảnh
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('img'), $imageName); // Lưu vào thư mục public/img/
                $imagePath = $imageName;
            }

            // Lưu sản phẩm mới
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'image_url' => $imagePath,
            ]);

            // Trả về trang danh sách với thông báo thành công
            return redirect()->route('staff_product_add')->with('success', 'Sản phẩm đã được thêm thành công!');
        }catch (\Exception $e) {
            logger()->error($e); // Ghi log lỗi
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi: '.$e->getMessage()); // Hiển thị chi tiết lỗi
        }


    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('staff.product.edit', ['product'=>$product, 'categories'=>$categories, 'suppliers'=>$suppliers]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Dữ liệu request:', $request->all());

        try {
            $product = Product::findOrFail($id);

            // Validate dữ liệu với thông báo tùy chỉnh
            $request->validate([
                'name' => 'required|max:255|unique:products,name,' . $id . ',product_id',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|integer',
                'category_id' => 'required|exists:categories,category_id',
                'supplier_id' => 'required|exists:suppliers,supplier_id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'category_id.required' => 'Vui lòng chọn danh mục.',
                'category_id.exists' => 'Danh mục không hợp lệ.',
                'supplier_id.required' => 'Vui lòng chọn nhà cung cấp.',
                'supplier_id.exists' => 'Nhà cung cấp không hợp lệ.',
            ]);


            // Xử lý ảnh mới nếu có upload
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($product->image_url) {
                    $oldImagePath = public_path('img/' . $product->image_url);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Tạo tên ảnh mới
                $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->extension();
                // Lưu ảnh vào thư mục public/img
                $request->file('image')->move(public_path('img'), $imageName);
                $product->image_url = $imageName;
            }

            // Cập nhật sản phẩm
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'category_id' => $request->category_id ?? $product->category_id,
                'supplier_id' => $request->supplier_id ?? $product->supplier_id,
                'image_url' => $product->image_url,
            ]);



            return redirect()->route('staff_product_edit', ['product_id' => $id])->with('success', 'Sản phẩm đã được cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Update failed:', ['error' => $e->getMessage()]); // Log lỗi
            return redirect()->back()->with('error', 'Cập nhật sản phẩm thất bại! Vui lòng thử lại.');
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Xóa ảnh sản phẩm nếu có
            if ($product->image_url) {
                $imagePath = public_path('img/' . $product->image_url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Xóa sản phẩm
            $product->delete();

            return redirect()->route('staff_product_list')->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Xóa sản phẩm thất bại:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Xóa sản phẩm thất bại! Vui lòng thử lại.');
        }
    }

    // Hiển thị form thêm thể loại
    public function createCategory()
    {
        return view('staff.category.category_add');
    }

    // Xử lý thêm thể loại
    public function storeCategory(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
            ]);

            Category::create([
                'name' => $request->name,
            ]);

            return redirect()->route('staff_category_add')->with('success', 'Thể loại đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thêm thể loại thất bại! Vui lòng thử lại.');
        }
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('staff.category.category_edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',category_id',
        ], [
            'name.required' => 'Vui lòng nhập tên thể loại.',
            'name.unique' => 'Tên thể loại đã tồn tại.',
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->update([
                'name' => $request->name,
            ]);

            return redirect()->route('staff_category_edit', ['id' => $id])->with('success', 'Thể loại đã được cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Fail', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Cập nhật thể loại thất bại! Vui lòng thử lại.');
        }
    }

    public function destroyCategory($id)
    {
        try {
            // Cập nhật tất cả sản phẩm thuộc danh mục này thành NULL
            Product::where('category_id', $id)->update(['category_id' => null]);

            // Xóa danh mục
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()->route('staff_category_list')->with('success', 'Danh mục đã được xóa và các sản phẩm đã được chuyển sang "Không danh mục"!');
        } catch (\Exception $e) {
            Log::error('Xóa danh mục thất bại:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Xóa danh mục thất bại! Vui lòng thử lại.');
        }
    }

    public function supplier_list()
    {
        return view('staff.supplier.supplier_list');
    }

    public function destroySupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('staff_supplier_list')->with('success', 'Xóa nhà cung cấp thành công');
    }

    public function editSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('staff.supplier.edit', compact('supplier'));
    }

    public function updateSupplier(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('staff_supplier_edit', $id)->with('success', 'Cập nhật thành công!');
    }

    public function createSupplier()
    {
        return view('staff.supplier.create_supplier');
    }

    public function storeSupplier(Request $request)
    {
        // Validate dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Tên nhà cung cấp là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        // Lưu nhà cung cấp mới
        Supplier::create($request->all());

        // Chuyển hướng kèm thông báo thành công
        return redirect()->route('staff_supplier_add')->with('success', 'Thêm nhà cung cấp thành công!');
    }

    function orderList()
    {
        return view('staff.order.order_list');
    }

    public function destroyOrder($id)
    {
        $order = Order::findOrFail($id); // Tìm đơn hàng
        $order->delete(); // Xóa đơn hàng

        return redirect()->route('admin_order_list')->with('success', 'Đã xóa đơn hàng.');
    }

    public function shipperList()
    {
        return view('staff.user.shipper_list');
    }

    public function acceptShipper($shipperId)
    {
        $shipper = User::findOrFail($shipperId);

        // Cập nhật trạng thái shipper thành active
        $shipper->status = 'active';
        $shipper->save();

        // Gửi email thông báo
        Mail::to($shipper->email)->send(new ShipperActivatedMail($shipper));

        return redirect()->back()->with('success', 'Shipper đã được kích hoạt và nhận thông báo.');
    }

    public function show($order_id)
    {
        $order = Order::with(['user', 'orderDetails.product'])->findOrFail($order_id);
        return view('staff.order.show', compact('order'));
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
}
