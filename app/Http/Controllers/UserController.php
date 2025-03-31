<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    public function showUserInfo() {
        // Lấy danh sách tỉnh/thành phố
        $provinces = Province::all();

        // Lấy danh sách địa chỉ của user hiện tại
        $addresses = UserAddress::with(['province', 'district', 'ward'])
            ->where('user_id', auth()->id())
            ->get();

        // Lấy activeComponent từ query string, mặc định là 'user-info'
        $activeComponent = request('active', 'user-info');

        // Truyền dữ liệu sang view
        return view('user.user_info', compact('provinces', 'addresses', 'activeComponent'));
    }


    public function store(Request $request)
    {
        // Ép kiểu giá trị của is_default trước khi validate
        $request->merge([
            'is_default' => $request->boolean('is_default'),
        ]);

        // Validate dữ liệu từ form
        $validated = $request->validate([
            'province_code' => 'required|string|exists:provinces,code',
            'district_code' => 'required|string|exists:districts,code',
            'ward_code'     => 'required|string|exists:wards,code',
            'address_detail'=> 'required|string|max:255',
            'is_default'    => 'nullable|boolean',
        ]);

        // Nếu người dùng chọn đặt làm mặc định, reset các địa chỉ khác về false
        if ($validated['is_default']) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        // Lưu địa chỉ mới
        UserAddress::create([
            'user_id'       => Auth::id(),
            'province_code' => $validated['province_code'],
            'district_code' => $validated['district_code'],
            'ward_code'     => $validated['ward_code'],
            'address_detail'=> $validated['address_detail'],
            'is_default'    => $validated['is_default'],
        ]);

        // Chuyển hướng về trang thông tin cá nhân hoặc địa chỉ
        return redirect()->route('user.info')->with('success', 'Thêm địa chỉ thành công!');
    }

    public function updateInfo(Request $request)
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Validate dữ liệu
        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Giới hạn 2MB
        ], [
            'avatar.max' => 'Ảnh tải lên không được vượt quá 2MB.', // Tùy chỉnh thông báo lỗi
        ]);

        // Cập nhật thông tin nếu có dữ liệu mới
        if ($request->filled('full_name')) {
            $user->full_name = $request->full_name;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');

            // Kiểm tra và xóa avatar cũ nếu không phải ảnh mặc định
            if ($user->avatar && $user->avatar !== 'avatar_default.jpg') {
                $oldAvatarPath = public_path('avatar/' . $user->avatar);
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }

            // Lưu avatar mới vào thư mục public/avatar
            $avatarName = time() . '.' . $avatarFile->extension();
            $avatarFile->move(public_path('avatar'), $avatarName);

            // Cập nhật tên avatar trong database
            $user->avatar = $avatarName;
        }

        // Lưu thay đổi vào database
        $user->save();

        // Chuyển hướng về trang thông tin cá nhân với thông báo thành công
        return redirect()->route('user.info')->with('success', 'Cập nhật thông tin thành công!');
    }


}
