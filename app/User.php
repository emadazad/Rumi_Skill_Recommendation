<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class User extends Authenticatable
{
    use Notifiable;
    use HybridRelations;

    protected $connection = 'mysql';

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password','major_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts(){
        return $this->hasMany('App\Post','user_id','id');
    }

    public function recommendations(){
        return $this->hasMany('App\Post');
    }
    
    public function skills(){
        return $this->belongsToMany('App\Skill', 'skills_users','user_id', 'skill_id');
    }
    public function major(){
        return $this->belongsTo('App\Major');
    }
}
