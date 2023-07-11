<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->get();

        return response()->json(['products' => $products], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        $product->categories()->attach($request->categories);

        $product->load('categories');

        return response()->json(['product' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::with('categories')->findOrFail($id);

        return response()->json(['product' => $product], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        $product->categories()->sync($request->categories);

        return response()->json(['product' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->categories()->detach();

        $product->delete();

        return response()->json(['message' => 'Product deleted'], 200);
    }
}
