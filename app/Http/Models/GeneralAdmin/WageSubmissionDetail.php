<?php

namespace App\Http\Models\GeneralAdmin;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class WageSubmissionDetail extends Model
{
	protected $table = 'wage_submission_details';

	protected $fillable = [
        'wage_submission_id',
        'customer_lot_id',
        'description',
        'note',
        'weekly_percentage',
        'weekly_cost'
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
            'wage_submission_id' => ['alias' => $model->table.'.wage_submission_id', 'type' => 'int'],
            'customer_lot_id' => ['alias' => $model->table.'.customer_lot_id', 'type' => 'int'],
            'description' => ['alias' => $model->table.'.description', 'type' => 'string'],
            'note' => ['alias' => $model->table.'.note', 'type' => 'string'],
            'weekly_percentage' => ['alias' => $model->table.'.weekly_percentage', 'type' => 'string'],
            'weekly_cost' => ['alias' => $model->table.'.weekly_cost', 'type' => 'string']
        ];
    }
}