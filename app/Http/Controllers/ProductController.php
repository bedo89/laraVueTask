<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Product;
use App\Category;
use Auth;

class ProductController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Product Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the Product CRUD process and exchange data through API.
    |
    */

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(5);
        return response()->json($products, 200);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation section before storing new product
        $request->validate([
            'name' => 'required|min:3',
            'price' => 'required|digits_between:1,4',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        // create new instance of product
        $product = new Product();
        $product->name = $request->name;
        $product->user_id = Auth::id();
        $product->description = $request->description;
        $product->price = $request->price;

        // Storing image
        $path = $request->file('image')->store('products_images');

        $product->image = $path;

        if ($product->save()) {
            return response()->json($product, 200);
        } else {
            return response()->json([
                'message' => 'Some error occurred, please try agian',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        // validation process before updating a specified product
        $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|digits_between:1,4',
            'description' => 'required|string',
        ]);

        // Assign the new values to the specified product
        $product->name = $request->name;
        $product->user_id = Auth::id();
        $product->description = $request->description;
        $product->price = $request->price;

        $oldPath = $product->image;

        // storing image if sent a new image
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg',
            ]);

            $path = $request->file('image')->store('products_images');
            $product->image = $path;

            Storage::delete($oldPath);
        }

        if ($product->save()) {
            return response()->json($product, 200);
        } else {
            Storage::delete($path);
            return response()->json([
                'message' => 'Some error occurred, Please try agian!',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Delete the image file after deleting the whole product
        if ($product->delete()) {

            Storage::delete($product->image);

            return response()->json([
                'message' => 'Product deleted successfully!',
                'status_code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Some error occurred, please try again',
                'status_code' => 500
            ], 500);
        }
    }
}
