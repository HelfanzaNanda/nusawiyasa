<?php

namespace App\Http\Models\Accounting;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class AccountingJournal extends Model
{
	protected $table = 'accounting_journals';

	protected $fillable = [
		'id',
		'ref',
		'description',
		'type',
		'date'
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
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }
}