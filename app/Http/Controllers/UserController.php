<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    function showUserInfo() {
        // Lấy danh sách tỉnh/thành phố
        $provinces = Province::all(); // Lấy tất cả các tỉnh/thành phố

        // Lấy danh sách địa chỉ của user hiện tại
        $addresses = UserAddress::with(['province', 'district', 'ward'])
            ->where('user_id', auth()->id())
            ->get();

        // Truyền dữ liệu sang view
        return view('user.user_info', compact('provinces', 'addresses'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu từ form
        $validated = $request->validate([
            'province_code' => 'required|string|exists:provinces,code',
            'district_code' => 'required|string|exists:districts,code',
            'ward_code'     => 'required|string|exists:wards,code',
            'address_detail'=> 'required|string|max:255',
            'is_default' => 'nullable|accepted',
        ]);

        // Nếu người dùng chọn đặt làm mặc định, reset các địa chỉ khác về false
        if ($request->has('is_default')) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        // Lưu địa chỉ mới
        UserAddress::create([
            'user_id'       => Auth::id(),
            'province_code' => $validated['province_code'],
            'district_code' => $validated['district_code'],
            'ward_code'     => $validated['ward_code'],
            'address_detail'=> $validated['address_detail'],
            'is_default'    => $request->has('is_default') ? 1 : 0,
        ]);

        // Chuyển hướng về trang thông tin cá nhân hoặc địa chỉ
        return redirect()->route('user.info')->with('success', 'Thêm địa chỉ thành công!');
    }

}
