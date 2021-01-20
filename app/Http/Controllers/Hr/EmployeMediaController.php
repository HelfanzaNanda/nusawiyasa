<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Hr\EmployeMedia;

class EmployeMediaController extends Controller
{
    public function insertData(Request $request)
    {
        $params = $request->all();
        return EmployeMedia::createOrUpdate($params, $request->method(), $request);
    }

    public function get($id){
        $education = EmployeMedia::whereId($id)->first();
        return response()->json($education);
    }

    public function delete($id){
        EmployeMedia::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah di hapus'
        ]);
    }
}
