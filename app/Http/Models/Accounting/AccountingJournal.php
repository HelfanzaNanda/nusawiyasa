<?php

namespace App\Http\Models\Accounting;

use App\Http\Models\Accounting\AccountingLedger;
use DB;
use Illuminate\Database\Eloquent\Model;
use Redirect;

class AccountingJournal extends Model
{
	protected $table = 'accounting_journals';

	protected $fillable = [
		'id',
		'ref',
		'description',
		'type',
		'date',
        'total',
        'cluster_id'
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
			'ref' => ['alias' => $model->table.'.ref', 'type' => 'string'],
			'description' => ['alias' => $model->table.'.description', 'type' => 'string'],
			'type' => ['alias' => $model->table.'.type', 'type' => 'int'],
			'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'total' => ['alias' => $model->table.'.total', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'string'],
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)->addSelect('clusters.name as cluster_name')->leftJoin('clusters', 'clusters.id', '=', 'accounting_journals.cluster_id');

        if ($filter['isPk'] == "ya") {
            $qry->where('ref', 'like', 'PK'. '%');
        }else{
            $qry->where('ref', 'like', 'JU'. '%');
        }


        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6, 10])) && isset($session['_cluster_id'])) {
            $qry->where('accounting_journals.cluster_id', $session['_cluster_id']);
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
    
    public function accountingLedgers()
    {
        return $this->hasMany(AccountingLedger::class);
    }

    public static function journalPosting($params)
    {
        DB::beginTransaction();
        $details = $params['details'];
        unset($params['details']);
        $insertJournal = self::create($params);

        if ($insertJournal) {
            if (count($details) > 0) {
                foreach($details as $type => $values) {
                    foreach($values as $coa => $value) {
                        AccountingLedger::create([
                            'accounting_journal_id' => $insertJournal->id,
                            'coa' => $coa,
                            'debit' => $type == 'debit' ? $value : 0,
                            'credit' => $type == 'credit' ? $value : 0,
                            'cluster_id' => $params['cluster_id'],
                        ]);
                    }
                }
            }
        }

        DB::commit();
    }
    
}