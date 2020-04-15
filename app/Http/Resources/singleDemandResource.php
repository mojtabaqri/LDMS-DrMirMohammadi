<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class singleDemandResource extends JsonResource
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
                'reply'=>$this->replies!= null ? $this->replies->text : 'پاسخ داده نشده!',
                'adminReply'=>$this->replies!= null ? $this->replies->admin_id : 'بدون پاسخ',
                'title'=>$this->title,
                'content'=>$this->content,
            ];

    }
}
