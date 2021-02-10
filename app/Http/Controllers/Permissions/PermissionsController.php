<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Models\Users;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Models\Permission\Permissions;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    public function index()
    {
        $perms = DB::table('permissions')->select('permissions.*')
        ->addSelect(DB::raw(' GROUP_CONCAT(DISTINCT roles.name) as roles'))
        ->leftJoin('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->leftJoin('roles', 'role_has_permissions.role_id', '=', 'roles.id')
        ->groupBy('permissions.id')->get();

        $headers = ['Main', 'Marketing', 'Project', 'Gudang', 'Purchasing', 'Pengaturan', 'Accounting', 'SLF'];
        $attrs = [];
        foreach ($perms as $permission) {
            if ($permission->name == 'employe' || $permission->name == 'employe-detail') {
                $attrs[$headers[0]][] = $this->showItem($permission);
            }elseif ($permission->name == 'customers' || $permission->name == 'customer-payments'
            || $permission->name == 'clusters' || $permission->name == 'lots'
            || $permission->name == 'booking-page'|| $permission->name == 'spk-project') {
                $attrs[$headers[1]][] = $this->showItem($permission);
            }elseif ($permission->name == 'customer-confirmation' || $permission->name == 'work-agreement'
            || $permission->name == 'rap' || $permission->name == 'request-material'
            || $permission->name == 'development-progress') {
                $attrs[$headers[2]][] = $this->showItem($permission);
            }elseif ($permission->name == 'inventory' || $permission->name == 'unit'
            || $permission->name == 'supplier' || $permission->name == 'inventory-history'
            || $permission->name == 'receipt-of-goods-request' || $permission->name == 'receipt-of-goods'
            || $permission->name == 'delivery-order' || $permission->name == 'report-used-inventory'
            || $permission->name == 'report-stock-opname') {
                $attrs[$headers[3]][] = $this->showItem($permission);
            }elseif ($permission->name == 'purchase-order' || $permission->name == 'report-inventory-purchase'
            || $permission->name == 'report-outstanding-po') {
                $attrs[$headers[4]][] = $this->showItem($permission);
            }elseif ($permission->name == 'user' || $permission->name == 'user-permissions') {
                $attrs[$headers[5]][] = $this->showItem($permission);
            }elseif ($permission->name == 'debt' || $permission->name == 'accounting-master'
            || $permission->name == 'accounting-general-ledger' || $permission->name == 'accounting-ledger'
            || $permission->name == 'accounting-profit-loss' || $permission->name == 'accounting-balance-sheet') {
                $attrs[$headers[6]][] = $this->showItem($permission);
            }elseif ($permission->name == 'slf-template') {
                $attrs[$headers[7]][] = $this->showItem($permission);
            }else{
                $attrs[explode('-',$permission->name)[0]][] = $this->showItem($permission);
            }
        }
        //dd($attrs);
        $result = collect($attrs)->paginate(2);
        //dd($attrs);
        return view('setting.permissions.permissions', [
            'permissions' => $result
        ]);
    }

    private function showItem($permission)
    {
        return  [
            'id' => $permission->id,
            'name' => $permission->name,
            'guard_name' => $permission->guard_name,
            'roles' => explode(',', $permission->roles),
        ];
    }

    public function update(Request $request)
    {
        $role = Role::findByName($request->role);
        $request->bool == "true" ?  $role->givePermissionTo($request->perm) : $role->revokePermissionTo($request->perm);
        return [
            'status' => true,
            'msg' => 'berhasil'
        ];
    }
}
