<?php

namespace App\Http\Models\Project;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RequestOfOtherMaterialItems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'request_of_other_material_items';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_of_other_material_id', 'inventory_name', 'brand', 'unit', 'qty', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'request_material_id' => 'int', 'inventory_id' => 'int', 'inventory_name' => 'string', 'brand' => 'string', 'qty' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public function inventory()
    {
        return $this->belongsTo('App\Http\Models\Inventory\Inventories', 'inventory_id');
    }

    public function requestOfOtherMaterial()
    {
        return $this->belongsTo(RequestOfOtherMaterials::class);
    }

    public static function generatePdf($id)
    {
        $requestMaterialItems =  self::with('requestOfOtherMaterial')
        ->whereHas('requestOfOtherMaterial', function($reqMaterial) use ($id){
            $reqMaterial->where('id', $id);
        })->get();

        $result = [];
        foreach ($requestMaterialItems as $val) {
            $body[] = [
                'inventory_name' => $val->inventory_name,
                'inventory_brand' => $val->brand ?? '-',
                'total' => $val->qty
            ];
            $item = [
                'date' => Carbon::parse($val->requestOfOtherMaterial->date)->translatedFormat('d F Y'),
                'rmf_number' => $val->requestOfOtherMaterial->number,
                'title' => $val->requestOfOtherMaterial->title,
                'body' => $body
            ];

            $result = $item;
        }
        return $result;
    }
    // Scopes...

    // Functions ...

    // Relations ...
}
