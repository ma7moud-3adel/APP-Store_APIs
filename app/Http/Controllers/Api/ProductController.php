<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request)
    {
        $products = Product::all();
        if (count($products) > 0) {
            return ApiResponse::sendResponse(200, 'Products Retrieved Successfully', $products);
        }
        return ApiResponse::sendResponse(200, 'Products Not Found', []);
    }

    public function create(Request $request)
    {
        $cat1 = Category::find(1);
        $cat2 = Category::find(2);

        request()->validate([
            'name' => ['required', 'min:3'],
            'description' => ['required'],
            'cat_id' => ['exists:categories,id']
        ]);
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'cat_id' => $request->cat_id,
        ]);
        if ($product['cat_id'] == 1) {
            $product->update(['description' => $cat1['description']]);

            Category::create([
                'name' => $cat1['name'],
                'description' => $cat1['description']
            ]);
        } elseif ($product['cat_id'] == 2) {
            $product->update(['description' => $cat2['description']]);

            Category::create([
                'name' => $cat2['name'],
                'description' => $cat2['description']
            ]);
        }
        return ApiResponse::sendResponse(201, 'Product Added Successfully', $product);
    }

    public function update(Request $request, $id)
    {
        $cat1 = Category::find(1);
        $cat2 = Category::find(2);

        $product = Product::find($id);
        if ($product) {
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                // 'cat_id'=>$request()->cat_id,
            ]);
            if (($product['name'] == 'Milk') || ($product['name'] == 'milke')) {
                $product->update([
                    'description' => $cat1['description'],
                    'cat_id' => $cat1['id']
                ]);
            } elseif (($product['name'] == 'Juice') || ($product['name'] == 'juice')) {
                $product->update([
                    'description' => $cat2['description'],
                    'cat_id' => $cat2['id']
                ]);
            }
            return ApiResponse::sendResponse(201, 'Product Updated Successfully', $product);
        }
        return ApiResponse::sendResponse(200, 'Product Not Found', []);
    }

    public function delete($id)
    {
        $product = Product::find($id);

        $product->delete();
        return ApiResponse::sendResponse(200, 'Product Deleted Successfully', $product);
    }


    public function search(Request $request)
    {
        $cat = Product::where('cat_id', $request->input('cat_id'))->get();
        if (count($cat) > 0) {
            return ApiResponse::sendResponse(200, 'All Products in This Category', $cat);
        }
        return ApiResponse::sendResponse(200, 'Product Not Found', []);
    }
}
