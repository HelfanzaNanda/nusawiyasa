<?php

namespace App\Http\Models\Project;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $number
 * @property int        $template_id
 * @property string     $title
 * @property string     $subject
 * @property Date       $date
 * @property string     $params
 * @property int        $created_at
 * @property int        $updated_at
 */
class WorkAgreements extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'work_agreements';

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
        'number', 'template_id', 'title', 'subject', 'date', 'params', 'created_at', 'updated_at'
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
        'number' => 'string', 'template_id' => 'int', 'title' => 'string', 'subject' => 'string', 'date' => 'date', 'params' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date', 'created_at', 'updated_at'
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
