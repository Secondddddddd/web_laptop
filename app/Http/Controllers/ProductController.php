<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
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

}
