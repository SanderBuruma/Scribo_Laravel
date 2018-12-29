<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function updateLeaderboard () {
        $data = DB::table('server_status')
        ->where('name','=','leaderboard_updated')
        ->first();
        return $data;
    }
}
