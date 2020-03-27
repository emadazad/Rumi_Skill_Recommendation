<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\HybridRelations;


class Post extends Eloquent
{
    use HybridRelations;

    protected $connection = 'mongodb';
    protected $collection = 'posts';
    protected $primarykey = '_id';
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function skill(){
        return $this->belongsTo('App\Skill','skill_id','id');
    }
}
