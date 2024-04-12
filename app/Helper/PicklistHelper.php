<?php

namespace App\Helper;

use App\Models\Picklist;

class PicklistHelper
{
    public static function getPicklist($module, $fieldid)
    {
        $model = Picklist::where("tabid", $module)
            ->where("fieldid", $fieldid)
            ->orderBy('sequence')
            ->get();
        return $model;
    }
    public static function getPicklistArray($module, $fieldid)
    {
        $models = Picklist::where("tabid", $module)
            ->where("fieldid", $fieldid)
            ->orderBy('sequence')
            ->get();
        $picklist = array();
        foreach ($models as $model) {
            $picklist[] = $model->name;
        }
        return $picklist;
    }
}
