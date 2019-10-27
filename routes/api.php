<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'localization'], function () {
    Route::get('get-cities', function () {
        return response()->json([
            'status' => true,
            'items' => \App\Models\City::where('is_active', '=', 1)->pluck('name', 'id')->toArray(),
        ]);
    });
    Route::get('set-language/{lang}', 'UserController@setLocal');
    /**
     * user , auth api routes
     */
    Route::post('user', 'UserController@createUser');
    Route::post('login', 'UserController@login');
    Route::post('send-activation-code', 'UserController@resendActivationCode');
    Route::get('brands', function () {
        $brands = \App\Models\Brand::pluck('name')->toArray();
        return response()->json([
            'items' => $brands,
            'status' => true,
            'message' => 'done'
        ]);
    });
    Route::get('car-type/{brand_id}', function ($brand_id) {
        $cars = \App\Models\CarType::where('brand_id', '=', $brand_id)
            ->get();

            return response()->json([
                'items' => $cars,
                'status' => true,
                'message' => 'dd'
            ]);
    });

    Route::get('get-locations/{city_id}', function ($city_id) {
        $locations = \App\Models\Locations::where('city_id', '=', $city_id)->get();
        return response()->json([
            'items' => $locations,
            'status' => true,
            'message' => 'dd'
        ]);
    });
    Route::get('features', function () {
        $features = \App\Models\Features::pluck('name', 'id')->toArray();

        return $features;
    });
    Route::get('cities', function () {
        $cities = \App\Models\City::pluck('name', 'id')->all();
        return $cities;
    });
    Route::group(['middleware' => ['auth:api'] ,'prefix' => 'user'], function (){
        Route::post('update-profile', 'UserController@updateProfile');
        Route::get('info', 'UserController@getUserInfo');
        Route::post('add-documents', 'UserController@saveDocuments');
        Route::get('reserviations', 'UserController@reserviations');
        Route::post('payment-type', 'UserController@setUserPayment');
        Route::get('provider-rates', 'UserController@getProviderRates');
        Route::post('rate-provider', 'UserController@rateProvider');
        Route::post('update', 'UserController@update');
    });

    Route::group(['middleware' => ['auth:api'], 'prefix' => 'reservations'], function (){
        Route::post('search', 'ReservationController@search');
        Route::post('car', 'ReservationController@reserveCar');
        Route::post('cancel', 'ReservationController@cancelReservation');
    });
    /**
     * provider api routes
     */
    Route::post('provider', 'ProviderController@newProvider');
    Route::post('provider-login', 'ProviderController@login');

    Route::group(['middleware' => 'auth:api', 'prefix' => 'provider'], function () {
        Route::post('add-documents', 'ProviderController@addDocuments');
        Route::get('info', 'ProviderController@getInfo');
        Route::put('update-profile', 'ProviderController@updateProfile');
        Route::post('standerds', 'ProviderController@storeStanderds');
        Route::put('standerds' , 'ProviderController@updateStanderds');
        Route::post('branch', 'ProviderController@newBranch');
        Route::put('branch', 'ProviderController@updateBranch');
        Route::post('car', 'ProviderController@newCar');
        Route::put('car', 'ProviderController@updateCar');
    });
});
