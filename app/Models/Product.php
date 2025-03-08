<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Bảng trong database
    protected $primaryKey = 'product_id'; // Khóa chính

    // Các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'name', 'description', 'price', 'stock_quantity',
        'category_id', 'supplier_id', 'image_url'
    ];

    // Quan hệ với bảng categories
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ với bảng suppliers
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Quan hệ 1-n: Một sản phẩm có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }
}
