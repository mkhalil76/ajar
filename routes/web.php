<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', function () {
	return view('auth.register');
});
Auth::routes();

Route::get('/', function () {
	return view('auth.login');
});
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});
Route::get('get-cities', function () {
	$cities = \App\Models\City::all();
	return response()->json([
		'cities' => $cities
	]);
})->name('get-cities');

Route::post('/login', 'Auth\LoginController@login');

Route::get('/add-documents/{provider_id}', [
	'as'=>'add.documents',
	'uses' => 'ProviderController@addDocuments'
]);

Route::get('/add/standerds/{provider_id}', [
	'as' => 'add.standerds',
	'uses' => 'ProviderController@addStanderds'
]);

Route::post('/add/standerds', [
	'as' => 'providers.add-standerds',
	'uses' => 'ProviderController@storeStanderds'
]);

Route::get('/add/payment-type/{provider_id}', [
	'as' => 'payments-type.add',
	'uses' => 'ProviderController@addPaymentType'
]);

Route::get('/admin/home',[
	'as' => 'admin.home',
	'uses' => 'AdminController@home'
]);
Route::post('/providers/add-payment-type',[
	'as' => 'providers.add-payment-type',
	'uses' => 'ProviderController@storePaymentType'
]);
Route::post('/providers/upload-documents',[
	'as' => 'providers.upload-documents',
	'uses' => 'ProviderController@uploadDocuments'
]);

Route::get('/add-branche/{provider_id}',[
	'as' => 'add.branche',
	'uses' => 'ProviderController@addBranch'
]);

Route::post('/providers/add-branch', [
	'as' => 'providers.add-branch',
	'uses' => 'ProviderController@postBranch'
]);

Route::middleware(['auth', 'hasdocuments', 'hasbranch', 'standards', 'payments', 'isAdmin'])->group(function () {
	Route::get('/','HomeController@index');
	Route::get('cars', 'ProviderController@showCars');
	Route::get('/new-car', 'ProviderController@newCar');
	Route::post('/providers/add-car', [
		'as' => 'providers.add-car',
		'uses' => 'ProviderController@addCar'
	]);
	Route::post('/providers/update-provider', [
		'as' => 'providers.update-provider',
		'uses' => 'ProviderController@updateDetails'
	]);
	Route::post('/provider/add-rate/', [
		'as' => 'providers.add-rate',
		'uses' => 'ProviderController@addRate'
	]);
	Route::get('car/show/{id}', [
		'as' => 'car.show',
		'uses' => 'ProviderController@showCar'
	]);
	Route::get('/provider/details', 'ProviderController@details');
	Route::post('car/edit', [
		'as' => 'car.edit',
		'uses' => 'ProviderController@editCar'
	]);
	Route::get('car/delete/{id}', [
		'as' => 'car.delete',
		'uses' => 'ProviderController@deleteCar'
	]);
	Route::post('providers/update-documents', [
		'as' => 'providers.update-documents',
		'uses' => 'ProviderController@updateDocuments'
	]);
	Route::get('branchs', 'ProviderController@showBranches');

	Route::prefix('reservations')->group(function () {
		Route::get('/', 'ReservationsController@index');
		Route::get('/confirm-reserviation/{reserviation_id}', 'ReservationsController@confirmReserviation');
		Route::get('/reject-reserviation/{reserviation_id}', 'ReservationsController@rejectReserviation');
		Route::get('delivery', 'ReservationsController@deliveryCar');
		Route::get('/submit-delivery/{reserviation_id}', 'ReservationsController@confirmDelivery');
		Route::get('/receipt', 'ReservationsController@receiptCar');
		Route::get('/submit-receipt/{reserviation_id}', 'ReservationsController@confirmReceiptCar');
	});

	Route::prefix('branchs')->group(function () {
		Route::get('/', 'ProviderController@showBranches');
		Route::get('/new/branch', 'ProviderController@newBranch');
		Route::post('/add-branch', 'ProviderController@postNewBranch');
		Route::get('/edit/{provider_id}', 'ProviderController@showEditBranch');
		Route::post('/update-branch', 'ProviderController@updateBranch');
		Route::post('/update-branch', 'ProviderController@updateBranch');
		Route::get('/delete/{branch_id}', 'ProviderController@deleteBranch');
	});

	Route::prefix('user')->group(function () {
		Route::get('show/{id}', 'HomeController@showUser');
	});
});

Route::prefix('admin')->group(function(){
    Route::get('/locations', 'AdminController@loccations');
    Route::get('/cities', 'AdminController@cities');
    Route::get('/new-city', 'AdminController@newCity');
    Route::post('/new-car', 'AdminController@postCity');
    Route::get('/city-edit/{id}', 'AdminController@editCity');
    Route::post('city-update', 'AdminController@postEditCity');
    Route::get('city-delete/{id}', 'AdminController@deleteCity');
    Route::get('new-location', 'AdminController@newLocation');
    Route::post('new-location', 'AdminController@postNewLocation');
    Route::get('/location-edit/{id}', 'AdminController@locationEdit');
    Route::post('/update-location', 'AdminController@updateLocation');
    Route::get('/location-delete/{id}', 'AdminController@locationDelete');
});
