<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tabs')->insert([
            [
                "name" => "Voters",
                "label" => "Voters",
                "status" => 1,
                "sort"  => 1,
            ],
            [
                "name" => "Barangays",
                "label" => "Barangays",
                "status" => 1,
                "sort" => 2
            ],
            [
                "name" => "Precinct",
                "label" => "Precinct",
                "status" => 1,
                "sort"   => 3
            ]
        ]);
    }
}
