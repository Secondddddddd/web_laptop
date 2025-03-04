<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdminController extends Controller
{
    //
    function index()
    {
        return view('admin.admin');
    }

    function product_list()
    {
        $products = Product::with(['category', 'supplier'])->paginate(10); // Hiển thị 10 sản phẩm mỗi trang
        return view('admin.product.product_list', compact('products'));
    }

    function category_list()
    {
        $categories = Category::paginate(10); // Lấy toàn bộ danh mục từ database
        return view('admin.category.category_list', ['categories'=>$categories]);
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.product.create_product', ['categories'=>$categories, 'suppliers'=>$suppliers]);
    }

    public function store(Request $request)
    {
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
            return redirect()->route('admin_product_add')->with('success', 'Sản phẩm đã được thêm thành công!');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thêm sản phẩm thất bại! Vui lòng thử lại.');
        }


    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('admin.product.edit', ['product'=>$product, 'categories'=>$categories, 'suppliers'=>$suppliers]);
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



            return redirect()->route('admin_product_edit', ['product_id' => $id])->with('success', 'Sản phẩm đã được cập nhật thành công!');
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

            return redirect()->route('admin_product_list')->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Xóa sản phẩm thất bại:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Xóa sản phẩm thất bại! Vui lòng thử lại.');
        }
    }

    // Hiển thị form thêm thể loại
    public function createCategory()
    {
        return view('admin.category.category_add');
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

            return redirect()->route('admin_category_add')->with('success', 'Thể loại đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thêm thể loại thất bại! Vui lòng thử lại.');
        }
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.category_edit', compact('category'));
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

            return redirect()->route('admin_category_edit', ['id' => $id])->with('success', 'Thể loại đã được cập nhật thành công!');
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

            return redirect()->route('admin_category_list')->with('success', 'Danh mục đã được xóa và các sản phẩm đã được chuyển sang "Không danh mục"!');
        } catch (\Exception $e) {
            Log::error('Xóa danh mục thất bại:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Xóa danh mục thất bại! Vui lòng thử lại.');
        }
    }

    public function userList()
    {
        $users = User::select('user_id', 'full_name', 'email', 'phone', 'role', 'avatar', 'created_at')->paginate(10);
        return view('admin.user.user_list', compact('users'));
    }

    public function userDetail($id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        return view('admin.user.user_detail', compact('user'));
    }

    public function showAddUserForm()
    {
        // Debug
        return view('admin.user.user_add');
    }


    public function storeUser(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'role' => 'required|in:customer,admin,staff',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Xử lý avatar
        if ($request->hasFile('avatar')) {
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('avatar'), $avatarName);
        } else {
            $avatarName = 'avatar_default.jpg';
        }

        // Tạo user
        User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'avatar' => $avatarName
        ]);

        return redirect()->route('admin_user_add')->with('success', 'Thêm người dùng thành công!');
    }

    public function disableUser($id)
    {
        try {
            $user = User::where('user_id', $id)->firstOrFail();
            $user->update(['status' => 'inactive']);

            return redirect()->route('admin_user_list')->with('success', 'Tài khoản đã bị vô hiệu hóa!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi vô hiệu hóa tài khoản', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Vô hiệu hóa tài khoản thất bại! Vui lòng thử lại.');
        }
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.user_edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',user_id',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'role' => 'required|in:customer,admin,staff',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'full_name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'role.required' => 'Vui lòng chọn vai trò.',
        ]);

        try {
            $user = User::findOrFail($id);

            // Cập nhật ảnh đại diện nếu có tải lên
            if ($request->hasFile('avatar')) {
                $avatarName = time() . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('avatar'), $avatarName);
                $user->avatar = $avatarName;
            }

            // Cập nhật thông tin người dùng
            $user->update([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role,
            ]);

            return redirect()->route('admin_user_edit',['id' => $id])->with('success', 'Cập nhật thông tin người dùng thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật thông tin user', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Cập nhật thất bại! Vui lòng thử lại.');
        }
    }

}
