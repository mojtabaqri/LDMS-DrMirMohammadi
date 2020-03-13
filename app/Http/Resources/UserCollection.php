<?php

namespace App\Http\Resources;
use App\Http\Resources\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use mysql_xdevapi\Collection;


class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $collects='App\Http\Resources\User';
    public function toArray($request)
    {
        return [
            'data'=>$this->collection,
            'total'=>$this->total(),
            'count'=>$this->count(),
            'per_page'=>$this->perPage(),
            'current_page'=>$this->currentPage(),
            'total_page'=>$this->lastPage(),

        ];
    }
}
