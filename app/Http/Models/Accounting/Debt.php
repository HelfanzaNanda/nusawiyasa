<?php

namespace App\Http\Models\Accounting;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
	protected $table = 'debts';

	protected $fillable = [
        'number',
        'supplier_id',
        'purchase_order_id',
        'total',
        'date',
        'payment_plan_date',
        'description'
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
            'number' => ['alias' => $model->table.'.number', 'type' => 'string'],
            'supplier_id' => ['alias' => $model->table.'.supplier_id', 'type' => 'int'],
            'purchase_order_id' => ['alias' => $model->table.'.purchase_order_id', 'type' => 'int'],
            'total' => ['alias' => $model->table.'.total', 'type' => 'string'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'payment_plan_date' => ['alias' => $model->table.'.payment_plan_date', 'type' => 'string'],
            'description' => ['alias' => $model->table.'.description', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();
        $filename = null;

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

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)->addSelect('suppliers.name as supplier_name')->addSelect('purchase_orders.number as po_number')
                    ->leftJoin('suppliers', 'suppliers.id', '=', 'debts.supplier_id')
                    ->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'debts.purchase_order_id');
        
        $totalFiltered = $qry->count();
        
        if (empty($search)) {
            
            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }

        } else {
            foreach (array_values(self::mapSchema()) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['alias'].' LIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema())) == ($key + 1)) {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\')');
                } else {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\'');
                }
            }

            $totalFiltered = $qry->count();

            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        return [
            'data' => $qry->get(),
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }
}