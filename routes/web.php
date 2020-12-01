<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login', 'Auth\LoginController@index');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout');

Route::group([
    'middleware' => ['auth.primary']
], function () {
	Route::get('/', 'Dashboard\DashboardController@index');

	Route::get('/dashboard/{type}', 'ASD@asd');

	Route::get('/customers', 'Customer\CustomerController@index');
	Route::post('/customers', 'Customer\CustomerController@insertData');
	Route::post('/customer-datatables', 'Customer\CustomerController@datatables');

	Route::get('/customer-terms', function () {
	    return abort(404);
	});
	// Route::get('/customer-terms', 'Customer\CustomerTermController@index');
	Route::post('/customer-terms', 'Customer\CustomerTermController@insertData');
	Route::post('/customer-term-datatables', 'Customer\CustomerTermController@datatables');

	Route::get('/customer-costs', function () {
	    return abort(404);
	});
	// Route::get('/customer-costs', 'Customer\CustomerCostController@index');
	Route::post('/customer-costs', 'Customer\CustomerCostController@insertData');
	Route::post('/customer-cost-datatables', 'Customer\CustomerCostController@datatables');

	Route::get('/development-progress', function () {
	    return abort(404);
	});

	// Route::get('/development-progress', 'Customer\CustomerLotProgressController@index');
	Route::post('/development-progress', 'Customer\CustomerLotProgressController@insertData');
	Route::post('/development-progres-datatables', 'Customer\CustomerLotProgressController@datatables');

	Route::get('/clusters', 'Cluster\ClusterController@index');
	Route::post('/clusters', 'Cluster\ClusterController@insertData');
	Route::post('/cluster-datatables', 'Cluster\ClusterController@datatables');

	Route::get('/lots', 'Cluster\LotController@index');
	Route::post('/lots', 'Cluster\LotController@insertData');
	Route::post('/lot-datatables', 'Cluster\LotController@datatables');

	Route::get('/booking-page', 'Customer\CustomerLotController@index');
	Route::get('/bookings/{lot_id?}', 'Customer\CustomerLotController@create');
	Route::post('/bookings/{lot_id?}', 'Customer\CustomerLotController@insertData');
	Route::post('/booking-datatables', 'Customer\CustomerLotController@datatables');
	Route::get('/bookings/detail/{id}', 'Customer\CustomerLotController@detail');

	Route::get('/city_by_province/{province_id}', ['as' => 'show.city', 'uses' => 'Ref\CityController@cityByProvince']);

	Route::get('/ref/term_purchasing_customers', ['as' => 'ref.term_purchasing_customers', 'uses' => 'Ref\RefTermPurchasingCustomerController@get']);
});