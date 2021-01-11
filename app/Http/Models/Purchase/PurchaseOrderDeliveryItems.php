<?php

namespace App\Http\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Date       $date
 * @property int        $purchase_order_item_id
 * @property int        $delivered_qty
 * @property int        $created_at
 * @property int        $updated_at
 */
class PurchaseOrderDeliveryItems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_delivery_items';

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
        'purchase_order_delivery_id', 'inventory_id', 'delivered_qty', 'note', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
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
    public function inventory()
    {
        return $this->belongsTo('App\Http\Models\Inventory\Inventories', 'inventory_id');
    }
}
