<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
        }

        // Kiểm tra trạng thái tài khoản trước khi xác thực mật khẩu
        if ($user->status === 'pending') {
            return back()->withErrors(['email' => 'Tài khoản của bạn chưa được kích hoạt. Vui lòng chờ xác nhận từ admin.']);
        }

        if ($user->status === 'inactive') {
            return back()->withErrors(['email' => 'Tài khoản của bạn không tồn tại.']);
        }

        // Xác thực thông tin đăng nhập
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin_dashboard')->with('success', 'Đăng nhập thành công!');
                case 'customer':
                    return redirect(session('url.intended', '/'))->with('success', 'Chào mừng bạn trở lại!');
                case 'staff':
                    return redirect()->route('admin_product_list')->with('success', 'Chào mừng nhân viên!');
                case 'shipper':
                    return redirect()->route('home')->with('success', 'Chào mừng shipper!');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Tài khoản không tồn tại!']);
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

    public function changePassword(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại có chính xác không
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới (mã hóa)
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }

    function showRegisterShipperForm()
    {
        return view('auth.register_shipper');
    }

    function registerShipper(Request $request){
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
            'role' => 'shipper',
            'avatar' => $avatarName,
            'status' => 'pending'
        ]);

        Auth::login($user);

        return back()->with('warning', 'Tài khoản của bạn đang chờ duyệt. Vui lòng chờ admin hoặc nhân viên xác nhận. Hệ thống sẽ gửi email thông báo khi tài khoản của bạn được kích hoạt.');
    }

}

