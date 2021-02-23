<?php

namespace App\Http\Models\Customer;

use App\Helper\GlobalHelper;
use App\Http\Models\Accounting\AccountingJournal;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Customer\Customer;
use App\Http\Models\Customer\CustomerCost;
use App\Http\Models\Customer\CustomerTerm;
use App\Http\Models\Ref\DefaultAccount;
use App\Http\Models\Ref\RefGeneralStatuses;
use App\Http\Models\Ref\RefTermPurchasingCustomer;
use DB;
use File;
use Illuminate\Database\Eloquent\Model;
use Redirect;

class CustomerLot extends Model
{
	protected $table = 'customer_lots';

	protected $fillable = [
		'customer_id',
		'lot_id',
		'status',
		'is_active',
		'is_deleted',
        'booking_date',
        'payment_type',
        'bank_status',
        'bank_statu_number'
    ];

    private $operators = [
        "\$gt" => ">",
        "\$gte" => ">=",
        "\$lte" => "<=",
        "\$lt" => "<",
        "\$like" => "like",
        "\$not" => "<>",
        "\$in" => "in"
    ];

    public function customer()
    {
        return $this->hasOne('App\Http\Models\Customer\Customer', 'id', 'customer_id');
    }

    public function lot()
    {
        return $this->hasOne('App\Http\Models\Cluster\Lot', 'id', 'lot_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
			'customer_id' => ['alias' => $model->table.'.customer_id', 'type' => 'int'],
			'lot_id' => ['alias' => $model->table.'.lot_id', 'type' => 'int'],
			'status' => ['alias' => $model->table.'.status', 'type' => 'string'],
			'is_active' => ['alias' => $model->table.'.is_active', 'type' => 'int'],
			'is_deleted' => ['alias' => $model->table.'.is_deleted', 'type' => 'int'],
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
            'booking_date' => ['alias' => $model->table.'.booking_date', 'type' => 'string'],
            'payment_type' => ['alias' => $model->table.'.payment_type', 'type' => 'string'],
            'bank_status' => ['alias' => $model->table.'.bank_status', 'type' => 'string'],
            'bank_status_number' => ['alias' => $model->table.'.bank_status_number', 'type' => 'string'],
        ];
    }

    public static function getById($id, $params)
    {
        $db = self::where('id', $id)
            ->first();

        return response()->json($db);
    }

    public static function getPaginatedResult($params)
    {
        $paramsPage = isset($params['page']) ? $params['page'] : 0;

        unset($params['page']);

        $db = self::select(array_keys(self::mapSchema()));

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'ilike') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        $countAll = $db->count();
        $currentPage = $paramsPage > 0 ? $paramsPage - 1 : 0;
        $page = $paramsPage > 0 ? $paramsPage + 1 : 2;
        $nextPage = env('APP_URL').'/api/users?page='.$page;
        $prevPage = env('APP_URL').'/api/users?page='.($currentPage < 1 ? 1 : $currentPage);
        $totalPage = ceil((int)$countAll / 10);

        // $db->orderBy($model->table.'.fullname', 'asc');
        $db->skip($currentPage * 10)
           ->take(10);

        return response()->json([
            'nav' => [
                'totalData' => $countAll,
                'nextPage' => $nextPage,
                'prevPage' => $prevPage,
                'totalPage' => $totalPage
            ],
            'data' => $db->get()
        ]);
    }

    public static function getAllResult($params)
    {
        unset($params['all']);

        $db = self::select(array_keys(self::mapSchema()));

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'ilike') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'data' => $db->get()
        ]);
    }

    public static function uploadImage($image, $folder)
    {
        $allowedfileExtension = ['pdf','jpg','png','docx', 'jpeg', 'txt'];
        $month_year_pfx = date('mY');
        $path_pfx = 'public/media/'.$folder.'/'.$month_year_pfx;
        $path = '/storage/'.$path_pfx;

        File::makeDirectory($path, 0777, true, true);

        $filename = $image->getClientOriginalName();

        $extension = $image->getClientOriginalExtension();
        $check = in_array($extension, $allowedfileExtension);
        if ($check) {
            $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

            $image->move(storage_path('app').'/'.$path_pfx, $filename);
            return [
                'status' => true,
                'filename' => $filename,
                'month_year_pfx' => $month_year_pfx,
                'filetype' => $extension
            ];

        } else {
            return [
                'status' => false,
            ];
        }
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            $params_customer_cost = [];
            if (isset($params['customer_costs']) && count($params['customer_costs']) > 0) {
                $params_customer_cost = $params['customer_costs'];
                unset($params['customer_costs']);
            }

            $params_customer_term = [];
            if (isset($params['customer_terms']) && count($params['customer_terms']) > 0) {
                $params_customer_term = $params['customer_terms'];
                unset($params['customer_terms']);
            }
            unset($params['id']);
            unset($params['term_ids']);
            $customer_lot = self::where('id', $id)->update($params)->first();

            // $customer_lot_update['customer_id'] = $params['customer_id'];
            // $customer_lot_update['lot_id'] = $params['lot_id'];
            // $customer_lot_update['status'] = 1;
            // $customer_lot_update['booking_date'] = $params['booking_date'];
            // $customer_lot_update['payment_type'] = $params['payment_type'];
            // $customer_lot_update['bank_status'] = 1;

            // $update = $customer_lot->update($customer_lot_update);

            if ($customer_lot) {
                if (count($params_customer_cost) > 0) {
                    CustomerCost::where('customer_id', $customer_lot['customer_id'])->where('lot_id', $customer_lot['lot_id'])->delete();
                    foreach($params_customer_cost as $key => $customer_cost) {
                        CustomerCost::create([
                            'customer_id' => $params['customer_id'],
                            'ref_term_purchasing_customer_id' => $key,
                            'value' => $customer_cost,
                            'status' => 1,
                            'lot_id' => $params['lot_id']
                        ]);
                    }
                }

                if ($request->has('customer_terms')) {
                    $files = $request->file('customer_terms');
                    foreach ($files as $keyFile => $file) {
                        if ($params['term_ids'][$keyFile] != null) {
                            $responseImage = self::uploadImage($file, 'customer_terms');
                            if ($responseImage['status']) {
                                $cust_term = CustomerTerm::findOrfail($params['term_ids'][$keyFile]);
                                $cust_term->update([
                                    'customer_id' => $params['customer_id'],
                                    'ref_term_purchasing_customer_id' => $keyFile,
                                    'filename' => $responseImage['filename'],
                                    'filepath' => '/storage/media/customer_terms/'.$responseImage['month_year_pfx'],
                                    'filetype' => $responseImage['filetype'],
                                    'status' => 1,
                                    'lot_id' => $params['lot_id']
                                ]);
                            }else{
                                DB::rollBack();
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Only upload jpg, png, and pdf'
                                ]);
                            }
                        }else{
                            $responseImage = self::uploadImage($file, 'customer_terms');
                            if ($responseImage['status']) {
                                CustomerTerm::create([
                                    'customer_id' => $params['customer_id'],
                                    'ref_term_purchasing_customer_id' => $keyFile,
                                    'filename' => $responseImage['filename'],
                                    'filepath' => '/storage/media/customer_terms/'.$responseImage['month_year_pfx'],
                                    'filetype' => $responseImage['filetype'],
                                    'status' => 1,
                                    'lot_id' => $params['lot_id']
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $customer_lot['customer_id'] = $params['customer_id'];
        $customer_lot['lot_id'] = $params['lot_id'];
        $customer_lot['status'] = 1;
        $customer_lot['booking_date'] = $params['booking_date'];
        $customer_lot['payment_type'] = $params['payment_type'];
        $customer_lot['bank_status'] = 1;

        $insert = self::create($customer_lot);

        if ($insert) {
            $total = 0;

            $exception_empty_account = [];
            $accounting_params = [];
            $credit = [];
            $debit = [];

            if (isset($params['customer_costs']) && count($params['customer_costs']) > 0) {
                foreach($params['customer_costs'] as $key => $customer_cost) {
                    $query_customer_cost = RefTermPurchasingCustomer::where('id', $key)->first();
                    if (empty($query_customer_cost['income_account']) && empty($query_customer_cost['receivable_account']) && $query_customer_cost['account_type'] != 'discount') {
                        $exception_empty_account[] = $query_customer_cost['name'];
                    }
                    CustomerCost::create([
                        'customer_id' => $params['customer_id'],
                        'ref_term_purchasing_customer_id' => $key,
                        'value' => $customer_cost,
                        'status' => 1,
                        'lot_id' => $params['lot_id']
                    ]);
                    $total += $customer_cost;
                    $debit[$query_customer_cost['receivable_account']] = (isset($debit[$query_customer_cost['receivable_account']]) ? $debit[$query_customer_cost['receivable_account']] : 0) + $customer_cost;
                }

                $sales_default_account = DefaultAccount::where('key', 'sales')->value('value');

                $credit[$sales_default_account] = $total;
            }

            if (count($exception_empty_account) > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda Belum Melakukan Setting Akun Pada '.implode(', ', $exception_empty_account).'. <br/><b>Harap Menuju Menu Master Biaya!</b>'
                ]);
            }

            //TODO DISCOUNT
            
            if (count($credit) > 0 && count($debit) > 0) {
                $lot = Lot::where('id', $params['lot_id'])->first();
                $cluster = Cluster::where('id', $lot['cluster_id'])->first();
                $customer = Customer::where('id', $params['customer_id'])->with('user')->first();

                $accounting_params['ref'] = GlobalHelper::generate('JU');
                $accounting_params['description'] = 'Penjualan Unit '.$cluster['name'].' Blok '.$lot['block'].' No '.$lot['unit_number'].' Pada '.$customer['user']['name'];
                $accounting_params['type'] = 1;
                $accounting_params['date'] = $params['booking_date'];
                $accounting_params['total'] = $total;
                $accounting_params['cluster_id'] = $cluster['id'];
                $accounting_params['details']['debit'] = $debit;
                $accounting_params['details']['credit'] = $credit;
                AccountingJournal::journalPosting($accounting_params);
            }

            if ($request->file('customer_terms')) {
                $allowedfileExtension = ['pdf','jpg','png','docx', 'jpeg', 'txt'];
                $files = $request->file('customer_terms');

                $month_year_pfx = date('mY');
                $path_pfx = 'public/media/customer_terms/'.$month_year_pfx;
                $path = '/storage/'.$path_pfx;

                File::makeDirectory($path, 0777, true, true);

                foreach($files as $keyFile => $file){
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                        $file->move(storage_path('app').'/'.$path_pfx, $filename);

                        CustomerTerm::create([
                            'customer_id' => $params['customer_id'],
                            'ref_term_purchasing_customer_id' => $keyFile,
                            'filename' => $filename,
                            'filepath' => '/storage/media/customer_terms/'.$month_year_pfx,
                            'filetype' => $extension,
                            'status' => 1,
                            'lot_id' => $params['lot_id']
                        ]);

                    } else {
                        DB::rollBack();

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Only upload jpg, png, and pdf'
                        ]);
                    }
                }
            }
        }

        Lot::where('id', $params['lot_id'])->update([
            'lot_status' => 2
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)
                ->addSelect('customers.user_id as user_id')
                ->addSelect('users.name as customer_name')
                ->addSelect('clusters.name as cluster_name')
                ->addSelect('lots.block')
                ->addSelect('lots.unit_number')
                ->addSelect('lots.surface_area')
                ->addSelect('lots.building_area')
                ->leftJoin('customers', 'customers.id', '=', 'customer_lots.customer_id')
                ->leftJoin('users', 'users.id', '=', 'customers.user_id')
                ->leftJoin('lots', 'lots.id', '=', 'customer_lots.lot_id')
                ->leftJoin('clusters', 'clusters.id', '=', 'lots.cluster_id');

        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6, 10])) && isset($session['_cluster_id'])) {
            $qry->where('lots.cluster_id', $session['_cluster_id']);
        }

        $totalFiltered = $qry->count();

        if (empty($search)) {

            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }

        } else {
            foreach (array_values(self::mapSchema()) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['alias'].' LIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema())) == ($key + 1)) {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\')');
                } else {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\'');
                }
            }

            $totalFiltered = $qry->count();

            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        return [
            'data' => $qry->get(),
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }

    public static function bookingLotBySession()
    {
        $session = [
            '_login' => session()->get('_login'),
            '_id' => session()->get('_id'),
            '_name' => session()->get('_name'),
            '_email' => session()->get('_email'),
            '_username' => session()->get('_username'),
            '_phone' => session()->get('_phone'),
            '_role_id' => session()->get('_role_id'),
            '_role_name' => session()->get('_role_name'),
            '_cluster_id' => session()->get('_cluster_id')
        ];

        $qry = self::select(['lots.id', 'clusters.id as cluster_id', 'customers.id as customer_id', 'clusters.name', 'lots.block', 'lots.unit_number', 'users.name as fullname'])
                ->join('lots', 'customer_lots.lot_id', '=', 'lots.id')
                ->join('clusters', 'clusters.id', '=', 'lots.cluster_id')
                ->join('customers', 'customers.id', '=', 'customer_lots.customer_id')
                ->join('users', 'users.id', '=', 'customers.user_id');

        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6, 10])) && isset($session['_cluster_id'])) {
            $qry->where('lots.cluster_id', $session['_cluster_id']);
        }

        return $qry->get();
    }

    public function generalStatus(){
        return $this->belongsTo(RefGeneralStatuses::class, 'bank_status');
    }

    public function customer_costs()
    {
        return $this->hasMany(CustomerCost::class);
    }
}
