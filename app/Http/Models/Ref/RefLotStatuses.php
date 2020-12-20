<?php

namespace App\Http\Models\Ref;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $name
 * @property string     $note
 * @property int        $created_at
 * @property int        $updated_at
 * @property string     $key
 * @property string     $type
 */
class RefLotStatuses extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ref_lot_statuses';

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
        'name', 'note', 'created_at', 'updated_at', 'key', 'type'
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
        'name' => 'string', 'note' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'key' => 'string', 'type' => 'string'
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
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
