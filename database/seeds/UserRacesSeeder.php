<?php

use Illuminate\Database\Seeder;
use App\Text;

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
        while ($count++ < 1e4) {
            $rndTextId = random_int(1,2739);
            $text = Text::where('id',$rndTextId)->first();
            $speed = random_int(40e3,70e3)/1e3;
            $timeTaken = $text->length / $speed * 12;
            $mistakes = ($text->length/40)-floor(random_int(0,random_int(0,$text->length/40)));
            $races[] = [
                'user_id' => random_int(2,177),
                'time_taken' => $timeTaken,
                'text_id' => $rndTextId,
                'speed' => $speed,
                'mistakes' => $mistakes,
            ];
            !($count%1e3)?print_r("\n$count"):null;
        }
        DB::table('races')->insert($races);
    }
}
