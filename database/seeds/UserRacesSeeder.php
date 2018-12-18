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
		$count = 2;
		foreach (["Piet","Jan","Joris","Ali","Mickey","Mark","Lucas","Jonah","Angela","Maria","Elise","Johanna","Katherina","Margriet","Judith","Hosea"] as $v) {
			foreach (["Buruma","Voorwaarts","Linkswaards","Jongsma","Vossens","Jager","Schoenmaker","Botergoed","Smit","Voerenaar","vd Werf"] as $vv) {
                $createdAt = '2018-12-0'.random_int(1,9).' '.random_int(10,23).':'.random_int(10,59).':'.random_int(10,59);
				DB::table('users')->insert([
					'name' => "$v $vv",
                    'email' => "$v$vv@gmail.com",
					'password' => '$2y$10$0LXS7kFPXV3lqzxeONNNiuWPBGBynLvDgPqXOO4ftOtzWJMX4QI82', //aaaaaaaa
                    'city' => 'Groningen',
                    'country' => 'the Netherlands',
					'created_at' => $createdAt,
					'updated_at' => $createdAt,
					'email_verified_at' => $createdAt,
				]);
				DB::table('role_user')->insert([
					'user_id' => $count++,
					'role_id' => 1,
				]);
			}
        }
        
        $count = 0;
        while ($count++ < 1e4) {
            $rndTextId = random_int(1,2739);
            $text = Text::where('id',$rndTextId)->first();
            $speed = random_int(70e3,130e3)/1e3;
            $timeTaken = $text->length / $speed * 12;
            $mistakes = ($text->length/20)-floor(random_int(0,random_int(0,$text->length/20)));
            DB::table('races')->insert([
                'user_id' => random_int(1,177),
                'time_taken' => $timeTaken,
                'text_id' => $rndTextId,
                'speed' => $speed,
                'mistakes' => $mistakes,
            ]);
            !$count%1e2?print_r($count):null;
        }
    }
}
