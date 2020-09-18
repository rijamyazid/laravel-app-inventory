<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = ['id', 'file_uuid','file_name', 'file_status', 'file_flag', 'admin_id', 'folder_id'];

    public function admin(){
        return $this->belongsTo('App\Admin', 'created_by', 'username');
    }

    public function folder(){
        return $this->belongsTo('App\Folder');
    }
}
