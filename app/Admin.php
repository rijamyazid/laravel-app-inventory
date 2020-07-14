<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table='admin';
    protected $primaryKey = 'username';

    public function folders(){
        return $this->hasMany('App\Folder', 'created_by', 'username');
    }

    public function role(){
        return $this->belongsTo('App\Role');
    }
}
