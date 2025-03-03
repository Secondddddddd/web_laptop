<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers'; // Tên bảng trong database

    protected $primaryKey = 'supplier_id'; // Khóa chính

    public $timestamps = false; // Nếu bảng không có cột created_at và updated_at

    protected $fillable = [
        'name',
        'contact_name',
        'phone',
        'email',
        'address'
    ]; // Cho phép cập nhật các cột này

    // Nếu có quan hệ với bảng sản phẩm (nếu có)
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id', 'supplier_id');
    }
}
