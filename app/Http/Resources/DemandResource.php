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
            'title'=>mb_substr($this->title,0,20,'UTF-8'),
            'content'=>mb_substr($this->content,0,20,'UTF-8'),
            'reply'=>$this->replies != null ? mb_substr($this->replies->text,0,20,'UTF-8') : 'پاسخ داده نشده!',
            'username'=>$this->users->name,
            'userphone'=>$this->users->phone,
            'userprofile'=>$this->users->profiles->photo_path,
            'updated_at'=>Verta::instance($this->updated_at)->format('Y/m/d  |   H:i:s'),
        ];
    }

}
