<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use App\Models\Provider;
use Request as HttpRequest;
use App\Models\ProviderDocuments;
use App\Models\Standard;
use App\Models\Branch;
use App\Models\Car;
use App\Models\CarType;
use App\Models\Brand;
use App\Models\Features;
use DB;
use AppHelper;
use App\Models\CarFeature;
use App\Models\City;
use App\Models\Locations;
use App\Models\Payment;
use App\Models\ProviderPayments;
use App\Models\Rate;
use App\Models\Reservation;
use Session;
use App\Models\rateProvider;

class ProviderController extends Controller
{
    private $provider;

    private $car;

    private $branch;

    private $user;

    private $standerds;

    /**
     * class constructor
     */
    function __construct(Provider $provider, Car $car, Branch $branch, Standard $standerds)
    {
        $this->provider = $provider;
        $this->car = $car;
        $this->branch = $branch;
        $this->user = Auth::user();
        $this->standerds = $standerds;
    }

    /**
     * function to create new provider
     *
     * @param  Request $request
     *
     * @return  view
     */
    public function newProvider(Request $request)
    {
    	HttpRequest::merge(['admin_mobile' => $this->formatMobileNumber($request->get('admin_mobile'))]);
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->provider->new_provider_rules);
        if ($validator->fails()) {
            return redirect('/register')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data['password'] = bcrypt($data['admin_mobile']);
        $provider = new Provider;
        $provider = $provider->create($data);
        $saved = $provider->save();
    }

    /**
     * function to show add documets page
     *
     * @param  Request $request
     *
     * @return view
     */
    public function addDocuments($provider_id)
    {
        return view('providers.add-documents')->with(['provider_id' => $provider_id]);
    }

    /**
     * function to upload provider files
     *
     * @param  Request $request
     *
     * @return  view
     */
    public function uploadDocuments(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->provider->document_store_rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;

        $data['commercial_log'] = $this->upload($request, 'commercial_log');
        $data['logo'] = $this->upload($request, 'logo');

        $provider_documents = new ProviderDocuments;
        $provider_documents = $provider_documents->create($data);
        $saved = $provider_documents->save();

        return redirect('/');
    }

    /**
     * function to show the provider cars page
     *
     * @param  Request $request
     *
     * @return  view
     */
    public function showCars(Request $request)
    {
        $provider = Auth::user();
        return view('providers.cars.index')->with([
            'provider' => $provider
        ]);
    }

    /**
     * function to show add new car page
     *
     * @param  Request $request
     *
     * @return  view
     */
    public function newCar(Request $request)
    {
        $provider = Auth::user();
        $brands = Brand::all();
        $branches = Branch::where('provider_id', '=', $provider->id)->get();
        $features = Features::all();

        return view('providers.cars.create-car')->with([
            'provider' => $provider,
            'brands' => $brands,
            'branches' => $branches,
            'features' => $features
        ]);
    }

    /**
     * function to store new car
     *
     * @param  Request $request
     *
     * @return  view
     */
    public function saveCar(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->car->post_new_rules());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $data['picture'] = $data['type'];
        if (empty($data['branch_id'])) {
            $data['branch_id'] = 0;
        }
        if ($data['can_delivery_in_another_branch'] == "on") {
            $data['can_delivery_in_another_branch'] = 1;
        } else {
            $data['can_delivery_in_another_branch'] = 0;
        }

        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $car = new Car;
        $car = $car->create($data);
        $saved = $car->save();
        $features = $data['features_id'];

        foreach ($features as $key=> $featue) {
            $car->features()->attach($featue);
        }
        $car = Car::with(['features'])->where('id' ,'=', $car->id)->first();
        dd($car);
        //return redirect()->back();
    }

    /**
     * function to show add new branch page
     *
     * @param int $provider_id
     *
     * @return view
     */
    public function addBranch($provider_id)
    {
        return view('providers.add-branche')->with(['provider_id' => $provider_id]);
    }

    /**
     * function to post create provider branch
     *
     * @param Request $request
     *
     * @return view
     */
    public function postBranch(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->branch->post_new_rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch = new Branch;

        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $branch = new Branch;
        $branch = $branch->fill($data);
        $saved = $branch->save();

        Session::flash('success', 'تم إضافة فرع جديد بنجاح');
        return redirect('/');
    }

    /**
     * function to post create new car
     *
     * @param Request $request
     *
     * @return view
     *
     */
    public function addCar(Request $request )
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->car->post_new_rules());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (empty($data['branch_id'])) {
            $data['branch_id'] = 0;
        }
        if ($data['can_delivery_in_another_branch'] == "on") {
            $data['can_delivery_in_another_branch'] = 1;
        } else {
            $data['can_delivery_in_another_branch'] = 0;
        }


        $provider_id = Auth::user()->id;

        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $car = new Car;
        $car = $car->create($data);
        $saved = $car->save();
        $features = $data['features_id'];

        foreach ($features as $key=> $featue) {
            $car->features()->attach($featue);
        }
        Session::flash('success', 'تم إضافة سيارة جديدة بنجاح');
        return redirect('/cars');
    }

    /**
     * function to show car page
     *
     * @param Request $request
     *
     * @return view
     *
     */
    public function showCar($car_id)
    {
        $car = Car::findOrFail($car_id);
        $provider = Auth::user();
        $brands = Brand::all();
        $branches = Branch::where('provider_id', '=', $provider->id)->get();
        $car_features = $car->features->toArray();
        $car_features = CarFeature::where('car_id', '=', $car->id)->pluck('features_id')->toArray();
        $types = CarType::all();
        $features = Features::pluck('name', 'id')->toArray();
        $reservation = $car->reservation;

        return view('providers.cars.show')->with([
            'car' => $car,
            'provider' => $provider,
            'brands' => $brands,
            'branches' => $branches,
            'car_features' => $car_features,
            'cartype' => $types,
            'features' => $features,
            'reservation' => $reservation
        ]);
    }

    /**
     * function to update car info
     *
     * @param Request $request
     *
     * @return view
     */
    public function editCar(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->car->post_new_rules());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (empty($data['branch_id'])) {
            $data['branch_id'] = 0;
        }
        if ($data['can_delivery_in_another_branch'] == "on") {
            $data['can_delivery_in_another_branch'] = 1;
        } else {
            $data['can_delivery_in_another_branch'] = 0;
        }

        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $car = Car::findOrFail($data['car_id']);
        $car = $car->fill($data);
        $saved = $car->save();

        $features = $data['features_id'];
        $car->features()->detach();
        foreach ($features as $key=> $featue) {
            $car->features()->attach($featue);
        }
        $car = Car::with(['features'])->where('id' ,'=', $car->id)->first();
        Session::flash('success', 'تم تعديل بيانات السيارة بنجاح');
        return redirect()->route('car.show', $car->id);
    }

    /**
     * function to delete the car
     *
     * @param Request $request
     *
     * @return view
     */
    public function deleteCar($car_id)
    {
        $car = Car::where('id', '=', $car_id)->first();
        if ($car->status == 1) {
            Session::flash('error', 'لايمكن حذف السيارة يوجد حجز');
            return redirect()->back();
        } else {
            $car = Car::where('id', '=', $car_id)->delete();
            Session::flash('success', 'تم حذف السيارة بنجاح');
            return redirect()->back();
        }
    }

    /**
     * function to show branches page
     *
     * @param Request $request
     *
     * #return view
     */
    public function showBranches(Request $request)
    {
        $provider_id = Auth::user()->id;
        $provider = Auth::user();
        $branches = Branch::where('provider_id', '=', $provider_id)->get();

        return view('providers.branchs.index')->with([
            'branches' => $branches,
            'provider' => $provider
        ]);
    }

    /**
     * function to show create new branch page
     *
     * @param Request $request
     *
     * return view
     */
    public function newBranch(Request $request)
    {
        $provider_id = Auth::user()->id;
        return view('providers.branchs.create')->with(['provider_id' => $provider_id]);
    }

    /**
     * function to post create new branch
     *
     * @param Request $request
     *
     * @return view
     */
    public function postNewBranch(Request $request )
    {

        $data = $request->all();
        $validator = $this->makeValidation($request, $this->branch->post_new_rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch = new Branch;

        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $branch = new Branch;
        $branch = $branch->fill($data);
        $saved = $branch->save();
        Session::flash('success', 'تم إضافة فرع جديد بنجاح');
        return redirect('branchs/');
    }

    /**
     * function to show edit branch page
     *
     * @param Request $request
     *
     * @return view
     *
     */
    public function showEditBranch($branch_id)
    {
        $branch = Branch::where('id', '=', $branch_id)->first();
        $city = City::all();
        $city_id = City::where('name', '=', $branch->city)->first()->id;

        $locations = Locations::where('city_id', '=', $city_id)->get();

        $provider_id = Auth::user()->id;

        return view('providers.branchs.edit')->with([
            'branch' => $branch,
            'provider_id' => $provider_id,
            'city' => $city,
            'locations' => $locations
        ]);
    }

    /**
     * function to post update branch
     *
     * @param Request $request
     *
     * @return view
     */
    public function updateBranch(Request $request )
    {
        $location_on_map = "";
        if (!empty($request->latitude) && !empty($request->Longitude)) {
            $location_on_map = $request->latitude."-".$request->Longitude;
        }

        $data = $request->all();
        $validator = $this->makeValidation($request, $this->branch->update_rules($data['branch_id']));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data['location_on_map'] = $location_on_map;


        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $branch = Branch::findOrFail($data['branch_id']);
        $branch = $branch->fill($data);
        $saved = $branch->save();
        Session::flash('success', 'تم تعديل الفرع بنجاح');
        return redirect('branchs/');
    }

    /**
     * function to delete provider branch
     *
     * @param $branch_id
     *
     * @return view
     */
    public function deleteBranch($branch_id)
    {
        $cars_count = Car::where('branch_id', '=', $branch_id)->count();
        if ($cars_count > 0) {
            Session::flash('error', 'لايمكن حذف الفرع لارتباطه بعدة امور');
            return redirect()->back();
        } else {
            $branch = Branch::where('id', '=', $branch_id)->first();
            $branch->delete();
            Session::flash('success', 'تم حذف الفرع بنجاح');
            return redirect()->back();
        }
    }

    /**
     * function to show  add standerds for provider page
     *
     * @param $provider_id
     *
     * @return view
     */
    public function addStanderds($provider_id)
    {
        return view('providers.add-standerds')->with(['provider_id' => $provider_id]);
    }

    /**
     * function to store standerds
     *
     * @param Request $request
     *
     * @return view
     */
    public function storeStanderds(Request $request)
    {
        $data = $request->all();
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;

        $validator = $this->makeValidation($request, $this->standerds->post_new_rules);
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        $standards = new Standard;
        $standerds = $standards->create($data);
        $saved = $standerds->save();

        return redirect('/');

    }

    /**
     * function to show add payment type page
     *
     * @param Request $request
     *
     * @return view
     */
    public function addPaymentType($provider_id)
    {
        return view('providers.add-payment-type')->with(['provider_id' => $provider_id]);
    }

    /**
     * function to store provider payment type
     *
     * @param Request $request
     *
     * @return view
     */
    public function storePaymentType(Request $request)
    {
        $data = $request->all();
        $provider = Auth::user();

        $payment = new ProviderPayments;
        $payment->payment_type = $request->payment_type;
        $payment->provider_id = $provider->id;
        $payment->save();

        return redirect('/');
    }

    /**
    * function to add rate for the user
    *
    * @param Request $request
    *
    * @return view
    */
    public function addRate(Request $request)
    {
        $rate = new Rate;
        $data = $request->all();
        $data['provider_id'] = Auth::user()->id;

        if ($data['note'] == null) {
            $data['note'] = " ";
        }
        try {
            $reserviation = Reservation::findOrFail($data['reservation']);

            $provider_id = $reserviation->provider_id;
            $user_id = $reserviation->user_id;

            $car = Car::findOrFail($reserviation->car_id);
            $car->status = 0;
            $car->save();
            $reserviation->delete();

            DB::table('rate_providers')->insert([
                'provider_id' => $provider_id,
                'user_id' => $user_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (ModelNotFoundException $e) {
            echo $e;
        }
        $data['user_id'] = $user_id;
        $rate = $rate->create($data);
        $saved = $rate->save();
        Session::flash('success', 'تم إستلام السيارة بنجاح');
        return redirect()->back();
    }

    /**
     * function to show providers details page
     *
     * @param Request $request
     *
     *
     * @return view
     */
    public function details(Request $request )
    {
        $provider_id = Auth::user()->id;
        $provider = Provider::findOrFail($provider_id);

        return view('providers.details')->with([
            'provider' => $provider
        ]);
    }

    /**
     * function to update provider details
     *
     * @param Request $request
     *
     * @return view
     */
    public function updateDetails(Request $request)
    {
        $provider_id = $request->provider_id;
        HttpRequest::merge(['admin_mobile' => $this->formatMobileNumber($request->get('admin_mobile'))]);
        $data = $request->all();
        if (is_null($data['password'])) {
            $data = $request->except('password');
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $validator = $this->makeValidation($request, $this->provider->update_rules($provider_id));
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $provider = Provider::findOrFail($provider_id);
        $provider = $provider->fill($data);
        $saved = $provider->save();
        Session::flash('success', 'تم تعديل بيانات الشركة بنجاح');
        return redirect()->back();
    }

    /**
     * function to update provider documents
     *
     * @param Request $request
     *
     * @return view
     */
    public function updateDocuments(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->provider->document_update_rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (!is_null($request->commercial_log)) {
            $data['commercial_log'] = $this->upload($request, 'commercial_log');
        }

        if (!is_null($request->logo)) {
            $data['logo'] = $this->upload($request, 'logo');
        }
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $request->provider_id;
        $provider_documents = ProviderDocuments::findOrFail($data['provider_document_id']);
        $provider_documents = $provider_documents->fill($data);
        $saved = $provider_documents->save();
        Session::flash('success', 'تم تعديل المستندات بنجاح');
        return redirect()->back();
    }
}
