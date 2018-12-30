<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\StatsController;
use App\User;
use App\Race;
use App\ServerStatus;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles;
        return view ('user.index')->withUser($user)->withRoles($roles);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        StatsController::updateUserStats();

        $user = User::where('name','=',$slug)->first();
        $users_with_25_races = ServerStatus::where('name', '=', 'users_with_25_races')->first();
        return view('user.show')->withUser($user)->withUserscount($users_with_25_races->val_int);
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
        if ($id == Auth::user()->id) {
            if ($request->req == 1) {
                //non password changes
                if ($request->user()->name == Auth::user()->name) {
                    $this->validate($request, [
                        'city'          => 'nullable|string|min:2|regex:/[ a-zA-Z]+/',
                        'country'       => 'nullable|string|min:2|regex:/[ a-zA-Z]+/',
                    ]);
                } else {
                    $this->validate($request, [
                        'name'          => 'required|string|unique:users|min:2|regex:/[ a-zA-Z0-9]+/',
                        'city'          => 'nullable|string|min:2|regex:/[ a-zA-Z]+/',
                        'country'       => 'nullable|string|min:2|regex:/[ a-zA-Z]+/',
                    ]);
                }
                
                $user = Auth::user();
                $user->name         = $request->name;
                $user->city         = $request->city;
                $user->country      = $request->country;

                $user->save();
                return ["message"=>"Gebruiker opgeslagen!"];

            } else if ($request->req == 2) {

                $user = Auth::user();
                if (Hash::check($request->password_old, $user->password)) {
                    if ($request->password === $request->password_confirmation) {
                        $user->password = Hash::make($request->password);
                        $user->save();
                        return [
                            "message"=>"Password Changed",
                            "failure"=>0,
                        ];
                    } else {
                        return [
                            "message"=>"New passwords did not match...",
                            "failure"=>1,
                    ];
                    }
                } else {
                    return [
                        "message"=>"Wrong old password...",
                        "failure"=>2,
                ];
                }

            }
        } else {
            return [
                "message"=>"authentication userID does not match client submitted user id $id!=".Auth::user()->id,
                "failure"=>3,
            ];
        }
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
