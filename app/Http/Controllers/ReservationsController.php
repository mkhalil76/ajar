<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Auth;
use App\Models\Car;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mobily;
use App\Models\User;
use Session;
use App\Models\rateProvider;
use DB;

class ReservationsController extends Controller
{
    public function index(Request $request)
    {
        $provider = Auth::user();
        $provider_cars = Car::where('provider_id', '=', $provider->id)->pluck('id')->toArray();

        $reservations = Reservation::whereIn('car_id', $provider_cars)->get();

        return view('providers.reservations.index')->with(['reservations' => $reservations]);
    }

    /**
     * function to confirm car reserviation
     *
     * @param int $reserviation_id
     *
     *
     * @return view
     */
    public function confirmReserviation($reserviation_id)
    {
        try {
            $reserviation = Reservation::findOrFail($reserviation_id);
            $reserviation->status = 2;
            $reserviation->save();

            $user = User::findOrFail($reserviation->user_id);
            // send sms to the user
            $car = Car::findOrFail($reserviation->car_id);
            $data = [
                'message' => ' تم حجز السيارة من نوع ' . $car->type . '  موديل  ' . $car->model . ' من تاريخ  ' . date("Y-m-d", strtotime($reserviation->from_date)) . '  الى تاريخ ' . date("Y-m-d", strtotime($reserviation->to_date)). ' من فرع ' . $car->branch->city . ' شركة '. $car->branch->provider->office_name,
                'mobile' => $user->mobile
            ];
            Mobily::send($user->mobile, $data['message']);
            Session::flash('success', 'تم تأكيد حجز السيارة بنجاح');

            return redirect()->back();
        } catch (ModelNotFoundException $e) {

        }
    }

    /**
     * function to reject user reserviation
     *
     * @param $reserviation_it
     *
     * @return view
     *
     */
    public function rejectReserviation($reservation_id)
    {
        try {
            $reserviation = Reservation::where('id', '=', $reservation_id)->first();


            $user = User::findOrFail($reserviation->user_id);
            // send sms to the user
            $car = Car::findOrFail($reserviation->car_id);
            $car->status = 0;
            $car->save();
            Reservation::where('id', '=', $reservation_id)->delete();
            $data = [
                'message' => ' تم رفض حجز السيارة من نوع ' . $car->type,
                'mobile' => $user->mobile
            ];
            Mobily::send($user->mobile, $data['message']);
            Session::flash('success', 'تم رفض حجز السيارة بنجاح');
            return redirect()->back();
        } catch (ModelNotFoundException $e) {

        }

    }

    /**
     * function to show search for reserviation car
     *
     * @param Request $request
     *
     * @return view
     */
    public function deliveryCar(Request $request)
    {
        $provider = Auth::user();
        $provider_cars = Car::where('provider_id', '=', $provider->id)->pluck('id')->toArray();

        $reservations = Reservation::whereIn('car_id', $provider_cars)->where('status', '=', 2)->get();

        return view('providers.reservations.delivery')->with(['reservations' => $reservations]);
    }


    /**
     * function to show search for reserviation car
     *
     * @param Request $request
     *
     * @return view
     */
    public function receiptCar(Request $request)
    {
        $provider = Auth::user();
        $provider_cars = Car::where('provider_id', '=', $provider->id)->pluck('id')->toArray();

        $reservations = Reservation::whereIn('car_id', $provider_cars)->where('status', '=', 3)->get();

        return view('providers.reservations.receipt')->with(['reservations' => $reservations]);
    }

    /**
     * function to confirm user car delivary
     *
     * @param int $reserviation_id
     *
     * @return view
     */
    public function confirmDelivery($reserviation_id)
    {
        try {
            $reserviation = Reservation::findOrFail($reserviation_id);
            $reserviation->status = 3;
            $reserviation->save();
            Session::flash('success', 'تم تسليم السيارة بنجاح');
            return redirect()->back();
        } catch (ModelNotFoundException $e) {

        }
    }


    /**
     * function to recive  user car
     *
     * @param int $reserviation_id
     *
     * @return view
     */
    public function confirmReceiptCar($reserviation_id)
    {
        try {
            $reserviation = Reservation::findOrFail($reserviation_id);
            $provider_id = $reserviation->provider_id;
            $user_id = $reserviation->user_id;

            $reserviation->delete();

            $rate = new rateProvider;
            $rate->provider_id = $provider_id;
            $rate->user_id = $user_id;
            $rate->save();

            Session::flash('success', 'تم إستلام السيارة بنجاح');
            return redirect()->back();
        } catch (ModelNotFoundException $e) {

        }
    }
}
