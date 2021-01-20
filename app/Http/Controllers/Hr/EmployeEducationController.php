<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Hr\EmployeEducation;

class EmployeEducationController extends Controller
{
    public function insertData(Request $request)
    {
        $params = $request->all();
        return EmployeEducation::createOrUpdate($params, $request->method(), $request);
    }

    public function get($id){
        $education = EmployeEducation::whereId($id)->first();
        return response()->json($education);
    }

    public function delete($id){
        EmployeEducation::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah di hapus'
        ]);
    }
}
