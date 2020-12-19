<?php

namespace App\Http\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $purchase_order_id
 * @property int        $inventory_id
 * @property int        $qty
 * @property int        $delivered_qty
 * @property int        $created_at
 * @property int        $updated_at
 */
class PurchaseOrderItems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_items';

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
        'purchase_order_id', 'inventory_id', 'qty', 'delivered_qty', 'price', 'tax', 'discount', 'total', 'created_at', 'updated_at'
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
        'purchase_order_id' => 'int', 'inventory_id' => 'int', 'qty' => 'int', 'delivered_qty' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function inventory()
    {
        return $this->hasOne('App\Http\Models\Inventory\Inventories', 'id', 'inventory_id');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Scopes...

    // Functions ...

    // Relations ...

    public static function adjustPurchaseOrderDeliveredItems($params)
    {
        $purchase_order_item = self::where('purchase_order_id', $params['purchase_order_id'])->where('inventory_id', $params['inventory_id'])->first();

        $order_total = $purchase_order_item['qty'];
        $current_delivered_total = $purchase_order_item['delivered_qty'];

        if (($current_delivered_total + $params['delivered_qty']) > $order_total) {
            return [
                'status' => 'error',
                'message' => 'Jumlah diterima lebih besar daripada jumlah order'
            ];
        }

        $purchase_order_item->delivered_qty = $current_delivered_total + $params['delivered_qty'];
        $purchase_order_item->save();
    }
}
