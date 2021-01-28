<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Models\Accounting\AccountingMaster;
use Illuminate\Http\Request;

class AccountingGeneralLedgerController extends Controller
{
    public function index()
    {
        return view('accounting.master.'.__FUNCTION__);
    }

    public function initTree()
    {
        $query = AccountingMaster::orderBy('coa', 'ASC')->get();
        $data =[];
        foreach($query as $row){
            $tmp = [];
            $tmp['id'] = $row['coa'];
            $tmp['name'] = $row['accounting_code'];
            $tmp['text'] = '<span style="font-size:14px;">'.$row['accounting_code'].' '.$row['name']. '</span>&nbsp;&nbsp;
            <a href="#" title="Edit Data" style="cursor: pointer;" class="btn btn-warning btn-icon rounded-circle" id="edit-accounting" data-id="'.$row['id'].'">
            <div>
            <i class="fa fa-pencil"></i>
            </div></a>&nbsp;&nbsp;
            <a href="#" title="Tambah Data" style="cursor: pointer;" class="btn btn-success btn-icon rounded-circle" id="add-accounting" data-id="'.$row['coa'].'">
            <div>
            <i class="fa fa-plus"></i>
            </div>
            </a>';
            $tmp['parent_id'] = $row['sub_coa'];
            array_push($data, $tmp); 
        }
        $itemsByReference = [];
         
         // Build array of item references:
         foreach($data as $key => &$item) {
            $itemsByReference[$item['id']] = &$item;
            // Children array:
            $itemsByReference[$item['id']]['nodes'] = [];
         }
         
         // Set items as children of the relevant parent item.
        foreach($data as $key => &$item)  {
        //echo "<pre>";print_r($itemsByReference[$item['parent_id']]);die;
            if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
               $itemsByReference [$item['parent_id']]['nodes'][] = &$item;
            }
        }

        // Remove items that were added to parents elsewhere:
        foreach($data as $key => &$item) {
             if(empty($item['nodes'])) {
                unset($item['nodes']);
                }
           if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
              unset($data[$key]);
             }
        }

        return response()->json($data);
    }

    public function create($id)
    {
        $accMaster = AccountingMaster::where('coa', $id)
                        ->first();
        $getMaxOrderCOA = $this->getMaxOrderCOA($id);

        return response()->json([
            'accMaster' => $accMaster,
            'getMaxOrderCOA' => $getMaxOrderCOA
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $accMaster = AccountingMaster::where('coa', $id)
                        ->first();
        $accountingMaster = new AccountingMaster;
        $accountingMaster->sub_coa = $id;
        $accountingMaster->name = $request['name'];
        $accountingMaster->type = $request['type'];
        $accountingMaster->coa = $request['coa'];
        $accountingMaster->order_coa = $request['order_coa'];
        $accountingMaster->accounting_code = $accMaster['accounting_code'].'.'.$request['order_coa'];
        $accountingMaster->balance = 0;
        $accountingMaster->is_protected = 0;
        $accountingMaster->is_active = 1;
        if ($accountingMaster->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses menambahkan COA',
                'data' => null
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi Kesalahan',
            'data' => null
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accMaster = AccountingMaster::where('id', $id)
                        ->first();

        return response()->json([
            'accMaster' => $accMaster,
            'getMaxOrderCOA' => $accMaster['order_coa']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $accMaster = AccountingMaster::where('id', $id)
                        ->first();
        $accMaster->name = $request['name'];
        $accMaster->type = $request['type'];
        if ($accMaster->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses memperbaharui COA',
                'data' => null
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi Kesalahan',
            'data' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function getMaxOrderCOA($id)
    {
        $query = AccountingMaster::select('order_coa as max')
                ->where('sub_coa', $id)
                ->orderBy('order_coa', 'DESC')
                ->first();

        $order_coa = "";
        if($query)
        {
            $tmp = ((int)$query->max)+1;
            $order_coa = sprintf("%02s", $tmp);
        }
        else
        {
            $order_coa = "01";
        }   
        return $order_coa;
    }

    public function get_coa(Request $request)
    {
        $db = AccountingMaster::select('coa', DB::raw('CONCAT(accounting_code, " | ", name) AS name'))->where('name', 'LIKE', '%'.$request->term.'%')->get();

        return json_decode($db);
    }
}