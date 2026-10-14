<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\ProductCategory;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Stock::with('category')->servicesFirst()->get();
        $categories = ProductCategory::orderBy('name')->get();

        return view('masters.products.index', compact('products', 'categories'));
    }

    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();

        $product = new Stock;
        $product->name = $data['name'];
        $product->category_id = $data['category_id'];
        $product->type = $data['type'];
        $product->sale_price_1 = $data['type'] === Stock::TYPE_SERVICE ? null : ($data['sale_price_1'] ?? null);
        $product->min_quantinty = $data['type'] === Stock::TYPE_SERVICE ? 0 : ($data['min_quantinty'] ?? 0);
        $product->quantity = 0;
        $product->unit_cost = 0;
        $product->save();

        session()->flash('alert-success', 'Product added successfully!');

        return back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:stock,id',
            'name' => ['required', 'string', 'max:100', Rule::unique('stock', 'name')->ignore($request->id)],
            'category_id' => 'required|exists:product_categories,id',
            'type' => ['required', Rule::in([Stock::TYPE_ITEM, Stock::TYPE_SERVICE])],
            'sale_price_1' => 'nullable|numeric|min:0',
            'min_quantinty' => 'nullable|integer|min:0',
        ]);

        $product = Stock::findOrFail($data['id']);
        $product->name = $data['name'];
        $product->category_id = $data['category_id'];
        $product->type = $data['type'];
        $product->sale_price_1 = $data['type'] === Stock::TYPE_SERVICE ? null : ($data['sale_price_1'] ?? null);
        $product->min_quantinty = $data['type'] === Stock::TYPE_SERVICE ? 0 : ($data['min_quantinty'] ?? 0);
        $product->save();

        session()->flash('alert-success', 'Product updated successfully!');

        return back();
    }

    public function destroy(Request $request)
    {
        $product = Stock::find($request->product_id);

        if (! $product) {
            return back();
        }

        // Keep history intact: never delete a product that has been used
        $used = DB::table('sales')->where('stock_id', $product->id)->exists()
            || DB::table('purchases')->where('product_id', $product->id)->exists()
            || DB::table('stock_adjustments')->where('stock_id', $product->id)->exists();

        if ($used) {
            session()->flash('alert-danger', "'{$product->name}' cannot be deleted because it has sales, purchases or stock adjustments.");

            return back();
        }

        $product->delete();
        session()->flash('alert-danger', 'Product deleted successfully!');

        return back();
    }
}
