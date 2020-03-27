<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\HybridRelations;


class Quote extends Eloquent
{
    use HybridRelations;

    protected $connection = 'mongodb';
    protected $collection = 'quotes';
    protected $primarykey = '_id';
    public $timestamps = true;

    public function generate(){
        $quotes = Quote::where('lost','<',3)->orderBy('view')->limit(2)->get();
        return $quotes;
    }
}
