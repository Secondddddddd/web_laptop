<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Ward;
use App\Models\UserAddress;

class AddressController extends Controller
{

    public function getProvinces()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    // Lấy danh sách quận/huyện theo tỉnh
    public function getDistricts($province_code)
    {
        $districts = District::where('province_code', $province_code)->get(['code', 'full_name']);
        return response()->json($districts);
    }

    // Lấy danh sách phường/xã theo quận/huyện
    public function getWards($district_code)
    {
        $wards = Ward::where('district_code', $district_code)->get(['code', 'full_name']);
        return response()->json($wards);
    }

    public function destroy($id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$address) {
            return response()->json(['error' => 'Địa chỉ không tồn tại hoặc bạn không có quyền xóa.'], 403);
        }

        // Nếu địa chỉ bị xóa là mặc định, có thể cập nhật địa chỉ khác làm mặc định
        if ($address->is_default) {
            $newDefault = UserAddress::where('user_id', auth()->id())->where('id', '!=', $id)->first();
            if ($newDefault) {
                $newDefault->is_default = true;
                $newDefault->save();
            }
        }

        $address->delete();

        return redirect()->route('user.info', ['active' => 'address']);
    }
}

