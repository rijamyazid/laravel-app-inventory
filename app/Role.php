<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table='roles';
    protected $fillable = ['role', 'role_prefix'];

    public function admin(){
        return $this->hasMany('App\Admin');
    }

}
