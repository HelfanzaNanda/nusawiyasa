<?php

namespace App\Http\Models\SLF;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class SLFTemplate extends Model
{
	protected $table = 'slf_templates';

	protected $fillable = [
        'name',
        'template_text'
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
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'template_text' => ['alias' => $model->table.'.template_text', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }
}