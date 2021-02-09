<?php

use App\Http\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Superadmin', 'Marketing', 'Project', 'Manager', 'Warehouse', 'Purchasing', 
        'Manager Marketing', 'Manager Project', 'Manager Warehouse', 'Customer'];

        for ($i=0; $i < count($roles); $i++) { 
            if ($i <= 9 ) {
                Role::create([
                    'id' => $i+1,
                    'name' => $roles[$i],
                    'description' => $roles[$i],
                    'guard_name' => 'web',
                ]);
            }else{
                Role::create([
                    'id' => 99,
                    'name' => $roles[$i],
                    'description' => $roles[$i],
                    'guard_name' => 'web',
                ]);
            }
            
        }
    }
}
