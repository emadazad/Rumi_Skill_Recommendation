<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\HybridRelations;


class Recommendation extends Eloquent
{
    use HybridRelations;

    protected $connection = 'mongodb';
    protected $collection = 'recommendations';
    protected $primarykey = '_id';
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }
}
