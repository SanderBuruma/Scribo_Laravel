<?php

use Illuminate\Database\Seeder;
use App\Text;
use App\User;
use App\Http\Controllers\StatsController;

class UserRacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $count = 0; $races = [];
        $blocks = [];
        while ($count++ < 1e4) {
            $rndTextId = random_int(1,2739);
            $text = Text::where('id',$rndTextId)->first();
            $speed = random_int(40e3,70e3)/1e3;
            $timeTaken = $text->length / $speed * 12;
            $mistakes = ($text->length/40)-floor(random_int(0,random_int(0,$text->length/40)));
            $date1 = date('Y-m-d G:i:s', time()-random_int(0,1e7));
            $races[] = [
                'user_id' => random_int(1,177),
                'time_taken' => $timeTaken,
                'text_id' => $rndTextId,
                'speed' => $speed,
                'mistakes' => $mistakes,
                'created_at' => $date1,
                'updated_at' => $date1,
            ];
            if ($count%1e3 == 0) {
                echo "$count\n";
                $blocks[] = $races;
                $races = [];
            }
        }
        foreach ($blocks as $block) {
            DB::table('races')->insert($block);
        }

        StatsController::calculateAndSaveStats();
        // $userStats = DB::select('SELECT user_id, name, sum(texts.length) as races_len, count(*) as races, sum(races.mistakes) as mistakes, sum(texts.length)/sum(races.time_taken)*12 AS WPM, sum(races.time_taken) as time_taken
		// FROM races
		// INNER JOIN texts ON races.text_id=texts.id
		// INNER JOIN users ON races.user_id=users.id
		// GROUP BY user_id
		// ORDER BY WPM DESC'
        // );
        // $rank = 1;
        // foreach ($userStats as $stat) {
        //     $date1 = date('Y-m-d G:i:s', time()-random_int(0,1e6));
        //     $user = User::find($stat->user_id);
        //     $user->mistakes     = $stat->mistakes;
        //     $user->rank         = $rank++;
        //     $user->races        = $stat->races;
        //     $user->time_taken   = $stat->time_taken;
        //     $user->races_len    = $stat->races_len;
        //     $user->stats_updated = time()-random_int(0,1e7);
        //     $user->created_at = $date1;
        //     $user->updated_at = $date1;
        //     $user->save();
        // }
    }
}