<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'id','demand_id','file_directory',
    ];
    public function demands()
    {
        return $this->belongsTo(Demand::class);
    }

}
