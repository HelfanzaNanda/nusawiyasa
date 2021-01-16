<?php

namespace App\Http\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Inventory\Suppliers;
use Carbon\Carbon;

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
        'purchase_order_id', 'inventory_id', 'qty', 'delivered_qty', 'price', 'tax', 'discount', 'total', 'created_at', 'updated_at', 'supplier_id'
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

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    public function inventory()
    {
        return $this->belongsTo('App\Http\Models\Inventory\Inventories');
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

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrders::class);
    }

    public static function generatePdf($id)
    {
        $purchaseItems = self::whereHas('purchaseOrder', function($purchaseOrder) use($id){
            $purchaseOrder->where('id', $id);
        })->get();

        $result = [];
        foreach ($purchaseItems as $val) {
            $body[] = [
                'inventory_name' => $val->inventory->name,
                'qty' => $val->qty,
                'price' => $val->price,
                'total' => $val->total,
            ];
            $item = [
                'date' => Carbon::parse($val->purchaseOrder->date)->translatedFormat('d F Y'),
                'po_number' => $val->purchaseOrder->number,
                'fpp_number' => $val->purchaseOrder->fpp_number,
                'isRap' => $val->type == 'rap' ? true : false,
                'receiver' => 'ini gatau',
                'supplier_name' => $val->purchaseOrder->supplier ? $val->purchaseOrder->supplier->name : '-',
                'supplier_address' => $val->purchaseOrder->supplier ? $val->purchaseOrder->supplier->address : '-',
                'supplier_phone' => $val->purchaseOrder->supplier ? $val->purchaseOrder->supplier->phone : '-',
                'supplier_telephone' => $val->purchaseOrder->supplier ? $val->purchaseOrder->supplier->pic_phone : '-',
                'cluster_name' => $val->purchaseOrder->cluster ?   $val->purchaseOrder->cluster->name : '-',
                'cluster_phone' => $val->purchaseOrder->cluster ?  $val->purchaseOrder->cluster->phone : '-',
                'cluster_address' => $val->purchaseOrder->cluster ?  $val->purchaseOrder->cluster->address. ' '
                                    .$val->purchaseOrder->cluster->subdistrict. ' '
                                    .$val->purchaseOrder->cluster->district. ' '
                                    .$val->purchaseOrder->cluster->city. ' '
                                    .$val->purchaseOrder->cluster->provice. ' ' : '-',
                'sub_total' => $val->purchaseOrder->subtotal,
                'tax' => substr($val->purchaseOrder->tax, 0, 1) == 0 ? 0 : $val->purchaseOrder->tax,
                'delivery' => substr($val->purchaseOrder->delivery, 0, 1) == 0 ? 0 : $val->purchaseOrder->delivery,
                'other' => substr($val->purchaseOrder->other, 0, 1) == 0 ? 0 : $val->purchaseOrder->other,
                'total' => $val->purchaseOrder->total,
                'note' => $val->purchaseOrder->note,
                'body' => $body
            ];

            $result = $item;

        }
        return $result;
    }
}
