<?php

namespace App\Http\Models\Project;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $development_progress_id
 * @property string     $filename
 * @property string     $filepath
 * @property string     $filetype
 * @property int        $created_at
 * @property int        $updated_at
 */
class DevelopmentProgressFiles extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'development_progress_files';

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
        'development_progress_id', 'filename', 'filepath', 'filetype', 'created_at', 'updated_at'
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
        'development_progress_id' => 'int', 'filename' => 'string', 'filepath' => 'string', 'filetype' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
