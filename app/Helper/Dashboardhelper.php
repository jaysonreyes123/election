<?php
namespace App\Helper;
class Dashboardhelper{
    public static function  dashboardQuery($module,$table,$columnanme,$type,$filter){
        if($type == "count"){
            $columnanme = ModuleHelper::getModuleKey($module);
        }
        $query = " SELECT $type($columnanme) as 'widget' from $table $filter  ";
        return $query;
    }
    public static function Filter($table, $filters)
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
}