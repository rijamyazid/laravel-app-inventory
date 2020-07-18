<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = ['id', 'uuid','filename', 'folder_id', 'created_by'];

    public function admin(){
        return $this->belongsTo('App\Admin', 'created_by', 'username');
    }

    public function folder(){
        return $this->belongsTo('App\Folder');
    }
}
