<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
use HasFactory, Notifiable;

protected $table = 'users';
protected $primaryKey = 'user_id';

protected $fillable = [
'full_name', 'avatar', 'email', 'password', 'phone', 'status', 'role'
];

protected $hidden = [
'password',
];

public function addresses()
{
return $this->hasMany(UserAddress::class, 'user_id', 'user_id');
}
}
