<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public $incrementing = false;
    protected $table='admin';
    protected $primaryKey = 'username';

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function folders(){
        return $this->hasMany('App\Folder', 'created_by', 'username');
    }

    public function files(){
        return $this->hasMany('App\File', 'created_by', 'username');
    }
}
