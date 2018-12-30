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
            $speed = random_int(10e3,20e3)/1e3;
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
    }
}