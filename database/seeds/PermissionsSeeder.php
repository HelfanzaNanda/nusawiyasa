<?php

use App\Http\Models\Permission\Permissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perms = ['user.index|user.create|user.update|user.edit|user.delete|user.permissions'];
        Artisan::call('permission:create-permission', [
            $perms
        ]);
    }
}
