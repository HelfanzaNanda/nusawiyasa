<?php

namespace App\Helper;

use App\Http\Models\Accounting\AccountingJournal;
use App\Http\Models\Accounting\Debt;
use App\Http\Models\GeneralAdmin\WageSubmission;
use App\Http\Models\Inventory\DeliveryOrders;
use App\Http\Models\Inventory\ReceiptOfGoodsRequest;
use App\Http\Models\Project\RequestMaterials;
use App\Http\Models\Project\SpkProjectAdditionals;
use App\Http\Models\Project\SpkProjects;
use App\Http\Models\Project\WorkAgreementAdditionals;
use App\Http\Models\Project\WorkAgreements;
use App\Http\Models\Purchase\PurchaseOrderDeliveries;
use App\Http\Models\Purchase\PurchaseOrders;
use Carbon\Carbon;

class GlobalHelper
{
	public static function convertSeparator($number)
	{
	    $number = str_replace(',', '', $number);

	    if ($number > 0) {
	    	return $number;
	    }

	    return 0;
	}

    public static function generate($prefix){
        $data = null;
        if(!isset($prefix) || $prefix == null){
            return response()->json([
                'status' => 'error',
                'data' => '',
                'message' => 'query prefix harus ada'
            ]);
        }else{
            $pref = $prefix;
            if($pref == 'PB'){
                $data = [
                    'class' => RequestMaterials::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'SPK'){
                $data = [
                    'class' => SpkProjects::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'BPB'){
                $data = [
                    'class' => ReceiptOfGoodsRequest::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'BkPB'){
                $data = [
                    'class' => PurchaseOrderDeliveries::class,
                    'field' => 'bpb_number',
                    'prefix' => $pref
                ];
            }else if($pref == 'SJ'){
                $data = [
                    'class' => DeliveryOrders::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'PO'){
                $data = [
                    'class' => PurchaseOrders::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'JU'){
                $data = [
                    'class' => AccountingJournal::class,
                    'field' => 'ref',
                    'prefix' => $pref
                ];
            }else if($pref == 'WA'){
                $data = [
                    'class' => WorkAgreements::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'WAP'){
                $data = [
                    'class' => WorkAgreementAdditionals::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'SPKP'){
                $data = [
                    'class' => SpkProjectAdditionals::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'PK'){
                $data = [
                    'class' => AccountingJournal::class,
                    'field' => 'ref',
                    'prefix' => $pref
                ];
            }else if($pref == 'PU'){
                $data = [
                    'class' => WageSubmission::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else if($pref == 'DE'){
                $data = [
                    'class' => Debt::class,
                    'field' => 'number',
                    'prefix' => $pref
                ];
            }else{
                return response()->json([
                    'status' => 'error',
                    'data' => '',
                    'message' => 'prefix tidak valid'
                ]);
            }
        }
        return self::generateNumber($data);
    }

    private static function generateNumber($params){
        $now = Carbon::now();
        $prefixSize = (strlen($params['prefix']))+10;
        
        $prefix = $params['prefix'];
        $prefix .= $now->year.sprintf('%02d', $now->month);
         
        $data = $params['class']::whereRaw('LENGTH('.$params['field'].') = ?' ,$prefixSize)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where($params['field'], 'like', $prefix.'%')
            ->orderBy('created_at', 'DESC')
            ->first();

        if($data == null){
            $prefix .= sprintf('%04d', 1); 
        }else{
            $repeat = true;
            $last = substr($data[$params['field']], -4);
            $new = sprintf('%04d',++$last);
            while($repeat){
                $data = $params['class']::where($params['field'], $prefix.$new)->first();
                if($data == null){
                    $repeat = false;
                    $prefix .= sprintf('%04d',$new);
                }else{
                    $new++;
                }
            }
        }
        return $prefix;
    }
}