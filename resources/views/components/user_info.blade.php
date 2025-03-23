<div class="ml-5 mt-5">
    @if ($errors->has('avatar'))
        <div class="alert alert-danger">{{ $errors->first('avatar') }}</div>
    @endif

    <form action="{{ route('user.update_info')  }}" method="POST" enctype="multipart/form-data"  class="flex flex-col gap-5">
        @csrf
        @method('PUT')
        <!-- Khối chứa thông tin và avatar -->
        <div class="flex w-full gap-5">
            <!-- Cột thông tin -->
            <div class="w-3/5">
                <div class="mb-4 mt-5 flex items-center">
                    <label class="label w-32">Họ và tên:</label>
                    <input type="text" name="full_name" class="input flex-1" value="{{$name}}" />
                </div>
                <div class="mb-4 flex items-center">
                    <label class="label w-32">Email:</label>
                    <input type="email" name="email" class="input flex-1" value="{{$email}}" />
                </div>
                <div class="mb-4 flex items-center">
                    <label class="label w-32">Số điện thoại:</label>
                    <input type="number" name="phone" class="input flex-1" value="{{$phone}}" />
                </div>
            </div>

            <!-- Cột avatar -->
            <div class="w-2/5 flex flex-col justify-center items-center gap-4 h-full">
                <div class="avatar">
                    <div class="w-32 rounded-full">
                        <img src="{{$avatar}}" alt="avatar" />
                    </div>
                </div>
                <input type="file" name="avatar" class="file-input file-input-info"  />
            </div>
        </div>

        <!-- Nút cập nhật -->
        <div class="flex justify-center">
            <button class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>
