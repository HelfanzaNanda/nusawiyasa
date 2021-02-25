<?php

namespace App\Http\Controllers\Hr;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Models\Hr\Employe;
use App\Http\Models\Ref\Province;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use App\Http\Models\GeneralSetting\GeneralSetting;

class EmployeController extends Controller
{
    public function index(){
        return view('employe.index', [
            'provinces' => Province::get(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function datatables(Request $request)
    {
        $_login = session()->get('_login');
        $_id = session()->get('_id');
        $_name = session()->get('_name');
        $_email = session()->get('_email');
        $_username = session()->get('_username');
        $_phone = session()->get('_phone');
        $_role_id = session()->get('_role_id');
        $_role_name = session()->get('_role_name');

        $columns = [
            0 => 'employes.id'
        ];

        $dataOrder = [];

        $limit = $request->length;

        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']];
            $nestedOrder['dir'] = $row['dir'];

            $dataOrder[] = $nestedOrder;
        }

        $order = $dataOrder;

        $dir = $request->order[0]['dir'];

        $search = $request->search['value'];

        $filter = $request->only(['sDate', 'eDate']);

        $res = Employe::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['fullname'] = $row['fullname'];
                $nestedData['email'] = $row['email'];
                $nestedData['employe_status'] = $row['employe_status'];
                $nestedData['gender'] = $row['gender'];
                $nestedData['joined_at'] = $row['joined_at'];
                $nestedData['bank_account'] = $row['bank_account'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" id="edit" href="#" data-toggle="modal" data-target="#edit_leave" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" href="#" data-toggle="modal" data-target="#delete_approve" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="detail" href="'.route('employe.detail', $row['id']).'"><i class="fa fa-info m-r-5"></i> Detail</a>';
                $nestedData['action'] .='            </div>';
                $nestedData['action'] .='        </div>';
                $data[] = $nestedData;
            }
        }
        $json_data = [
            'draw'  => intval($request->draw),
            'recordsTotal'  => intval($res['totalData']),
            'recordsFiltered' => intval($res['totalFiltered']),
            'data'  => $data,
            'order' => $order
        ];

        return json_encode($json_data);
    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return Employe::createOrUpdate($params, $request->method(), $request);
    }

    public function get($id, Request $request)
    {
        $customer = Employe::where('id', $id)->first();

        $customer['user'] = $customer->user;
        return response()->json($customer);
    }

    public function delete($id){
        $customer = Employe::destroy($id);
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function detail($id){
        $employee = Employe::whereId($id)->first();

        if($employee->date_birth){
            $employee['age'] =  Carbon::createFromDate($employee->date_birth)->diff(Carbon::now())->format('%y thn, %m bln and %d hr');
            $employee->date_birth = Carbon::parse($employee->date_birth)->isoFormat('D MMMM Y');
        }else{
            $employee['age'] = '-';
            $employee->date_birth = '-';
        }
        
        if($employee->joined_at){
            $employee['lama_kerja'] = Carbon::createFromDate($employee->joined_at)->diff(Carbon::now())->format('%y thn, %m bln and %d hr');
            $employee->joined_at = Carbon::parse($employee->joined_at)->isoFormat('D MMMM Y');
        }else{
            $employee['lama_kerja'] = '-';
            $employee->joined_at = '-';
        }
        $provinces = Province::get();
        
        $company_logo = GeneralSetting::getCompanyLogo();
        $company_name = GeneralSetting::getCompanyName();
        return view('employe.detail', compact(['employee', 'provinces', 'company_logo', 'company_name']));
    }

    public function pdf($id){
        $data = Employe::whereId($id)->first();

        if($data->date_birth){
            $data['age'] =  Carbon::createFromDate($data->date_birth)->diff(Carbon::now())->format('%y thn, %m bln and %d hr');
            $data->date_birth = Carbon::parse($data->date_birth)->isoFormat('D MMMM Y');
        }else{
            $data['age'] = '-';
            $data->date_birth = '-';
        }
        
        if($data->joined_at){
            $data['lama_kerja'] = Carbon::createFromDate($data->joined_at)->diff(Carbon::now())->format('%y thn, %m bln and %d hr');
            $data->joined_at = Carbon::parse($data->joined_at)->isoFormat('D MMMM Y');
        }else{
            $data['lama_kerja'] = '-';
            $data->joined_at = '-';
        }
        
        $path = ($data['avatar']) ? public_path($data['avatar']) : public_path('template/assets/img/user.jpg');
        
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $datafile = file_get_contents($path);
        $data['avatar'] = 'data:image/' . $type . ';base64,' . base64_encode($datafile);
        
        $customPaper = array(0,0,360,360);
        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true, 
            'isHtml5ParserEnabled' => true, 
            'setPaper' => $customPaper
        ])
        ->loadview('employe.pdf', [
            'data' => $data,
            'header' => GeneralSetting::getPdfHeaderImage(),
            'footer' => GeneralSetting::getPdfFooterImage()
            
        ]);
        return $pdf->download('Data diri '.$data['number'].'-'.Carbon::now().'.pdf');
    }
}
