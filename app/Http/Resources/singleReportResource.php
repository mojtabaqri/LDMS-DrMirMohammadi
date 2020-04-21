<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class singleReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attachment=Storage::files($this->file_directory);
            return [
                'attachment'=>$attachment!=null ?$attachment:'nofile',
                'title'=>$this->title,
                'content'=>$this->text,
            ];

    }
}
