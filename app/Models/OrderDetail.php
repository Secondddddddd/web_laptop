<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Đặt khóa chính là detail_id thay vì id
    protected $primaryKey = 'detail_id';

    // Tắt timestamps vì bảng không có created_at, updated_at
    public $timestamps = false;

    // Các cột có thể gán giá trị hàng loạt (Mass Assignment)
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    // Kiểu dữ liệu của các cột
    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Định nghĩa quan hệ: Một chi tiết đơn hàng thuộc về một đơn hàng.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Định nghĩa quan hệ: Một chi tiết đơn hàng thuộc về một sản phẩm.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
