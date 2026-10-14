<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class CurrentStockController extends Controller
{
    public function index()
    {

        $Products = Stock::goods()->orderBy('name')->get();

        return view('inventory.index', compact('Products'));
    }

    public function update(Request $request)
    {

        $product = Stock::find($request->id);
        $product->unit_cost = $request->unit_cost;
        $product->sale_price_1 = $request->sale_price_1;
        $product->save();

        session()->flash('alert-success', 'Price Updated successfully!');

        return back();

    }

    public function filter(Request $request)
    {

        if ($request->status == '0') {
            $Products = Stock::goods()->orderBy('name')->get();
        }
        if ($request->status == '1') {
            $Products = Stock::goods()->where('quantity', 0)->get();
        }
        if ($request->status == '2') {
            $Products = Stock::goods()->whereRaw('quantity <= min_quantinty and quantity > 0')
                ->get();
        }

        $request->flash();

        return view('inventory.index', compact('Products'));
    }

    public function stockOut()
    {
        $Products = Stock::goods()->where('quantity', 0)->get();

        return view('inventory.out_of_stock', compact('Products'));
    }

    public function belowMin()
    {
        $Products = Stock::goods()->whereRaw('quantity < min_quantinty and quantity > 0')->get();

        return view('inventory.below_min', compact('Products'));
    }
}
