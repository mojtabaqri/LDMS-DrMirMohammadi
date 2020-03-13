<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'id','admin_id','text','demand_id',
    ];
    public function demands()
    {
        return $this->belongsTo(Demand::class,'demand_id');
    }
}
