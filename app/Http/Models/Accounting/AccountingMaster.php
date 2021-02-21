<?php

namespace App\Http\Models\Accounting;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class AccountingMaster extends Model
{
	protected $table = 'accounting_masters';

	protected $fillable = [
        'coa',
        'sub_coa',
        'order_coa',
        'accounting_code',
        'name',
        'type',
        'balance',
        'is_protected',
        'is_active'
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
            'coa' => ['alias' => $model->table.'.coa', 'type' => 'string'],
            'sub_coa' => ['alias' => $model->table.'.sub_coa', 'type' => 'string'],
            'order_coa' => ['alias' => $model->table.'.order_coa', 'type' => 'string'],
            'accounting_code' => ['alias' => $model->table.'.accounting_code', 'type' => 'string'],
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'type' => ['alias' => $model->table.'.type', 'type' => 'int'],
            'balance' => ['alias' => $model->table.'.balance', 'type' => 'string'],
            'is_protected' => ['alias' => $model->table.'.is_protected', 'type' => 'int'],
            'is_active' => ['alias' => $model->table.'.is_active', 'type' => 'int'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function getChildrenCOA()
    {
        $coa = self::get();

        $collect = collect($coa);
        $children = [];
        $res_coa = [];

        foreach($coa as $row) {
            $children[$row['coa']] = $row['name'];
        }

        foreach($children as $key => $child) {
            $check = $collect->where('sub_coa', $key)->first();
            if (!$check) {
                $res_coa[$key] = $child;
            }
        }

        return $res_coa;
    }
}