<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Tab;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $models = Tab::all();
        foreach ($models as $model) {
            $block_model = new Block;
            $block_model->name = $model->name . " Details";
            $block_model->tabid = $model->id;
            $block_model->save();
        }

        $block_model = new Block;
        $block_model->id = 5;
        $block_model->name = "Demographics";
        $block_model->tabid = 1;
        $block_model->save();
    }
}
