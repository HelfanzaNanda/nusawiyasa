<?php

namespace App\Http\Controllers\Cluster;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Cluster\LotGallery;
use App\Http\Models\GeneralSetting\GeneralSetting;

class LotGalleryController extends Controller
{
    public function index($lotId)
    {
        return view('cluster.lot_galleries', [
            'lot' => Lot::whereId($lotId)->first(),
            'galleries' => LotGallery::where('lot_id', $lotId)->get(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }
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
