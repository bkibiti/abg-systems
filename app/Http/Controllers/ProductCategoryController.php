<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        return view('masters.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name',
        ]);

        ProductCategory::create($data);

        session()->flash('alert-success', 'Product category added successfully!');

        return back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:product_categories,id',
            'name' => ['required', 'string', 'max:100', Rule::unique('product_categories', 'name')->ignore($request->id)],
        ]);

        $category = ProductCategory::findOrFail($data['id']);
        $category->name = $data['name'];
        $category->save();

        session()->flash('alert-success', 'Product category updated successfully!');

        return back();
    }

    public function destroy(Request $request)
    {
        $category = ProductCategory::withCount('products')->find($request->id);

        if (! $category) {
            return back();
        }

        if ($category->products_count > 0) {
            session()->flash('alert-danger', "Cannot delete '{$category->name}': it is used by {$category->products_count} product(s).");

            return back();
        }

        $category->delete();
        session()->flash('alert-danger', 'Product category deleted successfully!');

        return back();
    }
}
