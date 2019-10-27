<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Provider;
use Auth;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'commercial_no' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     *
     * @return property guard use for login
     *
     */
    public function guard()
    {
        return Auth::guard('providers');
    }

    /**
     * function to login 
     * 
     * @param  Request $request
     * 
     * @return  view
     */
    public function login(Request $request)
    {
        $credentials = $request->only('commercial_no', 'password');
        $rules = [
            'commercial_no' => 'required|exists:providers',
            'password' => 'required|min:6'
        ];

        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        if (Auth::attempt($credentials)) {
            return redirect('/');
        }
    } 
}
