<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'id','state','title','text','file_directory','user_id',
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
