<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\Ref\RefLotStatuses;
use Illuminate\Http\Request;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function lotSold(Request $request)
    {
    	$res = [];
    	$clusters = Cluster::selectClusterBySession();

    	foreach($clusters as $cluster) {
    		$total_credit = 0;
    		$total_cash = 0;
    		$total_cash_in_stages = 0;
    		$total_unit_ready = 0;

    		$total_unit_ready = Lot::where('cluster_id', $cluster['id'])->where('lot_status', 1)->count();
    		$total_cash = CustomerLot::join('lots', 'lots.id', '=', 'customer_lots.lot_id')->where('lots.cluster_id', $cluster['id'])->where('payment_type', 'cash')->count();
    		$total_cash_in_stages = CustomerLot::join('lots', 'lots.id', '=', 'customer_lots.lot_id')->where('lots.cluster_id', $cluster['id'])->where('payment_type', 'cash_in_stages')->count();
    		$total_credit = CustomerLot::join('lots', 'lots.id', '=', 'customer_lots.lot_id')->where('lots.cluster_id', $cluster['id'])->where('payment_type', 'credit')->count();

    		$res[$cluster['name']] = [
    			'total_unit' => $total_credit + $total_cash + $total_cash_in_stages + $total_unit_ready,
				'total_credit' => $total_credit,
				'total_cash' => $total_cash,
				'total_cash_in_stages' => $total_cash_in_stages,
				'total_unit_ready' => $total_unit_ready,
    		];
    	}
    	
    	return $res;
    }

    public function lotProgressStep(Request $request)
    {
    	$res = [];
    	$clusters = Cluster::selectClusterBySession();
    	$steps = RefLotStatuses::get();

    	foreach($clusters as $cluster) {
    		$step_array = [];

    		foreach ($steps as $step) {
    			$step_array[$step['key']] = [
    				'name' => $step['name'],
    				'total' => Lot::where('lot_status', $step['id'])->where('cluster_id', $cluster['id'])->count()
    			];
    		}

    		$res[$cluster['name']] = $step_array;
    	}
    	
    	return $res;
    }

    // public function lotSold(Request $request)
    // {
    // 	$res = [];
    // 	$clusters = Cluster::selectClusterBySession();

    // 	foreach($clusters as $cluster) {
    // 		$res[$cluster['name']][] = 0;

    // 		$lots = Lot::where('cluster_id', $cluster['id'])->get();
    // 		if ($lots) {
    // 			unset($res[$cluster['name']][0]);
	   //  		foreach($lots as $lot) {
	   //  			$res[$cluster['name']][] = [
	   //  				'id' => $lot['id'],
	   //  				'name' => $lot['block'].' '.$lot['unit_number'],
	   //  				'lot_status' => $lot['lot_status']
	   //  			];
	   //  		}
    // 		}
    // 	}
    	
    // 	return $res;
    // }
}
