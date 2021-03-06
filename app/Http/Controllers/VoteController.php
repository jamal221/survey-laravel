<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class VoteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $guestId = $request->guest_id;
        $question = Question::inRandomOrder()
            ->whereDoesntHave('votes', function(Builder $query) use ($guestId) {
                $query->where('guest_id', $guestId);
            })
            ->with('options')
            ->first();
        return view('home', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $options = $request['options'];
        $guest_id = $request->guest_id;
        $vote = null;
        foreach ($options as $option_id) {
            Vote::create(compact('option_id', 'guest_id'));
            Option::find($option_id)->increment('votes');
        }
    }    
}
