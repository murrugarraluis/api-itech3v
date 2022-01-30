<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $file_name = $file->getClientOriginalName();
            $file_name = pathinfo($file_name, PATHINFO_FILENAME);

            $name = str_replace(" ", "_", $file_name);
            $extension = $file->getClientOriginalExtension();

            $picture = date('His') . '-' . $name . '-' . $extension;

            $file->move(public_path('Files/'), $picture);
            return response()->json(['message' => 'File Upload']);
        }
        return response()->json(['message' => 'File Not Upload']);
    }
}
