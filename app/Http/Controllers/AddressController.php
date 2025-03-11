<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Ward;

class AddressController extends Controller
{
    // Lấy danh sách quận/huyện theo tỉnh
    public function getDistricts($province_code)
    {
        $districts = District::where('province_code', $province_code)->get(['code', 'name']);
        return response()->json($districts);
    }

    // Lấy danh sách phường/xã theo quận/huyện
    public function getWards($district_code)
    {
        $wards = Ward::where('district_code', $district_code)->get(['code', 'name']);
        return response()->json($wards);
    }
}

