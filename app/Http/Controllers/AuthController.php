<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin_dashboard')->with('success', 'Đăng nhập thành công!');
                case 'customer':
                    // Lấy URL từ session hoặc mặc định về trang chủ
                    return redirect(session('url.intended', '/'))->with('success', 'Chào mừng bạn trở lại!');
                case 'staff':
                    return redirect()->route('admin_product_list')->with('success', 'Chào mừng nhân viên!');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Tài khoản không có quyền truy cập']);
            }
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }




    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->back()->with('success', 'Bạn đã đăng xuất.');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Xử lý avatar
        if ($request->hasFile('avatar')) {
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('avatar'), $avatarName);
        } else {
            $avatarName = 'avatar_default.jpg';
        }

        // Tạo user
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'customer',
            'avatar' => $avatarName
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }

    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Xác thực email và chuyển đến trang đổi mật khẩu
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email này không tồn tại trong hệ thống.']);
        }

        // Chuyển đến trang đặt lại mật khẩu kèm theo email
        return redirect()->route('password.reset')->with('email', $request->email);
    }

    // Hiển thị form đặt lại mật khẩu mới
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    // Cập nhật mật khẩu mới
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không hợp lệ.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi thành công! Vui lòng đăng nhập.');
    }
}

