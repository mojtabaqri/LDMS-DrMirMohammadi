<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function downloadFile(Request $request)
    {
        $path=$request->file;
        if(Storage::exists($path))
        {
            $file=Storage::get($path);
            $type=Storage::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
        abort(404);
    }
}
