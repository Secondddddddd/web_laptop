<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews'; // Tên bảng trong database
    protected $primaryKey = 'review_id'; // Khóa chính

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'decimal:1', // Tự động cast rating thành số thập phân 1 chữ số
    ];

    // Quan hệ: Một review thuộc về một user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Quan hệ: Một review thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
