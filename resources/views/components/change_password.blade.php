<div class="mt-5">
    <h2 class="text-2xl font-semibold mb-4 text-center">Đổi mật khẩu</h2>
    <form action="{{ $action }}" method="POST" class="space-y-4 ml-5">
        @csrf

        <!-- Mật khẩu cũ -->
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
            <input type="password" name="current_password" id="current_password" required
                   class="mt-1 input">
            @error('current_password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Mật khẩu mới -->
        <div>
            <label for="new_password" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
            <input type="password" name="new_password" id="new_password" required
                   class="mt-1 input">
            @error('new_password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Xác nhận mật khẩu mới -->
        <div>
            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                   class="mt-1 input">
            @error('new_password_confirmation')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nút cập nhật -->
        <div class="text-center">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Cập nhật mật khẩu
            </button>
        </div>
    </form>
</div>
