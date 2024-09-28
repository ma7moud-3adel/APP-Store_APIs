<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request)
    {
        $cat = Category::all();
        if (count($cat) > 0) {
            return ApiResponse::sendResponse(200, 'Products Retrieved Successfully', $cat);
        }
        return ApiResponse::sendResponse(200, 'Products Not Found', []);
    }

    public function create(Request $request)
    {

        request()->validate([
            'name' => ['required', 'min:3'],
            'description' => ['required'],
        ]);
        $ct = Category::where('name', $request->input('name'))->get();

        if (count($ct) != 0) {
            return ApiResponse::sendResponse(201, 'Category already exists', $ct);
        }

        $cat = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return ApiResponse::sendResponse(201, 'Category Added Successfully', $cat);
    }
}
