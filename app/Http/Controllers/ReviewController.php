<?php

namespace App\Http\Controllers;

use App\Models\Review;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'rating' => 'nullable|numeric|min:1|max:5', // Cho phép null và số thập phân
            'comment' => 'nullable|string'
        ]);

        $user_id = auth()->id();

        // Tìm đánh giá cũ của user cho sản phẩm này
        $review = Review::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($review) {
            // Nếu đã có đánh giá, cập nhật lại
            $review->update([
                'rating' => $request->rating, // Cho phép null hoặc số thập phân
                'comment' => $request->comment,
            ]);

            return back()->with('success', 'Đánh giá của bạn đã được cập nhật!');
        } else {
            // Nếu chưa có đánh giá, tạo mới
            Review::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return back()->with('success', 'Đánh giá của bạn đã được gửi!');
        }
    }


}
