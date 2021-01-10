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
	Route::get('/customers/{id}', 'Customer\CustomerController@detail')->name('customer.detail');
	Route::get('/customers/{id}/delete', 'Customer\CustomerController@delete')->name('customer.delete');

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
	Route::get('/clusters/{id}', 'Cluster\ClusterController@detail')->name('cluster.detail');
	Route::get('/clusters/{id}/delete', 'Cluster\ClusterController@delete')->name('cluster.delete');

	Route::get('/lots', 'Cluster\LotController@index');
	Route::post('/lots', 'Cluster\LotController@insertData');
	Route::post('/lot-datatables', 'Cluster\LotController@datatables');
	Route::get('/get_lots', 'Cluster\LotController@get');
	Route::get('/lots/{id}', 'Cluster\LotController@detail');
	Route::get('/lots/{id}/delete', 'Cluster\LotController@delete');

	Route::get('/booking-page', 'Customer\CustomerLotController@index');
	Route::get('/bookings/{lot_id?}', 'Customer\CustomerLotController@create');
	Route::post('/bookings/{lot_id?}', 'Customer\CustomerLotController@insertData');
	Route::post('/booking-datatables', 'Customer\CustomerLotController@datatables');
	Route::get('/bookings/detail/{id}', 'Customer\CustomerLotController@detail');

	Route::get('/city_by_province/{province_id}', ['as' => 'show.city', 'uses' => 'Ref\CityController@cityByProvince']);

	Route::get('/ref/term_purchasing_customers', ['as' => 'ref.term_purchasing_customers', 'uses' => 'Ref\RefTermPurchasingCustomerController@get']);

	Route::get('/spk-project', 'Project\SPKProjectController@index');
	Route::post('/spk-project', 'Project\SPKProjectController@insertData');
	Route::post('/spk-project-datatables', 'Project\SPKProjectController@datatables');

	Route::get('/customer-confirmation', 'Project\CustomerConfirmationController@index');
	Route::get('/customer-confirmation/detail/{id}', 'Project\CustomerConfirmationController@detail');
	Route::post('/customer-confirmation', 'Project\CustomerConfirmationController@insertData');
	Route::post('/customer-confirmation-datatables', 'Project\CustomerConfirmationController@datatables');

	Route::get('/work-agreement', 'Project\WorkAgreementController@index');
	Route::post('/work-agreement', 'Project\WorkAgreementController@insertData');
	Route::post('/work-agreement-datatables', 'Project\WorkAgreementController@datatables');

	Route::get('/rap', 'Project\RAPController@index');
	Route::get('/create-rap', 'Project\RAPController@create');
	Route::post('/rap', 'Project\RAPController@insertData');
	Route::post('/rap-datatables', 'Project\RAPController@datatables');

	Route::get('/request-material', 'Project\RequestMaterialController@index');
	Route::get('/create-request-material', 'Project\RequestMaterialController@create');
	Route::post('/request-material', 'Project\RequestMaterialController@insertData');
	Route::post('/request-material-datatables', 'Project\RequestMaterialController@datatables');
	Route::get('/request_materials/{id?}', 'Project\RequestMaterialController@get');

	Route::get('/development-progress', 'Project\DevelopmentProgressController@index');
	Route::get('/development-progress/detail/{id}', 'Project\DevelopmentProgressController@detail');
	Route::get('/create-development-progress', 'Project\DevelopmentProgressController@create');
	Route::post('/development-progress', 'Project\DevelopmentProgressController@insertData');
	Route::post('/development-progress-datatables', 'Project\DevelopmentProgressController@datatables');

	Route::get('/inventory', 'Inventory\InventoryController@index');
	Route::post('/inventory', 'Inventory\InventoryController@insertData');
	Route::post('/inventory-datatables', 'Inventory\InventoryController@datatables');
	Route::get('/inventories/{id?}', 'Inventory\InventoryController@get');

	Route::get('/inventory-history', 'Inventory\InventoryHistoryController@index');
	Route::post('/inventory-history-datatables', 'Inventory\InventoryHistoryController@datatables');

	Route::get('/inventory-category', 'Inventory\InventoryCategoryController@index');
	Route::post('/inventory-category', 'Inventory\InventoryCategoryController@insertData');
	Route::post('/inventory-category-datatables', 'Inventory\InventoryCategoryController@datatables');

	Route::get('/delivery-order', 'Inventory\DeliveryOrderController@index');
	Route::get('/create-delivery-order', 'Inventory\DeliveryOrderController@create');
	Route::post('/delivery-order', 'Inventory\DeliveryOrderController@insertData');
	Route::post('/delivery-order-datatables', 'Inventory\DeliveryOrderController@datatables');

	Route::get('/unit', 'Inventory\UnitController@index');
	Route::post('/unit', 'Inventory\UnitController@insertData');
	Route::post('/unit-datatables', 'Inventory\UnitController@datatables');

	Route::get('/supplier', 'Inventory\SupplierController@index');
	Route::post('/supplier', 'Inventory\SupplierController@insertData');
	Route::post('/supplier-datatables', 'Inventory\SupplierController@datatables');

	Route::get('/purchase-order', 'Purchasing\PurchaseOrderController@index');
	Route::get('/create-purchase-order', 'Purchasing\PurchaseOrderController@create');
	Route::post('/purchase-order', 'Purchasing\PurchaseOrderController@insertData');
	Route::post('/purchase-order-datatables', 'Purchasing\PurchaseOrderController@datatables');
	Route::get('/purchase_orders/{id?}', 'Purchasing\PurchaseOrderController@get');

	Route::get('/receipt-of-goods', 'Inventory\ReceiptOfGoodsController@index');
	Route::get('/create-receipt-of-goods', 'Inventory\ReceiptOfGoodsController@create');
	Route::post('/receipt-of-goods', 'Inventory\ReceiptOfGoodsController@insertData');
	Route::post('/receipt-of-goods-datatables', 'Inventory\ReceiptOfGoodsController@datatables');

	Route::get('/receipt-of-goods-request', 'Inventory\ReceiptOfGoodsRequestController@index');
	Route::get('/create-receipt-of-goods-request', 'Inventory\ReceiptOfGoodsRequestController@create');
	Route::post('/receipt-of-goods-request', 'Inventory\ReceiptOfGoodsRequestController@insertData');
	Route::post('/receipt-of-goods-request-datatables', 'Inventory\ReceiptOfGoodsRequestController@datatables');

	Route::get('/report-inventory-purchase', 'Report\InventoryPurchaseController@index');
	Route::post('/report-inventory-purchase', 'Report\InventoryPurchaseController@insertData');
	Route::post('/report-inventory-purchase-datatables', 'Report\InventoryPurchaseController@datatables');

	Route::get('/report-outstanding-po', 'Report\OutstandingPOController@index');
	Route::post('/report-outstanding-po', 'Report\OutstandingPOController@insertData');
	Route::post('/report-outstanding-po-datatables', 'Report\OutstandingPOController@datatables');

	Route::get('/user', 'User\UserController@index');
	Route::post('/user', 'User\UserController@insertData');
	Route::post('/user-datatables', 'User\UserController@datatables');
});