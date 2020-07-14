<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'folders';
    protected $incrementing = false;
    protected $fillable = ['name', 'url_path', 'parent_path', 'created_by', 'folder_role'];

    public function admin(){
        return $this->belongsTo('App\Admin', 'created_by', 'username');
    }

}
