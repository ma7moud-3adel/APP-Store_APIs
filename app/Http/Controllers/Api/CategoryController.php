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
            return ApiResponse::sendResponse(200, 'Categories Retrieved Successfully', $cat);
        }
        return ApiResponse::sendResponse(200, 'Not Found any Categories', []);
    }

    public function create(Request $request)
    {
        $allCats = array("Milk", "Juice");

        request()->validate([
            'name' => ['required', 'min:3'],
            'description' => ['required'],
        ]);
        $cat = Category::where('name', $request->input('name'))->get();

        if (in_array($cat, $allCats)) {
            if ($cat) {
                return ApiResponse::sendResponse(200, 'Category already exists', $cat);
            }

            $cat = Category::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return ApiResponse::sendResponse(201, 'Category Added Successfully', $cat);
        }
        return ApiResponse::sendResponse(200, "Category Doesn't exists in Categories List", []);
    }
}
