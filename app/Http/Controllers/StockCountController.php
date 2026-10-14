<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\MonthClosingStock;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

class StockCountController extends Controller
{


	public function index()
	{
		$products = Stock::orderBy('category_id')->orderBy('name')->get();
	
        return view('inventory.count_sheet')->with(['products' => $products]);
	}

	public function print(Request $request)
	{
		$products = Stock::orderBy('category_id')->orderBy('name')->get();

        $pdf = PDF::loadView('inventory.count_sheet_pdf', compact('products'));

        return $pdf->stream('stock_count.pdf');

	}

	public function closingIndex()
	{
		$products = MonthClosingStock::whereRaw('month=month(now()) and year = year(now())')->get();

		$value_purchase = 0;
		$value_selling = 0;
		foreach ($products as $p) {
			$value_purchase = $value_purchase + $p->value_purchase;
			$value_selling = $value_selling + $p->value_selling;
		}

        return view('inventory.monthly-closing',compact('products','value_purchase','value_selling'));
	}

	public function closingStockStore(Request $request)
	{
		$data = MonthClosingStock::whereRaw("month = '". $request->month ."' and year = '".$request->year."'");
		
		if($data->count() > 0){
			session()->flash("alert-danger", "Monthly closing stock has already been recorded!");
			return back();
		}

		$products = Stock::selectRaw('id,quantity,quantity*unit_cost as value_purchase, quantity*sale_price_1 as value_selling')->get();
		
		foreach ($products as $p) {
			$stock = new MonthClosingStock;
			$stock->stock_id = $p->id;
            $stock->qty = $p->quantity;
            $stock->value_purchase = $p->value_purchase;
            $stock->value_selling =  $p->value_selling;
            $stock->month = $request->month;
            $stock->year = $request->year;
			$stock->save();
		}
		
        session()->flash("alert-success", "Monthly closing stock generated successfully!");

        return back();
	}

	public function closingStockFilter(Request $request)
	{
		$products = MonthClosingStock::where('month', $request->month)->where('year',$request->year)->get();
		$value_purchase = 0;
		$value_selling = 0;
		foreach ($products as $p) {
			$value_purchase = $value_purchase + $p->value_purchase;
			$value_selling = $value_selling + $p->value_selling;
		}

		$request->flash();

        return view('inventory.monthly-closing',compact('products','value_purchase','value_selling'));
	}
	

}
