<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

class FileController extends Controller
{
    /** Process file uploaded */
    public function uploadFilePost(Request $request){
        $request->validate([
            'fileToUpload' => 'required|file|max:1024|mimes:csv,txt',
        ]);
        $fileName = 'csvFile.csv';
        $request->fileToUpload->storeAs('csvFiles',$fileName);
        if($request->hasFile('fileToUpload')){
            File::processCsv(storage_path().'\app\csvFiles\csvFile.csv');
            return back()->with('success','Upload Sucessfull.');
        }else{
            return back();
        }
    }
}
