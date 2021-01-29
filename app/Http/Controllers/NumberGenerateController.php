<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Project\RequestMaterials;
use Carbon\Carbon;

class NumberGenerateController extends Controller
{
    public function generate(Request $request){
        $data = null;
        if(!isset($request->prefix) || $request->prefix == null){
            return response()->json([
                'status' => 'error',
                'data' => '',
                'message' => 'query prefix harus ada'
            ]);
        }else{
            $pref = strtoupper($request->prefix);
            if($pref == 'PJ'){
                $data = [
                    'class' => RequestMaterials::class,
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
        return response()->json([
            'number' => $this->generateNumber($data)
        ]);
    }

    public function validateNumber(Request $request){
        if(!isset($request->prefix) || $request->prefix == null){
            return response()->json([
                'status' => 'error',
                'data' => '',
                'message' => 'query prefix harus ada'
            ]);
        }
        
        if(!isset($request->number) || $request->number == null){
            return response()->json([
                'status' => 'error',
                'data' => '',
                'message' => 'query number harus ada'
            ]);
        }
        $pref = strtoupper($request->prefix);
        if($pref == 'PJ'){
            $data = RequestMaterials::whereNumber($request->number)->first();
            if($data == null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'nomor dapat digunakan'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor tidak dapat digunakan'
                ]);
            }
        }
    }

    private function generateNumber($params){

        $now = Carbon::now();
        $prefixSize = (strlen($params['prefix']))+10;
        
        $prefix = strtoupper($params['prefix']);
        $prefix .= $now->year.sprintf('%02d', $now->month);
         
        $data = $params['class']::whereRaw('LENGTH(number) = ?' ,$prefixSize)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where($params['field'], 'like', $prefix.'%')
            ->orderBy('created_at', 'DESC')
            ->first();
        if($data == null){
            $prefix .= sprintf('%04d', 1); 
        }else{
            $last = substr($data->number, -4);
            $prefix .= sprintf('%04d',++$last);
        }
        return $prefix;
    }
}
