<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ServerStatus;

class StatsController extends Controller
{
    public static function updateLeaderboard () {

        $server_info = DB::table('server_statuses')
        ->where('name','=','leaderboard_updated')
        ->first();
        
        $created_at = strtotime($server_info->updated_at);
        $difference = time() - $created_at;

        if ($difference > 600) {
            $leaderboard_updated = ServerStatus::where('name','=','leaderboard_updated')->first();
            $leaderboard_updated->updated_at = date('Y-m-d G:i:s', time());
            $leaderboard_updated->save();
        }
    }
}
