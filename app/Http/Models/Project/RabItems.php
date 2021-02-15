<?php

namespace App\Http\Models\Project;

use App\Http\Models\Inventory\Inventories;
use Illuminate\Database\Eloquent\Model;

class RabItems extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rab_items';

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
        'rab_id', 'inventory_id', 'qty', 'price', 'total', 'created_at', 'updated_at'
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
        'rab_id' => 'int', 'inventory_id' => 'int', 'qty' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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

    // Scopes...

    // Functions ...

    // Relations ...
    public function inventory(){
        return $this->belongsTo(Inventories::class);
    }
}
