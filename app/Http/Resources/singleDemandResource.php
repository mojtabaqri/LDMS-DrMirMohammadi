<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'attachment'=>$this->files,
            'reply'=>'',
            'title'=>'',
            'content'=>'',
        ];
    }
}
