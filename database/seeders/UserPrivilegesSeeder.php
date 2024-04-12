<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tab;
use App\Models\UserPrivilege;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPrivilegesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $modules = Tab::all();
        $roles = Role::all();

        foreach ($modules as $module) {
            foreach ($roles as $role) {
                $user_privileges = new UserPrivilege;
                $user_privileges->tabid = $module->id;
                $user_privileges->roleid = $role->id;
                $user_privileges->create = 1;
                $user_privileges->edit = 1;
                $user_privileges->delete = 1;
                $user_privileges->import = 1;
                $user_privileges->save();
            }
        }
    }
}
