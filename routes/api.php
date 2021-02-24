<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['namespace' => 'API'], function() {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['namespace' => 'Customer'], function () {
            Route::get('customers/{customer_id}/total_receivables/{lot_id?}', 'CustomerController@totalReceivable');
        });
    }); 
});

Route::group(['namespace' => 'User'], function () {
    Route::post('login', 'AuthController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user'); 
        Route::post('setting', 'AuthController@setting');
        Route::get('logout', 'AuthController@logout');
    }); 
});

Route::group(['namespace' => 'Cluster'], function () {
    Route::get('clusters/{id?}', ['as' => 'get.cluster', 'uses' => 'ClusterController@get']);
    Route::get('lots/{id?}', ['as' => 'get.lot', 'uses' => 'LotController@get']);
});

Route::group(['namespace' => 'Project'], function () {
    Route::get('development_progress/{id?}', ['as' => 'get.development.progress', 'uses' => 'DevelopmentProgressController@get']);
});
Route::get('banner', function(){
    $banner = [
        [
            'id' => '1',
            'name' => 'Perumahan nyaman',
            'image' => 'http://157.230.250.8/banner/1.jpg'
        ],
        [
            'id' => '2',
            'name' => 'Perumahan nyaman',
            'image' => 'http://157.230.250.8/banner/2.jpg'
        ],
        [
            'id' => '3',
            'name' => 'Perumahan nyaman',
            'image' => 'http://157.230.250.8/banner/3.jpg'
        ],
        [
            'id' => '4',
            'name' => 'Perumahan nyaman',
            'image' => 'http://157.230.250.8/banner/4.jpg'
        ],
        [
            'id' => '5',
            'name' => 'Perumahan nyaman',
            'image' => 'http://157.230.250.8/banner/5.jpg'
        ],
               [
            'id' => '6',
            'name' => 'Perumahan nyaman',
            'image' => 'http://157.230.250.8/banner/6.jpg'
        ], 
    ];
    return response()->json($banner);
});