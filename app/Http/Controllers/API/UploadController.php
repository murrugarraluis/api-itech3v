<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadFile2(Request $request)
    {
        $file = File::create($request->all());
        $path = $request->file->storeAs('public/files', 'ayuda.pdf');
        $file->file = $path;
        $file->save();
        return response()->json(['message' => 'File Upload']);
    }
    public function getFile(File $file)
    {
        return response()->download(
            public_path(Storage::url($file->file)),
            'ayuda.pdf'
        );
    }
}
