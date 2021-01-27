<?php

namespace App\Http\Models\Customer;

use DB;
use File;
use Illuminate\Database\Eloquent\Model;
use Redirect;

class CustomerPayment extends Model
{
	protected $table = 'customer_payments';

	protected $fillable = [
        'customer_cost_id',
        'customer_lot_id',
        'value',
        'payment_type',
        'date',
        'note',
        'filepath',
        'filename'
    ];

    private $operators = [
        "\$gt" => ">",
        "\$gte" => ">=",
        "\$lte" => "<=",
        "\$lt" => "<",
        "\$like" => "like",
        "\$not" => "<>",
        "\$in" => "in"
    ];

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'customer_cost_id' => ['alias' => $model->table.'.customer_cost_id', 'type' => 'int'],
            'customer_lot_id' => ['alias' => $model->table.'.customer_lot_id', 'type' => 'int'],
            'value' => ['alias' => $model->table.'.value', 'type' => 'string'],
            'payment_type' => ['alias' => $model->table.'.payment_type', 'type' => 'string'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'note' => ['alias' => $model->table.'.note', 'type' => 'string'],
            'filepath' => ['alias' => $model->table.'.filepath', 'type' => 'string'],
            'filename' => ['alias' => $model->table.'.filename', 'type' => 'string'],
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $params['filename'] = null;
        $params['filepath'] = null;

        if($request->hasFile('file')){
            $allowedFileExtension = ['jpg', 'png', 'jpeg', 'pdf'];

            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            $month_year_pfx = date('mY');
            $path_pfx = 'public/media/payment-proof/'.$month_year_pfx;
            $path = '/storage/'.$path_pfx;

            File::makeDirectory($path, 0777, true, true);

            $check = in_array($extension, $allowedFileExtension);
            if($check){
                $params['filename'] = md5(uniqid(rand(), true).time()).'.'.$extension;
                $params['filepath'] = '/storage/media/payment-proof/'.$month_year_pfx;

                $file->move(storage_path('app').'/'.$path_pfx, $params['filename']);
            }
            unset($params['file']);
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($params);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $insert = self::create($params);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }
}
