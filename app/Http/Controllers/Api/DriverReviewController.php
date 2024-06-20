<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverReviewController extends Controller
{
    public function rating(Request $request)
    {
        $id = $request->query('id');
        $validate = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string'
        ], [
            'rating.required' => 'Rating is required',
            'rating.numeric' => 'Rating must be a number',
            'rating.min' => 'Rating must be between 1 and 5',
            'rating.max' => 'Rating must be between 1 and 5',
            'review.required' => 'Review is required',
            'review.string' => 'Review must be a string'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Error!',
                'data_driver' => ['errors' => $validate->errors()]
            ], 400);
        }

        try {
            $review = review::create([
                'user_id' => $id,
                'rating' => $request->rating,
                'review' => $request->review
            ]);

            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'data_rating' => $review
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'data_rating' => $th->getMessage()
            ], 500);
        }
    }
}
