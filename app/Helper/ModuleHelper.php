<?php

namespace App\Helper;

use App\Models\Tab;

class ModuleHelper
{
    public static function getModuleID($module)
    {
        $model = Tab::where("name", $module)->first();
        return $model->id;
    }
    public static function getModuleName($id)
    {
        $model = Tab::where("id", $id)->first();
        return $model->name;
    }

    public static function getModuleKey($module_name)
    {
        return  FieldHelper::trimSymbol(str_replace(" ", "_", strtolower($module_name)) . "id");
    }
    public static function getModuleTable($module_name)
    {
        return FieldHelper::trimSymbol(str_replace(" ", "_", strtolower($module_name)) . "_table");
    }
}
