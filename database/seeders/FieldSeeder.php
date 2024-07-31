<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('fields')->insert([
            //voters
            // [
            //     "table" => "voters_table",
            //     "columnname" => "no",
            //     "tabid" => 1,
            //     "label" => "No.",
            //     "type" => "integer",
            //     "datatype" => "int",
            //     "blockid" => 1,
            //     "mandatory" => 1,
            //     "column" => 1,
            //     "editable" => 1
            // ],
            // [
            //     "table" => "voters_table",
            //     "columnname" => "legend",
            //     "tabid" => 1,
            //     "label" => "Legend",
            //     "type" => "text",
            //     "datatype" => "varchar",
            //     "blockid" => 1,
            //     "mandatory" => 1,
            //     "column" => 1,
            //     "editable" => 1
            // ],
            // [
            //     "table" => "voters_table",
            //     "columnname" => "voters_name",
            //     "tabid" => 1,
            //     "label" => "Voter's Name",
            //     "type" => "text",
            //     "datatype" => "varchar",
            //     "blockid" => 1,
            //     "mandatory" => 1,
            //     "column" => 1,
            //     "editable" => 1
            // ],
            // [
            //     "table" => "voters_table",
            //     "columnname" => "voters_address",
            //     "tabid" => 1,
            //     "label" => "Voter's Address",
            //     "type" => "textarea",
            //     "datatype" => "text",
            //     "blockid" => 1,
            //     "mandatory" => 1,
            //     "column" => 1,
            //     "editable" => 1
            // ],
            // [
            //     "table" => "voters_table",
            //     "columnname" => "precinct",
            //     "tabid" => 1,
            //     "label" => "Precinct #",
            //     "type" => "text",
            //     "datatype" => "varchar",
            //     "blockid" => 1,
            //     "mandatory" => 1,
            //     "column" => 1,
            //     "editable" => 1
            // ],
            // [
            //     "table" => "voters_table",
            //     "columnname" => "barangay",
            //     "tabid" => 1,
            //     "label" => "barangay",
            //     "type" => "text",
            //     "datatype" => "varchar",
            //     "blockid" => 1,
            //     "mandatory" => 1,
            //     "column" => 1,
            //     "editable" => 1
            // ],
            // [
            //     "table" => "voters_table",
            //     "columnname" => "vote",
            //     "tabid" => 1,
            //     "label" => "vote",
            //     "type" => "integer",
            //     "datatype" => "int",
            //     "blockid" => 1,
            //     "mandatory" => 1,
            //     "column" => 1,
            //     "editable" => 1
            // ],

            //end voters


            //barangay
            [
                "table" => "barangays_table",
                "columnname" => "name",
                "tabid" => 1,
                "label" => "Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],
            [
                "table" => "barangays_table",
                "columnname" => "city",
                "tabid" => 1,
                "label" => "City",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            //end barangay

            // street
            [
                "table" => "street_table",
                "columnname" => "barangay_name",
                "tabid" => 2,
                "label" => "Barangay",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 2,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "street_table",
                "columnname" => "street_name",
                "tabid" => 2,
                "label" => "Street Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 2,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            // end street

            // leaders

            [
                "table" => "leaders_table",
                "columnname" => "barangay_name",
                "tabid" => 3,
                "label" => "Barangay",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "leaders_table",
                "columnname" => "precinct",
                "tabid" => 3,
                "label" => "Precinct",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "leaders_table",
                "columnname" => "area",
                "tabid" => 3,
                "label" => "Area",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "leaders_table",
                "columnname" => "cluster_leader",
                "tabid" => 3,
                "label" => "Cluster leader",
                "type" => "integer",
                "datatype" => "int",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "leaders_table",
                "columnname" => "barangay_leader",
                "tabid" => 3,
                "label" => "Barangay leader",
                "type" => "integer",
                "datatype" => "int",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "leaders_table",
                "columnname" => "precinct_leader",
                "tabid" => 3,
                "label" => "Precinct leader",
                "type" => "integer",
                "datatype" => "int",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "leaders_table",
                "columnname" => "cell_political_leader",
                "tabid" => 3,
                "label" => "Cell Political leader",
                "type" => "integer",
                "datatype" => "int",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            [
                "table" => "leaders_table",
                "columnname" => "street_leader",
                "tabid" => 3,
                "label" => "Street leader",
                "type" => "integer",
                "datatype" => "int",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],


            // end leaders

            //precinct
            [
                "table" => "precinc_table",
                "columnname" => "name",
                "tabid" => 4,
                "label" => "Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 4,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

            //precinct
            [
                "table" => "precinc_table",
                "columnname" => "barangay_name",
                "tabid" => 4,
                "label" => "Barangay Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 4,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 1
            ],

        ]);
    }
}
