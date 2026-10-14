<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::orderBy('id', 'ASC')->get();
        return view('customers.index', compact("customers"));

    }

    public function store(Request $request)
    {
        $customer = new Customer;
        $customer->cno = $this->getCustomerNo();
        $customer->name = $request->name;
        $customer->dob = date('Y-m-d', strtotime($request->dob));
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->status = 'Active';
        $customer->save();

        session()->flash("alert-success", "Customer Added Successfully!");
        return back();
    }


    public function update(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->name = $request->name;
        $customer->dob = date('Y-m-d', strtotime($request->dob));
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->status = $request->status;
        $customer->save();

        session()->flash("alert-success", "Customer Updated Successfully!");
        return back();
    }


    public function destroy(Request $request)
    {
        Customer::find($request->id)->delete();
        session()->flash("alert-danger", "Customer Deleted successfully!");
        return back();
    }

    public function getCustomerNo(){
        $lastno = Customer::orderBy('id','desc')->first();
        if ($lastno == ''){
            $id = 10000;
        }else{
            $id = substr($lastno->cno, -5);
            $id = (int)$id + 1;
        }
        $no = "CNO". $id;
        return $no;
    }
}
