<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;

class ProductController extends Controller
{

    public function index(){
        $laptops = Product::where('category_id',1)->get();
        return view('home_page', compact('laptops'));
    }

public function laptopList(Request $request)
{
// Lấy ID của danh mục Laptop
    $laptopCategory = Category::where('name', 'Laptop')->first();

    if (!$laptopCategory) {
    return view('products.laptops', ['products' => collect()]);
    }

    // Lấy danh sách Laptop và lọc theo từ khóa tìm kiếm nếu có
    $query = Product::where('category_id', $laptopCategory->category_id);

    if ($request->has('search')) {
    $query->where('name', 'LIKE', '%' . $request->search . '%');
    }

    $products = $query->get(); // Phân trang

    return view('products.laptops', compact('products'));
}

    public function accessories(Request $request)
    {
        $search = $request->input('search');

        $products = Product::whereHas('category', function ($query) {
            $query->where('name', '!=', 'Laptop'); // Lọc tất cả category KHÁC "Laptop"
        })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%");
            })
            ->get();

        return view('products.accessories', compact('products'));
    }

    public function showProductDetail($product_id, $product_slug)
    {
        // Lấy sản phẩm theo ID và kèm theo reviews + user
        $product = Product::with('reviews.user')->findOrFail($product_id);

        // Tạo slug chính xác từ tên sản phẩm
        $correctSlug = Str::slug($product->name);

        // Nếu slug không khớp, điều hướng đến URL đúng
        if ($product_slug !== $correctSlug) {
            return redirect()->route('product.detail', [
                'product_id' => $product->product_id,
                'product_slug' => $correctSlug
            ], 301); // 301: Permanent Redirect
        }

        // Hiển thị trang chi tiết sản phẩm
        return view('products.product_detail', compact('product'));
    }


}
