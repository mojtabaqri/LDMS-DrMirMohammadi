<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  Hekmatinasser\Verta\Verta;

class DemandResource extends JsonResource
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
            'id'=>$this->id,
            'title'=>$this->title,
            'content'=>$this->content,
            'reply'=>$this->replies != null ? $this->replies->text : 'پاسخ داده نشده!',
            'username'=>$this->users->name,
            'userphone'=>$this->users->phone,
            'userprofile'=>$this->users->profiles->photo_path,
            'updated_at'=>Verta::instance($this->updated_at)->format('Y/m/d  |   H:i:s'),
        ];
    }
}
