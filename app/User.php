<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $incrementing = false;
    protected $table='user';
    protected $fillable = ['user_username', 'user_password', 'user_name', 'bidang_id'];

    public function bidang(){
        return $this->belongsTo('App\Bidang', 'bidang_id', 'id');
    }

    public function folders(){
        return $this->hasMany('App\Folder');
    }

    public function files(){
        return $this->hasMany('App\File');
    }
}
