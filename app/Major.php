<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Major extends Model
{
	public $primarykey = 'id';
	
    public function users(){
        return $this ->hasMany('App\User');
    }
}
