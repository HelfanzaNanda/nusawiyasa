<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Models\Customer\CustomerCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function totalReceivable($customer_id, $lot_id = 0, Request $request)
    {   
        $total_receivable = 0;

        $customer_costs = CustomerCost::select('customer_costs.*')->addSelect('ref_term_purchasing_customers.name as key_name')->where('customer_id', $customer_id)->join('ref_term_purchasing_customers', 'ref_term_purchasing_customers.id', '=', 'customer_costs.ref_term_purchasing_customer_id');
        if ($lot_id > 0) {
            $customer_costs->where('lot_id', $lot_id);
        }

        foreach ($customer_costs->get() as $row) {
            $total_receivable += floatval($row['value']);
        }

        return response()->json([
            'total_receivable' => $total_receivable
        ]);
    }
}
