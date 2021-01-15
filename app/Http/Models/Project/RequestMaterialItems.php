<?php

namespace App\Http\Models\Project;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $request_material_id
 * @property int        $inventory_id
 * @property string     $inventory_name
 * @property string     $brand
 * @property int        $qty
 * @property int        $created_at
 * @property int        $updated_at
 */
class RequestMaterialItems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'request_material_items';

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
        'request_material_id', 'inventory_id', 'inventory_name', 'brand', 'qty', 'created_at', 'updated_at'
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

    public function requestMaterial()
    {
        return $this->belongsTo(RequestMaterials::class);
    }

    public static function generatePdf($id)
    {
        $requestMaterialItems =  self::with('requestMaterial')->whereHas('requestMaterial', function($reqMaterial) use ($id){
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
                'date' => Carbon::parse($val->requestMaterial->date)->translatedFormat('d F Y'),
                'rmf_number' => $val->requestMaterial->number,
                'title' => $val->requestMaterial->title,
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
