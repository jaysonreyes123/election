<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Field;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    //
    public function sequence(Request $request)
    {

        foreach ($request->block as $k => $block) {
            if (isset($block["fields"])) {
                $blockid = $block["blockid"];
                $fields = $block["fields"];
                $block_model = Block::where('id', $blockid)->first();
                $block_model->sort = $k + 1;
                $block_model->save();
                foreach ($fields as $key => $val) {
                    $field_model = Field::where('id', $val)->first();
                    $field_model->blockid = $blockid;
                    $field_model->sequence = $key + 1;
                    $field_model->save();
                }
            }
        }
    }
}
