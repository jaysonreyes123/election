<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BarangayMapController extends Controller
{
    //

    public function index()
    {
        return view('content.barangay_map');
    }
    public function upload(Request $request)
    {

        $zip = new ZipArchive;
        $path = base_path() . "/public/qgis";
        //remove existing folder
        $this->rrmdir($path);

        //check if the qgis folder is exist in public folder
        if (!file_exists($path)) {
            Storage::makeDirectory($path, 0777, true, true);
        }

        $zip->open($request->file('files')->getPathName());
        $zip->extractTo($path);
        $zip->close();
        $scandir = array_diff(scandir($path), array('..', '.'));
        foreach ($scandir as $f) {
            chmod($path . "/" . $f, 0777);
        }
        return redirect()->back();
    }
    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                echo $object . "<br>";
                if ($object != '.' && $object != '..') {
                    if (filetype($dir . '/' . $object) == 'dir') {
                        $this->rrmdir($dir . '/' . $object);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
