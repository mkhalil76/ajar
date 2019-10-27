<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use Auth;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Reservation;
use App\Models\Provider;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $provider = Auth::user();

        $car_count = Car::where('provider_id', '=', $provider->id)->count();
        $branch_count = Branch::where('provider_id', '=', $provider->id)->count();
        return view('home')->with([
            'car_count' => $car_count,
            'branch_count' => $branch_count,
        ]);
    }

    /**
     * function to show user profile details
     * 
     * @param int $id
     * 
     * @return view
     */
    public function showUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('user.show')->with(['user' => $user]);
        } catch (ModelNotFoundException $e) {
            echo $e;
        }    
    }
}
