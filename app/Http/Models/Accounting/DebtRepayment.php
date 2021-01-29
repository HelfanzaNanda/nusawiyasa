<?php

namespace App\Http\Models\Accounting;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class DebtRepayment extends Model
{
	protected $table = 'debt_repayments';

	protected $fillable = [
        'debt_id',
        'date_pay',
        'total'
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
            'debt_id' => ['alias' => $model->table.'.debt_id', 'type' => 'int'],
            'date_pay' => ['alias' => $model->table.'.date_pay', 'type' => 'string'],
            'total' => ['alias' => $model->table.'.total', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }
}