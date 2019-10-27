<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Validator;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\Provider;
use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Locations;

class AdminController extends Controller
{
    /**
     * function to show admin home page
     *
     * @param Request $request
     *
     * @return view
     */
    public function home(Request $request)
    {
        return view('admin.home');
    }

    /**
     * function to manage cities
     *
     * @return view
     *
     */
    public function cities()
    {
        $cities = City::all();
        return view('admin.cities.index')->with([
            'cities' => $cities
        ]);
    }

    /**
     * function to show add new city page
     *
     * @return view
     *
     */
    public function newCity()
    {
        return view('admin.cities.create');
    }

    /**
     * function to post create new city
     *
     * @param Request $request
     *
     * @return view
     *
     */
    public function postCity(Request $request)
    {
        $rule = [
            'name' => 'required|unique:cities'
        ];
        $validator = $this->makeValidation($request, $rule);
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $data = $request->all();
        $data['is_active'] = 1;
        $new_city = new City;
        $new_city->create($data);
        $new_city->save();
        Session::flash('success', '  تم إضافة المدينة بنجاح  ');
        return redirect('admin/cities');
    }

    /**
     * function to show edit city page
     *
     * @param int $city_id
     *
     * @return view
     *
     */
    public function editCity($city_id)
    {
        try {
            $city = City::findOrFail($city_id);
            return view('admin.cities.edit')->with(['city' => $city]);
        } catch (ModelNotFoundException $e) {
            return $e;
        }

    }
    /**
     * function to post edit city
     *
     * @param Request $request
     *
     * @return view
     *
     */
    public function postEditCity(Request $request)
    {

        try {
            $rule = [
                'name' => 'unique:cities,name,'.$request->city_id
            ];
            $validator = $this->makeValidation($request, $rule);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }
            $data = $request->all();
            $data['is_active'] = 1;
            $city = City::findOrFail($request->city_id);
            $city->fill($data);
            $city->save();
            Session::flash('success', '  تم تعديل المدينة بنجاح  ');
            return redirect('admin/cities');
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }


    /**
     * function to delete city
     *
     * @param int $city_id
     *
     * @return view
     *
     */
    public function deleteCity($city_id)
    {
        try {
            $city = City::findOrFail($city_id);
            if (count($city->branches) > 0) {
                Session::flash('error', 'لايمكن حذف المدينة لارتباطها بعدة امور');
                return redirect('admin/cities');
            } else {
                $city->delete();
                Session::flash('success', '  تم حذف المدينة بنجاح  ');
                return redirect('admin/cities');
            }
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }
    /**
     * function to manage list of locations
     *
     * @return view
     *
     */
    public function loccations()
    {
        $locations = Locations::all();
        return view('admin.locations.index')->with(['locations' => $locations]);
    }

    /**
     * function to show add new location page
     *
     * @return view
     *
     */
    public function newLocation()
    {
        $cities = City::all();
        return view('admin.locations.create')->with([
            'cities' => $cities
        ]);
    }

    /**
     * function to post new location
     *
     * @param Request $request
     *
     * @return view
     *
     */
    public function postNewLocation(Request $request)
    {
        $data = $request->all();
        $rule = [
            'name' => 'required|unique:locations,name',
            'city_id' => 'required|exists:cities,id',
        ];

        $validator = $this->makeValidation($request, $rule);
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $location = new Locations;
        $location->name = $request->name;
        $location->city_id = $request->city_id;
        $location->save();

        Session::flash('success', '   تم إضافة موقع جديد بنجاح ');
        return redirect('admin/locations');
    }

    /**
     * function to show edit location page
     *
     * @param int $location_id
     *
     * @return view
     *
     */
    public function locationEdit($location_id)
    {
        try {
            $location = Locations::findOrFail($location_id);
            return view('admin.locations.edit')->with([
                'location' => $location,
                'cities' => City::all()
            ]);
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * function to post update location
     *
     * @param Request $request
     *
     * @return view
     *
     */
    public function updateLocation(Request $request)
    {
        $data = $request->all();
        $rule = [
            'name' => 'unique:locations,name,'.$request->location_id,
            'city_id' => 'exists:cities,id',
        ];

        $validator = $this->makeValidation($request, $rule);
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $location = Locations::findOrFail($request->location_id);
        $location->name = $request->name;
        $location->city_id = $request->city_id;
        $location->save();

        Session::flash('success', '   تم تعديل الموقع   ');
        return redirect('admin/locations');
    }

    /**
     * function to delete location
     *
     * @param int $location_id
     *
     * @return view
     *
     */
    public function locationDelete($location_id)
    {
        try {
            $location = Locations::findOrFail($location_id);
            $location_branches = Branch::where('location_id', '=', $location_id)->get();
            if (count($location_branches) > 0) {
                Session::flash('error', 'لايمكن حذف الموقع لارتباطه بعدة امور');
                return redirect('admin/locations');
            } else {
                $location->delete();
                Session::flash('success', '  تم حذف الموقع بنجاح  ');
                return redirect('admin/locations');
            }
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }
}
