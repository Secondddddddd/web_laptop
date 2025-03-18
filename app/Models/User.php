<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tên bảng
    protected $table = 'users';

    // Khóa chính
    protected $primaryKey = 'user_id';

    // Không sử dụng timestamps mặc định (created_at, updated_at)
    public $timestamps = false;

    // Các cột có thể gán giá trị hàng loạt (Mass Assignment)
    protected $fillable = [
        'full_name',
        'avatar',
        'email',
        'password',
        'phone',
        'status',
        'role'
    ];

    // Ẩn trường password khi trả về JSON
    protected $hidden = [
        'password',
    ];

    // Kiểu dữ liệu cần cast
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Một người dùng có thể có nhiều địa chỉ giao hàng.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'user_id');
    }

    /**
     * Một người dùng (khách hàng) có thể có nhiều đơn hàng.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    /**
     * Một shipper (nhân viên giao hàng) có thể đảm nhận nhiều đơn hàng.
     */
    public function shippingOrders()
    {
        return $this->hasMany(Order::class, 'shipper_id', 'user_id');
    }

    /**
     * Một người dùng có thể có nhiều đánh giá sản phẩm.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }
}
