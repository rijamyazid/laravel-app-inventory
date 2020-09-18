<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $table='bidang';
    protected $fillable = ['bidang_name', 'bidang_prefix'];

    public function admin(){
        return $this->hasMany('App\Admin');
    }
}
