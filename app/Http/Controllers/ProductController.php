<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Stock::orderBy('name')->get();

        return view('masters.products.index')->with(['products' => $products]);
    }

    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();

        $product = new Stock;
        $product->name = $data['name'];
        $product->min_quantinty = $data['min_quantinty'] ?? 0;
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
            'min_quantinty' => 'nullable|integer|min:0',
        ]);

        $product = Stock::findOrFail($data['id']);
        $product->name = $data['name'];
        $product->min_quantinty = $data['min_quantinty'] ?? 0;
        $product->save();

        session()->flash('alert-success', 'Product updated successfully!');

        return back();
    }

    public function destroy(Request $request)
    {
        try {
            Stock::destroy($request->product_id);
            session()->flash('alert-danger', 'Product deleted successfully!');
        } catch (Exception $exception) {
            session()->flash('alert-danger', 'This product cannot be deleted because it has purchases, sales or stock adjustments.');
        }

        return back();
    }
}
