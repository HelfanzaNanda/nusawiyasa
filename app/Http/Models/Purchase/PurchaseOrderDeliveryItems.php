<?php

namespace App\Http\Models\Purchase;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Inventory\Inventories;

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

    public static function generatePdf($id)
    {
        $purchaseItems = self::whereHas('purchaseOrderDelivery', function($orderDelivery) use($id){
            $orderDelivery->where('id', $id);
        })->with('purchaseOrderDelivery')->get();

        $result = [];
        foreach ($purchaseItems as $val) {
            $body[] = [
                'code' => $val->inventory->code ?? '-',
                'name' => $val->inventory->name,
                'total' => $val->sum('delivered_qty'),
                'unit' => $val->inventory->unit->name,
                'note' => $val->note ?? '-',
            ];
            $item = [
                'supplier_name' => $val->purchaseOrderDelivery->purchaseOrder->supplier->name,
                'supplier_address' => $val->purchaseOrderDelivery->purchaseOrder->supplier->address,
                'bpb_number' => $val->purchaseOrderDelivery->bpb_number,
                'date' => Carbon::parse($val->purchaseOrderDelivery->date)->translatedFormat('d F Y'),
                'po_number' => $val->purchaseOrderDelivery->purchaseOrder->number,
                'inv_number' => $val->purchaseOrderDelivery->invoice_number,
                'body' => $body
            ];
            $result = $item;
        }

        return $result;
    }

    public function purchaseOrderDelivery()
    {
        return $this->belongsTo(PurchaseOrderDeliveries::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventories::class);
    }

    // Scopes...

    // Functions ...

    // Relations ...
    public function inventory()
    {
        return $this->belongsTo('App\Http\Models\Inventory\Inventories', 'inventory_id');
    }
}
