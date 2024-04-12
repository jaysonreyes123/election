<?php

namespace App\Helper;

class ReportHelper
{
    public static function queryColumn($table, $column)
    {
        $columns = explode(",", $column);
        $new_column = "";
        foreach ($columns as $key => $col) {
            $new_column .= "$table.`$col`,";
        }
        $new_column = rtrim($new_column, ",");
        return $new_column;
    }

    public static function reportQuery($table, $column, $limit = "")
    {
        $query = " SELECT $column from $table  ";

        if ($limit != "") {
            $query .= " limit $limit";
        }
        return $query;
    }
}
