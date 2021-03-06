<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ServerStatus;
use App\User;
use App\Race;
use App\Text;

class StatsController extends Controller
{
    public static function updateUserStats () {

        $server_info = DB::table('server_statuses')
        ->where('name','=','leaderboard_updated')
        ->first();
        
        $created_at = strtotime($server_info->updated_at);
        $difference = time() - $created_at;

        //don't update more than once per ten minutes because I think that this is an expensive operation
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
        $texts = Text::all();
        $user25RacesCount = 0;
        foreach ($userStats as $stat) {
            $date1 = date('Y-m-d G:i:s', time());
            $user = User::find($stat->user_id);
            $user->mistakes         = $stat->mistakes;
            if ($stat->races > 25) {

                $user25RacesCount++;
                $user->rank         = $rank++;

                //calculate the longest marathon run and the longest perfect streak by characters typed
                $userRaces = Race::where('user_id', '=', $stat->user_id)
                ->get()->sortBy('created_at');
                $lastRace = null;
                $currentRace = null;
                $currentMarathonLen = 0; 
                $maxMarathonLen = 0;
                $currentPerfectStreak = 0;
                $maxPerfectStreak = 0;
                $last25_wpm_len = 0;
                $last25_wpm_time = 0.00001;

                //calculate max perfect streak and marathon
                while ($currentRace = $userRaces->shift()) {

                    if ($lastRace) {
                        //longest marathon run
                        if ( strtotime($currentRace->created_at)-120 < strtotime($lastRace->created_at) ) {
                            //currentRace and lastRace were completed shortly after each other.
                            if ($currentMarathonLen == 0) {
                                $currentMarathonLen =  $texts[$currentRace->text_id-1]->length;
                                $currentMarathonLen += $texts[$lastRace->text_id-1]->length;
                            } else {
                                $currentMarathonLen += $texts[$currentRace->text_id-1]->length;
                            }

                        } else {
                            //currentRace and lastRace were NOT completed shortly after each other
                            $currentMarathonLen = 0;

                        }

                        //longest perfect streak
                        if ( $currentRace->mistakes == 0 && $lastRace->mistakes == 0) {
                            if ($currentPerfectStreak == 0) {
                                $currentPerfectStreak =  $texts[$currentRace->text_id-1]->length;
                                $currentPerfectStreak += $texts[$lastRace->text_id-1]->length;
                            } else {
                                $currentPerfectStreak += $texts[$currentRace->text_id-1]->length;
                            }
                        } else {
                            $currentPerfectStreak = 0;
                        }
                        
                        if ($currentMarathonLen > $maxMarathonLen) {
                            $maxMarathonLen = $currentMarathonLen;
                        }
                        if ($currentPerfectStreak > $maxPerfectStreak) {
                            $maxPerfectStreak = $currentPerfectStreak;
                        }

                        if (count($userRaces) <= 25) {
                            $last25_wpm_len += $texts[$currentRace->text_id-1]->length;
                            $last25_wpm_time += $currentRace->time_taken;
                        }

                        $lastRace = $currentRace;

                    } else {

                        $lastRace = $currentRace;

                    }
                }
                $user->last25_wpm               = round($last25_wpm_len/$last25_wpm_time*12,1);
                $user->longest_perfect_streak   = $maxPerfectStreak;
                $user->longest_marathon         = $maxMarathonLen;

            } else {

                $user->rank         = 1e7-1;

            }
            $user->races                    = $stat->races;
            $user->time_taken               = $stat->time_taken;
            $user->races_len                = $stat->races_len;
            $user->stats_updated            = time();
            $user->updated_at               = $date1;
            $user->save();
        }

        $leaderboard_updated = ServerStatus::where('name','=','leaderboard_updated')->first();
        $leaderboard_updated->updated_at = date('Y-m-d G:i:s', time());
        $leaderboard_updated->save();

        $users_with_25_races = ServerStatus::where('name', '=', 'users_with_25_races')->first();
        $users_with_25_races->val_int = $user25RacesCount;
        $users_with_25_races->updated_at = date('Y-m-d G:i:s', time());
        $users_with_25_races->save();

    }
}
