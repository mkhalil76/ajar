<?php

namespace App\Http\Controllers\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use Request as HttpRequest;
use App\Models\UserDocument;
use App\Models\Reservation;
use App\Models\Payment;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use App\Models\providerRating;
use DB;
use App\Models\rateProvider;
use abdullahobaid\mobilywslaraval\Mobily;

class UserController extends Controller
{
    private $user;

    private $serviceAccount;
    private $firebase;

    function __construct(User $user)
    {
        config([
            'auth.defaults.guard' => 'api'
        ]);

        $this->serviceAccount = ServiceAccount::fromJsonFile(public_path('ajar-4af21-dfd0be8715ff.json'));

        $this->firebase = (new Factory)
            ->withServiceAccount($this->serviceAccount)
            ->withDatabaseUri('https://ajar-4af21.firebaseio.com/')
            ->create();
        $this->user = $user;
    }

    /**
     * function to create new application user
     *
     * @param Request $request
     *
     * @return Illuminate/Http/Response
     *
     */
    public function createUser(Request $request)
    {
        HttpRequest::merge(['mobile' => $this->formatMobileNumber($request->get('mobile'))]);
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->user->new_user_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => __('auth.failed_register')
            ]);
        }


        $activiation_code = $this->generateActivationCode(6);
        $data['password'] = bcrypt($activiation_code);
        $data['activation_code'] = $activiation_code;
        $user = new User;
        $user = $user->create($data);
        $token = JWTAuth::fromUser($user);
        $saved = $user->save();

        Mobily::send($data['mobile'], __('auth.activation_code').$activiation_code);

        $this->storeInFireBase($user);

        return response()->json([
            'items' => $user,
            'token' => $token,
            'status' => true,
            'message' => __('general.create-user')
        ]);
    }


    /**
     * function to authinticat user
     *
     * @param Request $request
     *
     * @return response
     */
    public function login(Request $request)
    {
        HttpRequest::merge(['mobile' => $this->formatMobileNumber($request->get('mobile'))]);

        $validator = $this->makeValidation($request, [
            'mobile' => 'required|exists:users,mobile',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => __('general.error_msg')
            ]);
        }
        $credentials = $request->only('mobile', 'password');
        $user = User::where('mobile', '=', $request->mobile)
            ->where('activation_code', '=', $request->password)->first();
        if (!empty($user)) {
            $token =JWTAuth::fromUser($user);
            return response()->json([
                'items' => $user,
                'token' => $token,
                'status' => true,
                'message' => __('auth.login')
            ]);
        } else {
            return response()->json([
                'error' => 'email or password are invalid',
                'status' => 'false',
                'message' => __('general.error_msg')
            ], 401);
        }
    }

    /**
     * function to get user information
     *
     * @param Request $request
     *
     * @return response
     *
     */
    public function getUserInfo(Request $request)
    {
        $user_id = Auth::user()->id;
        try {
            $user = User::with(['documents', 'payment', 'reservation'])
                ->where('id', '=', $user_id)
                ->first();

            return response()->json([
                'items' => $user,
                'status' => true,
                'message' => __('general.fetch-data')
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.error_msg')
            ]);
        }
    }

    /**
     * function to update user profile info
     *
     * @param Request $request
     *
     * @return response
     *
     */
    public function updateProfile(Request $request)
    {
        $mobile = $request->get('mobile');

        if (isset($mobile)) {
            HttpRequest::merge(['mobile' => $this->formatMobileNumber($request->get('mobile'))]);
        }
        $user_id = Auth::user()->id;
        $data = $request->all();

        $validator = $this->makeValidation($request, $this->user->update_rules($user_id));
        if (!empty($data['profile_pic'])) {
            $data['profile_pic'] = $this->upload($request, 'profile_pic');
        }
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => __('general.error_msg')
            ]);
        }

        try {
            $user = User::findOrFail($user_id);
            $user = $user->fill($data);
            $saved = $user->save();
            return response()->json([
                'items' => $user,
                'status' => true,
                'message' => __('general.update-info')
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.error_msg')
            ]);
        }
    }

    /**
     * function to fetch validation errors
     *
     * @param validator
     *
     * @return array
     */
    public function validateService($validator)
    {
        $arr = array();
        $errors = [];
        $messages = $validator->errors()->toArray();
        foreach ($messages as $key => $row) {
            $errors['fieldname'] = $key;
            $errors['message'] = $row[0];
            $arr[] = $errors;
        }

        return response()->json([
            'sucess' => false,
            'message' => $arr,
            'status' => false
        ]);
    }

    /**
     * function to save user required documents
     *
     * @param Request $request
     *
     * @return response
     *
     */
    public function saveDocuments(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->user->document_store_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => __('general.error_msg')
            ]);
        }

        $user_id = Auth::user()->id;
        $data['user_id'] = $user_id;

        $data['national_id_image'] = $this->upload($request, 'national_id_image');
        $data['driving_license_image'] = $this->upload($request, 'driving_license_image');
        $data['job_card_image'] = $this->upload($request, 'job_card_image');
        $user_document = new \App\Models\UserDocument;
        $user_document = $user_document->create($data);
        $saved = $user_document->save();

        return response()->json([
            'items' => $user_document,
            'status' => true,
            'message' => __('general.save-documents')
        ]);
    }

    /**
     * function to get list of reserviations for user
     *
     * @param Request $request
     *
     * @return response
     */
    public function reserviations(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $reserviations = Reservation::where('user_id', '=', Auth::user()->id)->get();
        return response()->json([
            'items' => $reserviations,
            'status' => true,
            'message' => __('general.fetch-data')
        ]);
    }

    /**
     * function to set application language
     *
     * @param  string $lang
     *
     * @return  response
     */
    public function setLocal($lang)
    {
        config(['app.locale' => $lang]);
        return response()->json([
            'status' => true,
            'message' => __('general.set-language'),
        ]);
    }

    /**
     * function to set user payment method
     *
     * @param  Request $request
     *
     * @return  response
     */
    public function setUserPayment(Request $request)
    {
        $user = Auth::user();

        if ($user->payment()->exists()) {
            $payment = Payment::where('user_id', '=', $user->id)->first();
            return response()->json([
                'status' => true,
                'message' => 'exist',
                'items' => $payment
            ]);
        } else {
            $payment = new Payment;
            $payment->user_id = $user->id;
            $payment->payment_type = $request->payment_type;
            $payment->save();

            return response()->json([
                'status' => true,
                'message' => 'تم إضافة الية الدفع بنجاح',
                'items' => $payment
            ]);
        }
    }

    /**
     * function to store new user info in firebase
     *
     * @param User $user
     *
     */
    public function storeInFireBase(User $user)
    {
        $database = $this->firebase->getDatabase();
        $newMsg = $database
            ->getReference('users/'.$user->national_id)
            ->set($user->national_id);
    }

    /**
     * function to post rate provider
     *
     * @param Request $request
     *
     * @return response
     *
     */
    public function rateProvider(Request $request)
    {
        $user = Auth::user();


        $rate = DB::table('rate_providers')->where('id', '=', $request->rate_id)->update([
            'car_clean' => $request->car_clean,
            'staff_treated' => $request->staff_treated,
            'fast_receipt_delivery' => $request->fast_receipt_delivery,
            'customer_experience' => $request->customer_experience,
            'status' => 1
        ]);
        return response()->json([
            'status' => true,
            'message' => 'تم إضافة تقيمك بنجاح'
        ]);
    }

    /**
     * function to get provider rates for user
     *
     * @param Request $request
     *
     * @return response
     *
     */
    public function getProviderRates(Request $request )
    {
        $user = Auth::user();

        $rates = rateProvider::where('status', '=', 0)->where('user_id', '=', $user->id)->get();
        return response()->json([
            'status' => true,
            'message' => __('general.fetch-data'),
            'items' => $rates
        ]);
    }

    /**
     * function to resend the user activation code
     *
     * @param Request $request
     *
     *
     * @return response
     */
    public function resendActivationCode(Request $request)
    {
        $mobile = $request->get('mobile');
        if (isset($mobile)) {
            HttpRequest::merge(['mobile' => $this->formatMobileNumber($request->get('mobile'))]);
        }

        $user = User::where('mobile', '=', $request->mobile)->first();
        if (!empty($user)) {
            $activation_code = $this->generateActivationCode(6);
            $user->activation_code = $activation_code;
            $user->save();

            Mobily::send($request->mobile, __('auth.activation_code').$activation_code);

            return response()->json([
                'status' => true,
                'message' => __('auth.resend_activation_code')
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    /**
     * function to update user info
     *
     * @param Request $request
     *
     *
     * @return response
     *
     */
    public function update(Request $request)
    {
        $data = $request->all();
        dd($data);
    }
}
