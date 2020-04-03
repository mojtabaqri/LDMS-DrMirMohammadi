<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileToken extends Model
{
    public $timestamps=false;
    protected $fillable = [
        'id','token','revoke','user_id','created','expired',
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
