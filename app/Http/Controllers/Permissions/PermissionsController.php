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
        ->join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
        ->groupBy('permissions.id')->get();
        $attrs = [];
        foreach ($perms as $permission) {
            $attrs[explode('-',$permission->name)[0]][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'roles' => explode(',', $permission->roles),
            ];
        }
        $result = collect($attrs)->paginate(2);
        //dd($attrs);
        return view('permissions.permissions', [
            'permissions' => $result
        ]);
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
