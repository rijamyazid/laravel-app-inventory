<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'folders';
    protected $fillable = ['folder_name', 'url_path', 'parent_path', 'folder_status', 'folder_flag', 'admin_id', 'bidang_id'];

    public function admin(){
        return $this->belongsTo('App\Admin', 'created_by', 'username');
    }

    public function files(){
        return $this->hasMany('App\File');
    }
}
