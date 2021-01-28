<?php

namespace App\Http\Models\Accounting;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class AccountingLedger extends Model
{
	protected $table = 'accounting_ledgers';

	protected $fillable = [
        'id',
        'accounting_journal_id',
        'coa',
        'debit',
        'credit'
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
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }
}