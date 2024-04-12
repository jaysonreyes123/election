<?php

namespace App\Helper;

use App\Models\UserPrivilege;
use Illuminate\Support\Facades\Auth;

class UserPrivilegesHelper
{
    public static function getUserPrivileges($module_id)
    {
        $user_privileges = UserPrivilege::where('tabid', $module_id)
            ->where('roleid', Auth::user()->role)
            ->first();
        return $user_privileges;
    }
}
