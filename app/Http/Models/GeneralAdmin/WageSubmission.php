<?php

namespace App\Http\Models\GeneralAdmin;

use App\Http\Models\GeneralAdmin\WageSubmissionDetail;
use DB;
use Illuminate\Database\Eloquent\Model;
use Redirect;

class WageSubmission extends Model
{
	protected $table = 'wage_submissions';

	protected $fillable = [
        'date',
        'number',
        'cluster_id',
        'total',
        'created_by_user_id',
        'approved_by_user_id',
        'received_by_user_id'
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
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'number' => ['alias' => $model->table.'.number', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'int'],
            'total' => ['alias' => $model->table.'.total', 'type' => 'string'],
            'created_by_user_id' => ['alias' => $model->table.'.created_by_user_id', 'type' => 'int'],
            'approved_by_user_id' => ['alias' => $model->table.'.approved_by_user_id', 'type' => 'int'],
            'received_by_user_id' => ['alias' => $model->table.'.received_by_user_id', 'type' => 'int'],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)->addSelect('clusters.name as cluster_name')->join('clusters', 'clusters.id', '=', 'wage_submissions.cluster_id');
        
        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6, 10])) && isset($session['_cluster_id'])) {
            $qry->where('wage_submissions.cluster_id', $session['_cluster_id']);
        }

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

        $total = 0;

        if (isset($params['weekly_cost'])) {
            foreach($params['weekly_cost'] as $weekly_cost) {
                $total += $weekly_cost;
            }
        }

        $insert = self::create([
            'number' => $params['number'],
            'cluster_id' => $params['cluster_id'],
            'date' => $params['date'],
            'total' => $total,
            'created_by_user_id' => session()->get('_id'),
        ]);

        if ($insert) {
            if (isset($params['description'])) {
                foreach($params['description'] as $key => $val) {
                    WageSubmissionDetail::create([
                        'wage_submission_id' => $insert->id,
                        'customer_lot_id' => $params['customer_lot_id'][$key],
                        'description' => $val,
                        'note' => $params['note'][$key],
                        'weekly_percentage' => $params['weekly_percentage'][$key],
                        'weekly_cost' => $params['weekly_cost'][$key],
                    ]);
                }
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }
}