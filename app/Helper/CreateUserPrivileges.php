<?php

namespace App\Helper;

use App\Models\UserPrivilege;

class CreateUserPrivileges
{
    public static function save($tabid, $roleid, $create, $edit, $delete, $import)
    {
        $user_privileges_model = new UserPrivilege;
        $user_privileges_model->tabid   = $tabid;
        $user_privileges_model->roleid  = $roleid;
        $user_privileges_model->create  = $create;
        $user_privileges_model->edit    = $edit;
        $user_privileges_model->delete  = $delete;
        $user_privileges_model->import  = $import;
        $user_privileges_model->save();
    }
}
