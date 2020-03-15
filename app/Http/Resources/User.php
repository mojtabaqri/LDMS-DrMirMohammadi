<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  Hekmatinasser\Verta\Verta;
class User extends JsonResource
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
            'email'=>$this->email,
            'name'=>$this->name,
            'photo'=>$this->profiles->photo_path,
            'email_verified_at'=>Verta::instance($this->email_verified_at)->format('Y/m/d  |  H:i:s'),
            'updated_at'=>Verta::instance($this->updated_at)->format('Y/m/d  |   H:i:s'),
        ];
    }
}
