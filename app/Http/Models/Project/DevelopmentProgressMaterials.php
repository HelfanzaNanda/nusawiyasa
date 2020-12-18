<?php

namespace App\Http\Models\Project;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $development_progress_id
 * @property string     $inventory_id
 * @property int        $qty
 * @property string     $type
 * @property int        $created_at
 * @property int        $updated_at
 */
class DevelopmentProgressMaterials extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'development_progress_materials';

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
        'development_progress_id', 'inventory_id', 'qty', 'type', 'created_at', 'updated_at'
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
        'development_progress_id' => 'int', 'inventory_id' => 'string', 'qty' => 'int', 'type' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
}
