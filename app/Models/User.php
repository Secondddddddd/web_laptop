<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Đặt khóa chính là user_id thay vì id
    protected $primaryKey = 'user_id';

    // Tắt timestamps vì bảng chỉ có created_at, không có updated_at
    public $timestamps = false;

    // Các cột có thể gán giá trị hàng loạt (Mass Assignment)
    protected $fillable = [
        'full_name',
        'avatar',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'created_at',
        'status',
    ];

    // Ẩn các trường không muốn trả về khi query
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Kiểu dữ liệu của các cột
    protected $casts = [
        'password' => 'hashed', // Laravel 10 hỗ trợ hash password tự động
        'created_at' => 'datetime',
    ];
}
