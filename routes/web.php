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

	// Route::get('/customer-terms', 'Customer\CustomerTermController@index');
	Route::post('/customer-terms', 'Customer\CustomerTermController@insertData');
	Route::post('/customer-term-datatables', 'Customer\CustomerTermController@datatables');

	Route::get('/customer-payments', 'Customer\CustomerPaymentController@index');
	Route::get('/bookings/{id}/payments', 'Customer\CustomerPaymentController@detail');
	Route::post('/bookings/{id}/payments', 'Customer\CustomerPaymentController@insertData');
	Route::post('/bookings/payments/{id}', 'Customer\CustomerPaymentController@update');
	Route::get('/bookings/payments/{id}/delete', 'Customer\CustomerPaymentController@delete');
	Route::get('/customer_payments/{id?}', 'Customer\CustomerPaymentController@get');
	Route::post('/customer-payment-datatables', 'Customer\CustomerPaymentController@datatables');

	Route::post('/customer-costs', 'Customer\CustomerCostController@insertData');
	Route::post('/customer-cost-datatables', 'Customer\CustomerCostController@datatables');

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

	Route::post('/lot-gallery', 'Cluster\LotGalleryController@insertData');
	Route::get('/lot_galleries', 'Cluster\LotGalleryController@get');

	Route::get('/booking-page', 'Customer\CustomerLotController@index');
	Route::get('/bookings/{lot_id?}', 'Customer\CustomerLotController@create');
	Route::post('/bookings/{lot_id?}', 'Customer\CustomerLotController@insertData');
	Route::delete('/bookings/{lot_id?}', 'Customer\CustomerLotController@delete');
	// Route::post('/bookings', 'Customer\CustomerLotController@insertData');
	Route::post('/booking-datatables', 'Customer\CustomerLotController@datatables');
	Route::get('/bookings/edit/{id}', 'Customer\CustomerLotController@edit');
	Route::get('/bookings/detail/{id}', 'Customer\CustomerLotController@detail');
	Route::get('/bookings/{id}/get', 'Customer\CustomerLotController@get');
	Route::get('/bookings/create', 'Customer\CustomerLotController@insertData');

	Route::get('/city_by_province/{province_id}', ['as' => 'show.city', 'uses' => 'Ref\CityController@cityByProvince']);

	Route::get('/ref/term_purchasing_customers', ['as' => 'ref.term_purchasing_customers', 'uses' => 'Ref\RefTermPurchasingCustomerController@get']);

	Route::get('/spk-project', 'Project\SPKProjectController@index');
	Route::get('/spk-project-pdf/{id}', 'Project\SPKProjectController@generatePdf');
	Route::post('/spk-project', 'Project\SPKProjectController@insertData');
	Route::post('/spk-project-datatables', 'Project\SPKProjectController@datatables');
	Route::get('/spk-project/{id}', 'Project\SPKProjectController@detail');
	Route::get('/spk-project/{id}/delete', 'Project\SPKProjectController@delete');
	Route::get('/spk_projects/{id?}', 'Project\SPKProjectController@get');

	Route::get('/spk-project/{spk_project_id}/additional', 'Project\SpkProjectAdditionalsController@index');
	Route::post('/spk-project/{spk_project_id}/additional', 'Project\SpkProjectAdditionalsController@insertData');
	Route::get('/spk-project/{spk_project_id}/additional/{id}/delete', 'Project\SpkProjectAdditionalsController@delete');

	Route::get('/customer-confirmation', 'Project\CustomerConfirmationController@index');
	Route::get('/customer-confirmation/detail/{id}', 'Project\CustomerConfirmationController@detail');
	Route::post('/customer-confirmation', 'Project\CustomerConfirmationController@insertData');
	Route::post('/customer-confirmation-datatables', 'Project\CustomerConfirmationController@datatables');

	Route::get('/work-agreement', 'Project\WorkAgreementController@index');
	Route::post('/work-agreement', 'Project\WorkAgreementController@insertData');
	Route::post('/work-agreement-datatables', 'Project\WorkAgreementController@datatables');
	Route::get('/work-agreement/{id}/delete', 'Project\WorkAgreementController@delete');
	Route::get('/work-agreement/{id}', 'Project\WorkAgreementController@get');

	Route::get('/work-agreement/{spk_worker_id}/additional', 'Project\WorkAgreementAdditionalsController@index');
	Route::post('/work-agreement/{spk_worker_id}/additional', 'Project\WorkAgreementAdditionalsController@insertData');
	Route::get('/work-agreement/{spk_worker_id}/additional/{id}/delete', 'Project\WorkAgreementAdditionalsController@delete');

	Route::get('/rap', 'Project\RAPController@index');
	Route::get('/create-rap', 'Project\RAPController@create');
	Route::get('/update-rap/{id?}', 'Project\RAPController@update');
	Route::post('/rap', 'Project\RAPController@insertData');
	Route::post('/rap-datatables', 'Project\RAPController@datatables');
	Route::get('/rap/{id}/delete', 'Project\RAPController@delete');

	Route::get('/rab', 'Project\RABController@index');
	Route::get('/create-rab', 'Project\RABController@create');
	Route::get('/update-rab/{id?}', 'Project\RABController@update');
	Route::post('/rab', 'Project\RABController@insertData');
	Route::post('/rab-datatables', 'Project\RABController@datatables');
	Route::get('/rab/{id}/delete', 'Project\RABController@delete');

	Route::get('/request-material', 'Project\RequestMaterialController@index');
	Route::get('/request-material-pdf/{id}', 'Project\RequestMaterialController@generatePdf');
	Route::get('/create-request-material', 'Project\RequestMaterialController@create');
	Route::post('/request-material', 'Project\RequestMaterialController@insertData');
	Route::post('/request-material-datatables', 'Project\RequestMaterialController@datatables');
	Route::get('/request_materials/{id?}', 'Project\RequestMaterialController@get');
	Route::get('/update-request-material/{id}', 'Project\RequestMaterialController@edit')->name('request_material.edit');
	Route::get('/request-material/{id}/delete', 'Project\RequestMaterialController@delete');

	Route::get('/development-progress', 'Project\DevelopmentProgressController@index');
	Route::get('/development-progress/detail/{id}', 'Project\DevelopmentProgressController@detail');
	Route::get('/create-development-progress', 'Project\DevelopmentProgressController@create');
	Route::post('/development-progress', 'Project\DevelopmentProgressController@insertData');
	Route::post('/development-progress-datatables', 'Project\DevelopmentProgressController@datatables');
	Route::get('/development-progress/pdf/{id}', 'Project\DevelopmentProgressController@pdf');

	Route::get('/inventory', 'Inventory\InventoryController@index');
	Route::post('/inventory', 'Inventory\InventoryController@insertData');
	Route::post('/inventory-datatables', 'Inventory\InventoryController@datatables');
	Route::get('/inventories/{id?}', 'Inventory\InventoryController@get');
	Route::get('/inventories/{id?}/delete', 'Inventory\InventoryController@delete');

	Route::get('/inventory-history', 'Inventory\InventoryHistoryController@index');
	Route::post('/inventory-history-datatables', 'Inventory\InventoryHistoryController@datatables');

	Route::get('/inventory-category', 'Inventory\InventoryCategoryController@index');
	Route::post('/inventory-category', 'Inventory\InventoryCategoryController@insertData');
	Route::post('/inventory-category-datatables', 'Inventory\InventoryCategoryController@datatables');

	Route::get('/delivery-order', 'Inventory\DeliveryOrderController@index');
	Route::get('/create-delivery-order', 'Inventory\DeliveryOrderController@create');
	Route::get('/update-delivery-order/{id:}', 'Inventory\DeliveryOrderController@edit')->name('delivery.edit');
	Route::post('/delivery-order', 'Inventory\DeliveryOrderController@insertData');
	Route::post('/delivery-order-datatables', 'Inventory\DeliveryOrderController@datatables');
	Route::get('/delivery-order/{id}/delete', 'Inventory\DeliveryOrderController@delete');
	Route::get('/delivery-order-pdf/{id}', 'Inventory\DeliveryOrderController@generatePdf');

	Route::get('/unit', 'Inventory\UnitController@index');
	Route::post('/unit', 'Inventory\UnitController@insertData');
	Route::post('/unit-datatables', 'Inventory\UnitController@datatables');
	Route::get('/unit/{id}', 'Inventory\UnitController@detail');
	Route::get('/unit/{id}/delete', 'Inventory\UnitController@delete');

	Route::get('/supplier', 'Inventory\SupplierController@index');
	Route::post('/supplier', 'Inventory\SupplierController@insertData');
	Route::post('/supplier-datatables', 'Inventory\SupplierController@datatables');
	Route::get('/supplier/{id}', 'Inventory\SupplierController@detail');
	Route::get('/supplier/{id}/delete', 'Inventory\SupplierController@delete');

	Route::get('/purchase-order', 'Purchasing\PurchaseOrderController@index');
	Route::get('/purchase-order-pdf/{id}', 'Purchasing\PurchaseOrderController@generatePdf');
	Route::get('/create-purchase-order', 'Purchasing\PurchaseOrderController@create');
	Route::post('/purchase-order', 'Purchasing\PurchaseOrderController@insertData');
	Route::post('/purchase-order-datatables', 'Purchasing\PurchaseOrderController@datatables');
	Route::get('/purchase_orders/{id?}', 'Purchasing\PurchaseOrderController@get');
	Route::get('/update-purchase-order/{id}', 'Purchasing\PurchaseOrderController@edit')->name('po.edit');
	Route::get('/purchase-order/{id}/delete', 'Purchasing\PurchaseOrderController@delete');

	Route::get('/receipt-of-goods', 'Inventory\ReceiptOfGoodsController@index');
	Route::get('/receipt-of-goods-pdf/{id}', 'Inventory\ReceiptOfGoodsController@generatePdf');
	Route::get('/create-receipt-of-goods', 'Inventory\ReceiptOfGoodsController@create');
	Route::post('/receipt-of-goods', 'Inventory\ReceiptOfGoodsController@insertData');
	Route::post('/receipt-of-goods-datatables', 'Inventory\ReceiptOfGoodsController@datatables');
	Route::get('/update-receipt-of-goods/{id}', 'Inventory\ReceiptOfGoodsController@edit')->name('receipt_a.edit');
	Route::get('/receipt-of-goods/{id}/delete', 'Inventory\ReceiptOfGoodsController@delete');

	Route::get('/receipt-of-goods-request', 'Inventory\ReceiptOfGoodsRequestController@index');
	Route::get('/create-receipt-of-goods-request', 'Inventory\ReceiptOfGoodsRequestController@create');
	Route::post('/receipt-of-goods-request', 'Inventory\ReceiptOfGoodsRequestController@insertData');
	Route::post('/receipt-of-goods-request-datatables', 'Inventory\ReceiptOfGoodsRequestController@datatables');
	Route::get('/update-receipt-of-goods-request/{id}', 'Inventory\ReceiptOfGoodsRequestController@edit')->name('receipt.edit');
	Route::get('/receipt-of-goods-request/{id}/delete', 'Inventory\ReceiptOfGoodsRequestController@delete');

    Route::get('/report-used-inventory', 'Report\UsedInventoryController@index');
    Route::post('/report-used-inventory-pdf', 'Report\UsedInventoryController@generatePdf')->name('report.used-inventory.pdf');
    Route::post('/report-used-inventory-datatables', 'Report\UsedInventoryController@datatables');

	Route::get('/report-inventory-purchase', 'Report\InventoryPurchaseController@index');
	Route::post('/report-inventory-purchase-pdf', 'Report\InventoryPurchaseController@generatePdf')->name('report.inventory-purchase.pdf');
	Route::post('/report-inventory-purchase-datatables', 'Report\InventoryPurchaseController@datatables');

	Route::get('/report-outstanding-po', 'Report\OutstandingPOController@index');
	Route::post('/report-outstanding-po-pdf', 'Report\OutstandingPOController@generatePdf')->name('report.outstanding.pdf');
    Route::post('/report-outstanding-po-datatables', 'Report\OutstandingPOController@datatables');

    Route::get('/report-stock-opname', 'Report\StockOpnameController@index');
    Route::post('/report-stock-opname-datatables', 'Report\StockOpnameController@datatables');
    Route::post('/report-stock-opname-pdf', 'Report\StockOpnameController@generatePdf')->name('report.stock-opname.pdf');

	Route::get('/user', 'User\UserController@index');
	Route::post('/user', 'User\UserController@insertData');
	Route::post('/user-datatables', 'User\UserController@datatables');
	Route::get('/user/{id:}', 'User\UserController@detail');
	Route::get('/user/{id}/delete', 'User\UserController@delete');

	Route::get('user-permissions', 'Permissions\PermissionsController@index');
	Route::post('user-permissions-update', 'Permissions\PermissionsController@update');

	Route::get('roles', 'Role\RoleController@index');
	Route::post('roles-datatables', 'Role\RoleController@datatables');
	Route::post('roles-add', 'Role\RoleController@store');
	Route::get('roles/{id}', 'Role\RoleController@get');
	Route::post('roles-update', 'Role\RoleController@update');
	Route::get('roles-delete/{id}', 'Role\RoleController@delete');

	Route::get('employe', 'Hr\EmployeController@index');
	Route::post('/employe', 'Hr\EmployeController@insertData');
	Route::post('/employe-datatables', 'Hr\EmployeController@datatables');
	Route::get('/employe/{id:}', 'Hr\EmployeController@get');
	Route::get('/employe-detail/{id:}', 'Hr\EmployeController@detail')->name('employe.detail');
	Route::delete('/employe/{id:}', 'Hr\EmployeController@delete');
	Route::get('/employe-pdf/{id:}', 'Hr\EmployeController@pdf')->name('employe.pdf');

	Route::get('employe/education/{id}', 'Hr\EmployeEducationController@get');
	Route::post('employe/education', 'Hr\EmployeEducationController@insertData');
	Route::delete('employe/education/{id}', 'Hr\EmployeEducationController@delete');

	Route::get('employe/media/{id}', 'Hr\EmployeMediaController@get');
	Route::post('employe/media', 'Hr\EmployeMediaController@insertData');
	Route::delete('employe/media/{id}', 'Hr\EmployeMediaController@delete');

	Route::get('accounting-master', 'Accounting\AccountingMasterController@index');

	Route::get('accounting-general-ledger', 'Accounting\GeneralLedgerController@index');
	Route::post('accounting-general-ledger-datatables', 'Accounting\GeneralLedgerController@datatables');

	Route::get('financial-submission', 'Accounting\FinancialSubmissionController@index');
	Route::post('financial-submission-datatables', 'Accounting\FinancialSubmissionController@datatables');

	Route::get('customer_lot_progress', 'Customer\CustomerPaymentController@lotProgress');

	Route::group(['namespace' => 'GeneralAdmin'], function () {
		Route::group(['prefix' => 'salary-submission'], function () {
			Route::get('/', ['as' => 'general-admin.salary-submission.index', 'uses' => 'WageSubmissionController@index']);
	        Route::post('/datatables', ['as' => 'general-admin.salary-submission.datatable', 'uses' => 'WageSubmissionController@datatables']);
	        Route::post('/store', ['as' => 'general-admin.salary-submission.store', 'uses' => 'WageSubmissionController@store']);
	        Route::post('/update/{id}', ['as' => 'general-admin.salary-submission.update', 'uses' => 'WageSubmissionController@update']);
	        Route::get('/delete/{id}', ['as' => 'general-admin.salary-submission.delete', 'uses' => 'WageSubmissionController@delete']);
	        Route::get('/{id}', ['as' => 'general-admin.salary-submission.edit', 'uses' => 'WageSubmissionController@edit']);
	    });
    });

	Route::get('accounting-ledger', 'Accounting\LedgerController@index');
	Route::post('accounting-ledger-datatables', 'Accounting\LedgerController@datatables');
	
	Route::get('accounting-profit-loss', 'Accounting\ProfitLossController@index');
	Route::post('accounting-profit-loss-datatables', 'Accounting\ProfitLossController@datatables');

	Route::get('accounting-balance-sheet', 'Accounting\BalanceSheetController@index');
	Route::post('accounting-balance-sheet-datatables', 'Accounting\BalanceSheetController@datatables');

	Route::get('debt', 'Accounting\DebtController@index');
	Route::post('debt', 'Accounting\DebtController@insertData');
	Route::post('debt-datatables', 'Accounting\DebtController@datatables');
	
	Route::group(['namespace' => 'Accounting', 'prefix' => 'accounting'], function () {
    	Route::get('accounting-master', 'AccountingMasterController@index');
        Route::get('/', ['as' => 'accounting.master', 'uses' => 'AccountingMasterController@initTree']);
        Route::get('/add/{coa}', ['as' => 'accounting.add', 'uses' => 'AccountingMasterController@create']);
        Route::post('/store/{coa}', ['as' => 'accounting.store', 'uses' => 'AccountingMasterController@store']);
        Route::get('/edit/{coa}', ['as' => 'accounting.edit', 'uses' => 'AccountingMasterController@edit']);
        Route::post('/update/{coa}', ['as' => 'accounting.update', 'uses' => 'AccountingMasterController@update']);
        Route::delete('/destroy/{coa}', ['as' => 'accounting.destroy', 'uses' => 'AccountingMasterController@update']);
        Route::get('/get', ['as' => 'accounting.get', 'uses' => 'AccountingMasterController@get_coa']);

        Route::group(['prefix' => 'general_ledger'], function () {
            Route::post('/datatables', ['as' => 'accounting.general_ledger.datatable', 'uses' => 'GeneralLedgerController@index']);
            Route::post('/store', ['as' => 'accounting.general_ledger.store', 'uses' => 'GeneralLedgerController@store']);
            Route::post('/update/{id}', ['as' => 'accounting.general_ledger.update', 'uses' => 'GeneralLedgerController@update']);
            Route::get('/delete/{id}', ['as' => 'accounting.general_ledger.delete', 'uses' => 'GeneralLedgerController@delete']);
            Route::get('/{id}', ['as' => 'accounting.general_ledger.edit', 'uses' => 'GeneralLedgerController@edit']);
        });

		Route::group(['prefix' => 'financial-submission'], function () {
            Route::post('/datatables', ['as' => 'accounting.financial-submission.datatable', 'uses' => 'FinancialSubmissionController@index']);
            Route::post('/store', ['as' => 'accounting.financial-submission.store', 'uses' => 'FinancialSubmissionController@store']);
            Route::post('/update/{id}', ['as' => 'accounting.financial-submission.update', 'uses' => 'FinancialSubmissionController@update']);
            Route::get('/delete/{id}', ['as' => 'accounting.financial-submission.delete', 'uses' => 'FinancialSubmissionController@delete']);
            Route::get('/{id}', ['as' => 'accounting.financial-submission.edit', 'uses' => 'FinancialSubmissionController@edit']);
        });

        Route::group(['prefix' => 'ledger'], function () {
            Route::post('/datatables', ['as' => 'accounting.ledger.datatable', 'uses' => 'LedgerController@index']);
        });

        Route::group(['prefix' => 'profit_loss'], function () {
            Route::post('/get', ['as' => 'accounting.profit_loss.get', 'uses' => 'ProfitLossController@get']);
        });

        Route::group(['prefix' => 'balance_sheet'], function () {
            Route::post('/', ['as' => 'accounting.profit_loss.get', 'uses' => 'BalanceSheetController@get']);
        });
	});


	Route::get('slf-template', 'Slf\SlfTemplateController@index');
	Route::post('slf-template-store', 'Slf\SlfTemplateController@store');

	
});

Route::get('number/generate', 'NumberGenerateController@generate');
Route::get('number/validate', 'NumberGenerateController@validateNumber');
