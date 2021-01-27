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
Route::group(['namespace' => 'User'], function () {
    Route::post('login', 'AuthController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user'); 
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
            'image' => 'https://nusawiyasapropertindo.com/wp-content/uploads/2020/06/6-3-1024x1024.png'
        ],[
            'id' => '2',
            'name' => 'Peruman keluarga',
            'image' => 'https://nusawiyasapropertindo.com/wp-content/uploads/2020/06/6-2-1024x1024.png'
        ]
        ];
    return response()->json($banner);
});