<?php

namespace App\Http\Controllers;

use App\Helper\FieldHelper;
use App\Helper\ModuleHelper;
use App\Helper\PicklistHelper;
use App\Models\Block;
use App\Models\Field;
use App\Models\Picklist;
use App\Models\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\Stmt\TryCatch;

class FieldController extends Controller
{
    public function index()
    {
        return view("content.admin.field");
    }
    public function save(Request $request)
    {
        $model = new Block;
        $model->name = $request->get('name');
        $model->tabid = $request->get('module');
        $model->save();
        return Block::where('tabid', $request->get('module'))->get();
    }

    public function block_list($module)
    {
        $output = array();
        $models = Block::where('tabid', $module)->orderBy('sort')->get();
        foreach ($models as $model) {
            $data = array(
                "id" => $model->id,
                "name" => $model->name
            );
            $field_models = Field::where('tabid', $module)->where('blockid', $model->id)->orderBy('sequence')->get();
            $data["fields"] = $field_models;
            $output[] = $data;
        }
        return $output;
    }

    public function save_field(Request $request)
    {
        $tabmodel = Tab::find($request->tabid);
        $module = strtolower($tabmodel->name);
        $table = ModuleHelper::getModuleTable($module);
        $columnname =  FieldHelper::trimSymbol(strtolower(str_replace(" ", "_", $request->label)));
        $datatype = $request->datatype;
        $datatype_ = "";
        switch ($datatype) {
            case 'text':
            case 'picklist':
                $datatype_ = "varchar";
                break;
            case 'integer':
                $datatype_ = "int";
                break;
            case 'textarea':
                $datatype_ = "text";
                break;
            default:
                $datatype_ = $datatype;
                break;
        }

        if ($request->id == "") {
            $default = "";
            $length = "";
            if ($request->default_value != "") {
                $default = " DEFAULT '" . $request->default_value . "' ";
            }
            if ($datatype_ == "varchar" || $datatype_ == "int") {
                $length .= "(200)";
            } else if ($datatype_ == "decimal") {
                $length .= "(50," . $request->decimals . ")";
            }
            try {
                $add_table = "ALTER TABLE `$table` ADD `$columnname` $datatype_$length $default";
                DB::statement($add_table);
                $model = new Field;
                $sequnce = Field::where('tabid', $request->tabid)
                    ->where('blockid', $request->block_id)
                    ->max('sequence') + 1;
                $model->tabid = $request->tabid;
                $model->label = $request->label;
                $model->columnname = $columnname;
                $model->type = $datatype;
                $model->datatype = $datatype_;
                $model->default = $request->default_value;
                $model->picklist_value = json_encode($request->picklist);
                $model->decimals = $request->datatype == "decimal" ? $request->decimals : "";
                $model->blockid = $request->block_id;
                $model->mandatory = $request->mandatory;
                $model->column = $request->column;
                $model->sequence = $sequnce;
                $model->table = $table;
                if ($model->save()) {
                    // if ($datatype == "picklist") {

                    //     if (count($request->picklist) > 0) {
                    //         $sequence = 1;
                    //         foreach ($request->picklist as $picklist) {
                    //             $picklist_model = new Picklist;
                    //             $picklist_model->tabid = $request->tabid;
                    //             $picklist_model->fieldid = $model->id;
                    //             $picklist_model->name = $picklist;
                    //             $picklist_model->sequence = $sequence;
                    //             $sequence++;
                    //             $picklist_model->save();
                    //         }
                    //     }
                    // }
                }
                return $model;
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(["error" => $e->getMessage()], 404);
            }
        } else {
            $model = Field::find($request->id);
            $model->label = $request->label;
            $model->default = $request->default_value;
            $model->mandatory = $request->mandatory;
            $model->column = $request->column;
            $model->picklist_value = json_encode($request->picklist);
            $model->save();
        }
    }
}
