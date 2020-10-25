<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $fillable = ['log_type', 'keterangan', 'user_id', 'bidang_id'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
