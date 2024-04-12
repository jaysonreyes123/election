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
            [
                "table" => "voters_table",
                "columnname" => "no",
                "tabid" => 1,
                "label" => "No.",
                "type" => "integer",
                "datatype" => "int",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
            [
                "table" => "voters_table",
                "columnname" => "legend",
                "tabid" => 1,
                "label" => "Legend",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
            [
                "table" => "voters_table",
                "columnname" => "voters_name",
                "tabid" => 1,
                "label" => "Voter's Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
            [
                "table" => "voters_table",
                "columnname" => "voters_address",
                "tabid" => 1,
                "label" => "Voter's Address",
                "type" => "textarea",
                "datatype" => "text",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
            [
                "table" => "voters_table",
                "columnname" => "precinct",
                "tabid" => 1,
                "label" => "Precinct #",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
            [
                "table" => "voters_table",
                "columnname" => "barangay",
                "tabid" => 1,
                "label" => "barangay",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
            [
                "table" => "voters_table",
                "columnname" => "vote",
                "tabid" => 1,
                "label" => "vote",
                "type" => "integer",
                "datatype" => "int",
                "blockid" => 1,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],

            //end voters


            //barangay
            [
                "table" => "barangays_table",
                "columnname" => "name",
                "tabid" => 2,
                "label" => "Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 2,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
            [
                "table" => "barangays_table",
                "columnname" => "city",
                "tabid" => 2,
                "label" => "City",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 2,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],

            //end barangay


            //precinct
            [
                "table" => "precinc_table",
                "columnname" => "name",
                "tabid" => 3,
                "label" => "Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],

            //precinct
            [
                "table" => "precinc_table",
                "columnname" => "barangay_name",
                "tabid" => 3,
                "label" => "Barangay Name",
                "type" => "text",
                "datatype" => "varchar",
                "blockid" => 3,
                "mandatory" => 1,
                "column" => 1,
                "editable" => 0
            ],
        ]);
    }
}
