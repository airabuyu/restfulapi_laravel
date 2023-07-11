<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json(['categories' => $categories], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json(['category' => $category], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json(['category' => $category], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->save();

        return response()->json(['category' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $hasRelation = $category->products()->wherePivot('category_id', $category->id)->exists();

        if ($hasRelation) {
            return response()->json(['error' => 'Cannot delete category with related products'], 400);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted'], 200);
    }

}
