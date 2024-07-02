<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    //

    public function upload(Request $request){
        if($request->hasFile('files')){
            $file = $request->file('files');
            $filepath = asset('attachment');
            $filename = $file->getClientOriginalName();
            $file->move('attachment',$filename);
        }
    }
}
