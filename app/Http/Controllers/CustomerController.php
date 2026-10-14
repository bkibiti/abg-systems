<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'ASC')->get();

        return view('customers.index', compact('customers'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'mobile' => ['required', 'regex:/^255[0-9]{9}$/'],
            'location' => 'required|string|max:100',
        ], [
            'mobile.regex' => 'Mobile must be 12 digits starting with 255, e.g. 255745777602.',
        ]);

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->location = $request->location;
        $customer->status = 'Active';
        $customer->save();

        session()->flash('alert-success', 'Customer Added Successfully!');

        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:customers,id',
            'name' => 'required|string|max:50',
            'mobile' => ['required', 'regex:/^255[0-9]{9}$/'],
            'location' => 'required|string|max:100',
            'status' => 'required|in:Active,Inactive',
        ], [
            'mobile.regex' => 'Mobile must be 12 digits starting with 255, e.g. 255745777602.',
        ]);

        $customer = Customer::find($request->id);
        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->location = $request->location;
        $customer->status = $request->status;
        $customer->save();

        session()->flash('alert-success', 'Customer Updated Successfully!');

        return back();
    }

    public function destroy(Request $request)
    {
        Customer::find($request->id)->delete();
        session()->flash('alert-danger', 'Customer Deleted successfully!');

        return back();
    }
}
