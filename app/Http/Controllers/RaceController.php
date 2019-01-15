<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Race;
use App\User;

class RaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'speed'	    => 'required|numeric|min:10|max:400',
            'mistakes'	=> 'required|numeric|min:0',
            'text_id'   => 'required|exists:texts,id',
            'time_taken'=> 'required|numeric|min:0',
        ]);
        $race = new Race();
        $race->user_id      = auth()->id()?auth()->id():2;
        $race->text_id      = $request->text_id;
        $race->speed        = $request->speed;
        $race->mistakes     = $request->mistakes;
        $race->time_taken   = $request->time_taken;
        $race->save();

        $sumLen = 0; $sumTime = 0;
        $races = Race::where('races.user_id', '=', auth()->id())
            ->limit(25)
            ->join('texts', 'races.text_id', '=', 'texts.id')
            ->select('races.time_taken', 'races.mistakes', 'texts.length')
            ->orderByDesc('races.created_at')
            ->get();
            
        foreach ($races as $race) {
            $sumTime += $race->time_taken;
            $sumLen  += $race->length;
        }
        $last25_wpm = $sumLen / $sumTime * 12;
        $user = User::find(auth()->id());
        $user->last25_wpm = $last25_wpm;
        $user->races_len += $races[0]->length;
        $user->mistakes += $races[0]->mistakes;
        $user->save();
        return $last25_wpm;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
