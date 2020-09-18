<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public $incrementing = false;
    protected $table='admin';
    protected $fillable = ['admin_username', 'admin_password', 'admin_name', 'bidang_id'];

    public function bidang(){
        return $this->belongsTo('App\Bidang', 'bidang_id', 'id');
    }

    public function folders(){
        return $this->hasMany('App\Folder', 'folder_id', 'id');
    }

    public function files(){
        return $this->hasMany('App\File', 'created_by', 'username');
    }
}
