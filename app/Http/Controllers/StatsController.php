<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ServerStatus;
use App\User;

class StatsController extends Controller
{
    public static function updateUserStats () {

        $server_info = DB::table('server_statuses')
        ->where('name','=','leaderboard_updated')
        ->first();
        
        $created_at = strtotime($server_info->updated_at);
        $difference = time() - $created_at;

        //don't update more than once per ten minutes
        if ($difference > 600) {
            self::calculateAndSaveStats();
        }
    }

    public static function calculateAndSaveStats() {
        
        $userStats = DB::select('SELECT user_id, name, sum(texts.length) as races_len, count(*) as races, sum(races.mistakes) as mistakes, sum(texts.length)/sum(races.time_taken)*12 AS WPM, sum(races.time_taken) as time_taken
        FROM races
        INNER JOIN texts ON races.text_id=texts.id
        INNER JOIN users ON races.user_id=users.id
        GROUP BY user_id
        ORDER BY WPM DESC'
        );
        $rank = 1;
        foreach ($userStats as $stat) {
            $date1 = date('Y-m-d G:i:s', time());
            $user = User::find($stat->user_id);
            $user->mistakes         = $stat->mistakes;
            if ($stat->races > 25) {
                $user->rank         = $rank++;
            } else {
                $user->rank         = 1e9-1;
            }
            $user->races            = $stat->races;
            $user->time_taken       = $stat->time_taken;
            $user->races_len        = $stat->races_len;
            $user->stats_updated    = time();
            $user->updated_at       = $date1;
            $user->save();
        }

        //insert here the code to calculate the longest marathon run and the longest perfect streak by characters typed

        $leaderboard_updated = ServerStatus::where('name','=','leaderboard_updated')->first();
        $leaderboard_updated->updated_at = date('Y-m-d G:i:s', time());
        $leaderboard_updated->save();

    }
}
