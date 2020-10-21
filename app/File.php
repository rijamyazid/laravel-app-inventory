<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = ['id', 'file_uuid','file_name', 'file_status', 'file_flag', 'file_dl_count','user_id', 'folder_id'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function folder(){
        return $this->belongsTo('App\Folder', 'folder_id', 'id');
    }
}
