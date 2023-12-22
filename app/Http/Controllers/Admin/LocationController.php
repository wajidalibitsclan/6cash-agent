<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function country()
    {
        $countries = Country::get();
        return view('admin-views.location.country', compact('countries'));
    }

    public function countryStatus($id)
    {
        $country = Country::where('id', $id)->first();
        $status = $country->status === 'active' ? 'block' : 'active';
        $country->update([
            'status' => $status
        ]);

        Toastr::success(translate('Country status updated successfully!'));
        return back();
    }

    public function cityStatus($id)
    {
        $city = City::where('id', $id)->first();
        $status = $city->status === 'active' ? 'block' : 'active';
        $city->update([
            'status' => $status
        ]);
        Toastr::success(translate('City status updated successfully!'));
        return back();
    }

    public function city()
    {
        $cities = City::with('country')->orderBy('id', 'desc')->get();

        return view('admin-views.location.city', compact('cities'));
    }

    public function addCity($country)
    {
        return view('admin-views.location.add', ['country' => $country]);
    }


    public function storeCity(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required|string'
        ], [
            'name.required' => translate('Name is required'),
            'status.required' => translate('Status is required')
        ]);

        if ($validation->fails()) {
            Toastr::error($validation->getMessageBag());
            return back();
        }

        City::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'status' => $request->status
        ]);

        Toastr::success(translate('City is created'));
        return back();
    }

    public function editCity($id)
    {
        try {
            $city = City::with('country')->where('id', $id)->first();
            $countries = Country::get();
            return view('admin-views.location.edit', compact('city', 'countries'));
        } catch (\Exception $error) {
            Toastr::error(translate('Something went wrong!'));
            return back();
        }
    }

    public function updateCity(Request $request)
    {
        try {
            $city = City::where('id', $request->id)->first();
            if (is_null($city)) {
                Toastr::error(translate('City does not exist!'));
                return back();
            }
            $city->update([
                'name' => $request->name,
                'country_id' => $request->country_id,
                'status' => $request->status
            ]);
            Toastr::success(translate('City updated successfully!'));
            return redirect()->route('admin.location.city');
        } catch (\Exception $error) {
            Toastr::error(translate('Something went wrong!'));
            return back();
        }
    }
}
