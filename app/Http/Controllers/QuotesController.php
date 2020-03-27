<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quote;
use Auth;
use DB;

class QuotesController extends Controller
{
    public function quote(){
        $bests = Quote::where('lost','<',3)->orderBy('winrate')->get();
        if($bests->count() < 4){
            return view('quote')->with(['bests'=>$bests]);
        }
        else{
            $test = new Quote();
            $quotes = $test->generate();
            return view('quote')->with(['quotes'=>$quotes]);
        }
    }
    public function update($id1 , $id2){
        $first = Quote::find($id1);
        $first->increment('score');
        $first->increment('view');
        $first->winrate = ($first->score)/($first->view);
        $first->save();

        $second = Quote::find($id2);
        $second->decrement('score');
        $second->increment('view');
        $second->increment('lost');
        $second->winrate = ($second->score)/($second->view);
        $second->save();

        return $this->quote();
    }
    public function restart(){
        DB::connection('mongodb')->table('quotes')->update(['score'=>0 , 'winrate'=>0, 'lost'=>0, 'view'=> 0]);
        return $this->quote();
    }
}
