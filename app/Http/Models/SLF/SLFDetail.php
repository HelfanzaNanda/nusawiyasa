<?php

namespace App\Http\Models\SLF;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class SLFDetail extends Model
{
	protected $table = 'slf_details';

	protected $fillable = [
        'slf_id',
        'name',
        'description',
        'filename',
        'filepath'
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
            'slf_id' => ['alias' => $model->table.'.slf_id', 'type' => 'int'],
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'description' => ['alias' => $model->table.'.description', 'type' => 'string'],
            'filename' => ['alias' => $model->table.'.filename', 'type' => 'string'],
            'filepath' => ['alias' => $model->table.'.filepath', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }
}