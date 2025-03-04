<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    // Đặt khóa chính là shipping_id thay vì id
    protected $primaryKey = 'shipping_id';

    // Tắt timestamps vì bảng không có created_at, updated_at
    public $timestamps = false;

    // Các cột có thể gán giá trị hàng loạt (Mass Assignment)
    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'estimated_delivery',
        'delivery_status',
    ];

    // Kiểu dữ liệu của các cột
    protected $casts = [
        'estimated_delivery' => 'date',
    ];

    /**
     * Định nghĩa quan hệ: Một shipping thuộc về một đơn hàng.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
