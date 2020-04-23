<?php

namespace App\Http\Resources;

use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'text'=>$this->text,
            'username'=>$this->users->name,
            'userphone'=>$this->users->phone,
            'userprofile'=>$this->users->profiles->photo_path,
            'state'=>$this->state,
            'file_directory'=>$this->file_directory,
            'updated_at'=>Verta::instance($this->updated_at)->format('Y/m/d  |   H:i:s'),
        ];
    }
}
