<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'folders';
    protected $fillable = ['folder_name', 'url_path', 'parent_path', 'folder_status', 'folder_flag', 'user_id', 'bidang_id'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function files(){
        return $this->hasMany('App\File');
    }
}
