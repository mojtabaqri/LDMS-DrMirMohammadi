<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'id','user_id','photo_path',
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
