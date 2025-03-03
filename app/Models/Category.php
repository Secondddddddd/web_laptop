<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Tên bảng

    protected $primaryKey = 'category_id'; // Khóa chính

    public $timestamps = false; // Nếu bảng không có cột created_at và updated_at

    protected $fillable = ['name']; // Cho phép cập nhật cột name

    // Nếu có quan hệ với sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
