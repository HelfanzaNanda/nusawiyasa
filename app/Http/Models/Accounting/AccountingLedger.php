<?php

namespace App\Http\Models\Accounting;

use App\Http\Models\Accounting\AccountingJournal;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Redirect;
use Str;

class AccountingLedger extends Model
{
	protected $table = 'accounting_ledgers';

	protected $fillable = [
        'id',
        'accounting_journal_id',
        'coa',
        'debit',
        'credit',
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
            'accounting_journal_id' => ['alias' => $model->table.'.accounting_journal_id', 'type' => 'int'],
            'coa' => ['alias' => $model->table.'.coa', 'type' => 'int'],
            'debit' => ['alias' => $model->table.'.debit', 'type' => 'string'],
            'credit' => ['alias' => $model->table.'.credit', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'string'],
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $category)
    {
        $totalData = AccountingJournal::join('accounting_ledgers', 'accounting_ledgers.accounting_journal_id', '=', 'accounting_journals.id')
            ->count();

        $qry = AccountingJournal::select([
            'accounting_journals.id',
            'accounting_journals.ref',
            'accounting_journals.description',
            'accounting_journals.type',
            'accounting_journals.created_at',
            'accounting_journals.date',
            'accounting_ledgers.coa',
            'accounting_ledgers.debit',
            'accounting_ledgers.credit',
            'accounting_masters.name'
        ])
        ->join('accounting_ledgers', 'accounting_journals.id', '=', 'accounting_ledgers.accounting_journal_id')
        ->join('accounting_masters', 'accounting_masters.coa', '=', 'accounting_ledgers.coa');
        if (empty($filter['daterange'])) {
            $qry->whereMonth('accounting_journals.date', '=', date('m'))
                ->whereYear('accounting_journals.date', '=', date('Y'));
        } else {
            $startDate = Carbon::parse(substr($filter['daterange'], 0, 10))->format('Y-m-d');
            $endDate = Carbon::parse(substr($filter['daterange'], 12))->format('Y-m-d');
            $qry->whereBetween(DB::raw("DATE(accounting_journals.date)"), [$startDate, $endDate]);
        }
        if (!empty($filter['coa']) && $category === 'ledger') {
            $qry->where('accounting_ledgers.coa', $filter['coa']);
        } else if (empty($filter['coa']) && $category === 'ledger') {
            $qry->where('accounting_ledgers.coa', 0);
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
            $qry->whereRaw('(accounting_journals.ref LIKE "%'.$search.'%"')
                ->orWhereRaw('accounting_journals.description LIKE "%'.$search.'%"')
                ->orWhereRaw('accounting_journals.created_at LIKE "%'.$search.'%"')
                ->orWhereRaw('accounting_ledgers.coa LIKE "%'.$search.'%"')
                ->orWhereRaw('accounting_ledgers.debit LIKE "%'.$search.'%"')
                ->orWhereRaw('accounting_ledgers.credit LIKE "%'.$search.'%"')
                ->orWhereRaw('accounting_masters.name LIKE "%'.$search.'%")');

            $totalFiltered = $qry->count();

            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        // $sql_with_bindings = Str::replaceArray('?', $qry->getBindings(), $qry->toSql());
        // dd($sql_with_bindings);
        return [    
            'data' => $qry->get(),
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }

    public function accountingMaster()
    {
        return $this->hasOne(AccountingMaster::class, 'coa', 'coa');
    }
}