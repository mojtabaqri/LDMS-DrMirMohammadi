<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $fillable = [
       'id','title','content','user_id', 'state','tracking','file_directory',
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function replies()
    {
        return $this->hasOne(Reply::class);
    }

}
