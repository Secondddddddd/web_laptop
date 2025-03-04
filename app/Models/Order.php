<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Đặt khóa chính là order_id thay vì id
    protected $primaryKey = 'order_id';

    // Tắt timestamps vì bảng không có updated_at
    public $timestamps = false;

    // Các cột có thể gán giá trị hàng loạt (Mass Assignment)
    protected $fillable = [
        'user_id',
        'payment_method',
        'total_price',
        'order_status',
        'created_at',
    ];

    // Kiểu dữ liệu của các cột
    protected $casts = [
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Định nghĩa quan hệ: Một đơn hàng thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
