<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use File;
use Illuminate\Support\Facades\Validator;
use App\Models\Car;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mobily;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * function to upload file
     *
     * @param Request $request , $file_name
     */
    public function upload($request, $input_name)
    {
        $temp = time() . rand(5, 50);
        $ext = $request->file($input_name)->getClientOriginalExtension();
        $new_file_name = $temp . '.' . $ext;
        $path = public_path().'/public/assets/upload';
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $uploaded = $request->file($input_name)->move($path, $new_file_name);

        if ($uploaded)
            return $new_file_name;
        return '';
    }

    /**
     * function to format phone number
     *
     * @param  String $mobile_number
     *
     * @return  string
     */
    public function formatMobileNumber($mobile_number)
    {
        if ($mobile_number[0] == "+") {
            $mobile_number = str_replace($mobile_number[0], "00", $mobile_number);
        }
        return $mobile_number;
    }

    /**
     * function to make request validation
     *
     * @param Request $request , array $rules
     *
     * @return boolean
     */
    protected function makeValidation ($request, $rules)
    {
        return Validator::make($request->all(),$rules);
    }

    /**
     * function to update car status
     *
     * @param int $car_id
     *
     * @return boolean
     */
    public function updateCarStatus($car_id)
    {
        try {
            $car = Car::findOrFail($car_id);
            $car->status = 1;
            $car->save();
            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    /**
     * function to send sms to user
     *
     * @param array $data
     */
    public function sendSMS($data)
    {
        Mobily::send($data['mobile'], $data['message']);
    }

    /**
     * function to generate activation code
     *
     * @param Integer Length
     *
     */
    public function generateActivationCode($digits = 6)
    {
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while ($i < $digits) {
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }
}
