<?php

namespace App\Http\Controllers\Cluster;

use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\LotGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LotGalleryController extends Controller
{
    public function insertData(Request $request)
    {
        $params = $request->all();
        return LotGallery::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {

    }

    public function detail($id)
    {
        $lot = LotGallery::whereId($id)->first();
        return response()->json($lot);
    }

    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = LotGallery::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = LotGallery::getAllResult($request);
        } else {
            $res = LotGallery::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return LotGallery::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return LotGallery::createOrUpdate($params, $request->method(), $request);
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return LotGallery::createOrUpdate($params, $request->method(), $request);
    }

    public function delete($id)
    {
        LotGallery::destroy($id);
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }
}
