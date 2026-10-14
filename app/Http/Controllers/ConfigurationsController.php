<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ConfigurationsController extends Controller
{
    public function index()
    {

        $configurations = Setting::where('name', '<>', 'default_store_id')->orderBy('id', 'ASC')->get();

        return View::make('configurations.index')
            ->with(compact('configurations'));
    }

    public function store(Request $request)
    {
        $setting = new Setting;
        $setting->display_name = $request->display_name;
        $setting->name = $request->name;
        $setting->save();
        session()->flash('alert-success', 'Setting is Added successfully!');

        return back();

    }

    public function update(Request $request)
    {
        $logo = $request->file('logo');
        $setting = Setting::find($request->setting_id);

        if ($logo) {
            File::delete(public_path().'/logo/'.$setting->value);
            $originalLogoName = $logo->getClientOriginalName();
            $logoExtension = $logo->getClientOriginalExtension();
            $logoStore = base_path().'/public/logo/';
            $logoName = $logo->getFilename().'.'.$logoExtension;
            $logo->move($logoStore, $logoName);
            $setting->value = $logoName;
        } else {
            $setting->value = $request->formdata;
        }

        $setting->updated_by = Auth::user()->id;
        $setting->save();

        session()->flash('alert-success', 'Changes saved successfully!');

        return back();
    }

    public function destroy($id)
    {
        //
    }
}
