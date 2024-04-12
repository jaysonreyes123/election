<?php

namespace App\Helper;

use App\Models\Block;

class BlockHelper
{
    public static function getBlockModule($module)
    {
        $model = Block::where('tabid', $module)->get();
        return $model;
    }
}
