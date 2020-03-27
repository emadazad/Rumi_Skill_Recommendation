<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Skill extends Model
{
	use HybridRelations;
    protected $connection = 'mysql';
    protected $table = 'skills';
    public $primarykey = 'id';
    

    public function users(){
        return $this->belongsToMany('App\User', 'skills_users','skill_id','user_id');
    }

    public function posts(){
        return $this->hasMany('App\Post','skill_id','id');
    }

}
