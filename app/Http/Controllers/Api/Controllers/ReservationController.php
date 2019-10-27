<?php

namespace App\Http\Controllers\Api\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Reservation;
use App\Models\Car;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Auth;
use abdullahobaid\mobilywslaraval\Mobily;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use App\Events\SendNotification;
use App\Models\Provider;
use App\Models\Standard;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CarFeature;

class ReservationController extends Controller
{
    private $car;
    private $reservation;

    function __construct(Car $car, Reservation $reservation)
    {
        $this->serviceAccount = ServiceAccount::fromJsonFile(public_path('ajar-4af21-dfd0be8715ff.json'));

        $this->firebase = (new Factory)
            ->withServiceAccount($this->serviceAccount)
            ->withDatabaseUri('https://ajar-4af21.firebaseio.com/')
            ->create();
        $this->car = $car;
        $this->reservation = $reservation;
    }

    /**
     * function to search for cars
     *
     * @param Request $request
     *
     * @return response
     */
    public function search(Request $request)
    {
        $car = Car::query()->where('status', '=', 0);
        $car_list = [];

        if (isset($request->city)) {
            $branch = Branch::where('city', '=', $request->city)->pluck('id')->toArray();
            $car = $car->whereIn('branch_id', $branch);
        }
        if (isset($request->receipt)) {
            $branch = Branch::where('location_id', '=', $request->receipt)->pluck('id')->toArray();
            $car = $car->whereIn('branch_id', $branch);
        }
        if (isset($request->delivery)) {
            $branch = Branch::where('location_id', '=', $request->delivery)->pluck('id')->toArray();
            $car = $car->whereIn('branch_id', $branch);
        }
        if (isset($request->branch)) {
            $car = $car->where('branch_id', '=', $request->branch);
        }

        if(isset($request->categoy)) {
            $car = Car::where('category', '=', $request->categoy);
        }
        if (isset($request->insurance_type)) {
            $providers =Standard::where('insurance', '=', $request->insurance_type)->pluck('provider_id')->toArray();
            $branchs = Branch::whereIn('provider_id',$providers)->pluck('id')->toArray();
            $car = $car->whereIn('branch_id', $branchs);
        }
        if (isset($request->free_kilo)) {
            $providers =Standard::where('free_kilo', '=', $request->free_kilo)->pluck('provider_id')->toArray();
            $branchs = Branch::whereIn('provider_id',$providers)->pluck('id')->toArray();
            $car = $car->whereIn('branch_id', $branchs);
        }
        if (isset($request->licens_type)) {
            $providers =Standard::where('licens_type', '=', $request->licens_type)->pluck('provider_id')->toArray();
            $branchs = Branch::whereIn('provider_id',$providers)->pluck('id')->toArray();
            $car = $car->whereIn('branch_id', $branchs);
        }
        if (isset($request->features)) {
            $features = $request->features;
            $features = substr($features, 1, strlen($features) - 2);
            $features = explode(',', $features);

            $cars_list_id = CarFeature::whereIn('features_id', $features)->pluck('car_id')->toArray();
            $car = $car->whereIn('id', $cars_list_id);
        }
        $car_list = $car->get();
        $car_list2 = [];
        $car_list3 = [];

        if (isset($request->from_date) && isset($request->to_date)) {
            $number_of_dayes = $this->getNumberOfDayes($request->from_date, $request->to_date);
            foreach($car_list as $car){
                $car_list2[] = $this->setTotalPrice($number_of_dayes, $car);
            }
        } else {
            foreach($car_list as $car){
                $car_list2[] = $this->setTotalPrice(1, $car);
            }
        }
        if (!empty($car_list2)) {
            foreach ($car_list2 as $key=>$val) {
                $car_list[$key] = $val;
            }
        }

        if (isset($request->price_from) && isset($request->price_to)) {
            $car_list3[] = $this->formatByPrice($car_list, $request->price_from, $request->price_to);
        }
        foreach ($car_list3 as $test) {
            $car_list3 = $test;
        }
        //$car_list = array_intersect ($car_list, $car_list3);
        if (!empty($car_list3)) {
            $car_list = [];
            foreach ($car_list3 as $key => $val) {
                $car_list[$key] = $val;
            }
        }
        return response()->json([
            'status' => true,
            'items' => array_values((array)$car_list),
            'message' => __('general.fetch-data')
        ]);
    }

    /**
     * function to get interval between two dates
     *
     * @param $start_date, $end_date
     *
     * return int
     */
    public function getNumberOfDayes($start_date, $end_date)
    {
        if (empty($start_date) || empty($end_date)) {
            return 1;
        }
        $datetime1 = new DateTime($start_date);
        $datetime2 = new DateTime($end_date);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');

        return $days;
    }

    /**
     * function to set total price for car
     *
     * @param int $number_od_dayes, int $car_id
     *
     */
    public function setTotalPrice($number_of_dayes, $car)
    {
        DB::table('car_prices')->insert([
            'user_id' => Auth::user()->id,
            'total_price' => $number_of_dayes*$car['price_per_day'],
            'car_id' => $car['id']
        ]);
        try {
            $cars = Car::find($car['id']);
            $car_price = DB::table('car_prices')->where('user_id', '=', Auth::user()->id)->where('car_id', '=', $car['id'])->first();
            $cars['total_price'] =  $car_price->total_price;
            DB::table('car_prices')->where('user_id', '=', Auth::user()->id)->where('car_id', '=', $car['id'])->delete();
            return $cars;
        } catch (ModelNotFoundException $e) {

        }
    }

    /**
     * function to format cars list by price
     *
     */
    public function formatByPrice($cars, $from, $to)
    {
        $cars = $cars->filter(function ($value, $key) use($from, $to) {
            if ($value['total_price'] >= $from && $value['total_price'] <= $to) {
                return $value;
            }
        });
        return $cars;
    }

    /**
     * function to reserve car for the user
     *
     * @param int $car_id
     *
     * @return response
     */
    public function reserveCar(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->reservation->new_rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => __('general.error_msg')
            ]);
        }

        $car_id = $data['car_id'];
        $car = Car::findOrFail($car_id);
        $data['provider_id'] = $car->provider_id;
        $data['user_id'] = Auth::user()->id;
        $data['status'] = 1;
        $reservation = new Reservation;
        $reservation = $reservation->create($data);
        $saved = $reservation->save();

        if( $saved ) {

            event(new SendNotification('يوجد لديك حجز جديد', $car->provider_id));
            $this->updateCarStatus($car_id);
            $this->storeInFireBase($car->provider->id, $reservation->id);
        }
        return response()->json([
            'status' => true,
            'items' => $reservation,
            'message' => __('general.car-reserviations')
        ]);
    }

    /**
     * function to store new firebase reserviation record
     *
     * @param Reqeust $request
     *
     * @return response
     */
    public function storeInFireBase($provider_id, $reserviation_id)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(public_path('ajar-4af21-dfd0be8715ff.json'));
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://ajar-4af21.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        $newMsg = $database
            ->getReference('reserviations/'.$provider_id)
            ->set($reserviation_id);
    }

    /**
     * function to cancel car reserviation
     *
     * @param Request $request
     *
     * @return response
     *
     */
    public function cancelReservation(Request $request)
    {
        $reserviation_id = $request->reserviation_id;

        $reserviation = Reservation::where('id', '=', $reserviation_id)->first();
        $user = Auth::user();
        $car = Car::where('id', '=', $reserviation->car_id)->first();
        $current_date = date("Y-m-d");
        $branch = Branch::where('id', '=', $car->branch_id)->first();

        if (!empty( $reserviation)) {
            if ($current_date > date("Y-m-d", strtotime($reserviation->from_date))) {
                $reserviation->delete();
                $data = [
                    'message' => 'قام '. $user->name .' بإلغاء حجز السيارة من نوع '. $car->type,
                    'mobile' => $branch->branch_mobile
                ];
                Mobily::send($user->mobile, $data['message']);
                return response()->json([
                    'status' => true,
                    'message' => __('general.success-cancel-reserviations')
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => __('general.fail-cancel-reserviations')
                ]);
            }
        }
    }
}
