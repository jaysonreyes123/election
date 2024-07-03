<?php

namespace App\Helper;

use App\Models\Field;


class FieldHelper
{

    public static function getColumn($module_id)
    {
        $model = Field::where('tabid', $module_id)->where('column', 1)->where('presence',1)->get();
        return $model;
    }
    public static function getField($moduleid, $block)
    {
        $model = Field::where('tabid', $moduleid)
            ->where('blockid', $block)
            ->where('presence',1)
            ->orderBy('sequence')
            ->get();
        return $model;
    }
    public static function getfieldModule($moduleid)
    {
        $model = Field::where('tabid', $moduleid)
            ->where('presence',1)
            ->get();
        return $model;
    }
    public static function getSingleFieldModule($moduleid, $columnname)
    {
        $model = Field::where('tabid', $moduleid)
            ->where('columnname', $columnname)
            ->where('presence',1)
            ->first();
        return $model;
    }

    public static function trimSymbol($field)
    {
        return preg_replace('/[^A-Za-z0-9_]/', '', $field);
    }
}
