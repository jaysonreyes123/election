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
    public static function reportFilter($table, $filters)
    {
        $condition = "";

        if (count($filters) > 0) {
            $and = "(";
            $or = "(";
            foreach ($filters as $key => $filter) {
                $operator = "";
                $filter_value = "";
                switch ($filter->filter_type) {
                    case 'equals':
                        $operator = '=';
                        $filter_value = "'$filter->filter_value'";
                        break;
                    case 'not equal to':
                        $operator = '<>';
                        $filter_value = "'$filter->filter_value'";
                        break;
                    case 'contains':
                        $operator = 'like';
                        $filter_value = "'%$filter->filter_value%'";
                        break;
                    case 'is empty':
                        $operator = 'is null';
                        $filter_value = "";
                        break;
                    case 'is not empty':
                        $operator = 'is not null';
                        $filter_value = "";
                        break;
                }

                if ($filter->operator == "and") {
                    $and .= " `$table`.`$filter->columnname` $operator $filter_value and ";
                } else {
                    $or .= " `$table`.`$filter->columnname` $operator $filter_value or ";
                }
            }
            $and = rtrim($and, " and");
            $or = rtrim($or, " or");
            $and .= ")";
            $or .= ")";
            $and == "()" ? $and = "" : $and = $and;
            $or == "()" ? $or = "" : $or = $or;
            $and_between = $and != "" && $or != "" ? "and" : "";
            $condition = " where $and $and_between $or";
        }
        return $condition;
    }

    public static function reportQuery($table, $column, $filter, $limit = "")
    {
        $query = " SELECT $column from $table $filter  ";

        if ($limit != "") {
            $query .= " limit $limit";
        }
        return $query;
    }
}
