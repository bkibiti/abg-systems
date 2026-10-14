<?php

namespace App\Http\Controllers;

use App\Models\PriceCategory;
use Exception;
use Illuminate\Http\Request;

class PriceCategoryController extends Controller
{
    public function index()
    {

        $price_category = PriceCategory::orderBy('id', 'ASC')->get();
        return view('masters.price_category.index')->with('price_category', $price_category);
    }

 

    public function update(Request $request)
    {

        $price_category = PriceCategory::find($request->price_category_id);
        $price_category->description = $request->description;
        $price_category->active = $request->active;
        $price_category->save();

        session()->flash("alert-success", "Price category updated successfully!");
        return back();

    }

    public function destroy(Request $request)
    {
        try {
            PriceCategory::destroy($request->price_category_id);
            session()->flash("alert-danger", "Price category deleted successfully!");
            return back();
        } catch (Exception $e) {
            session()->flash("alert-danger", "Price category in use!");
            return back();
        }

    }
}
