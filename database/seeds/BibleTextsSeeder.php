<?php

use Illuminate\Database\Seeder;

class BibleTextsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genesis = file('C:\\Users\\Cubit32\\Desktop\\coding\\Scribo_Laravel\\database\\seeds\\BibleDRAgen.txt');
        foreach ($genesis as $key => $line) {
            if (preg_match("/(\d\d?):(\d\d?).{2,2}(.+)/",$line,$matches)) {
                
                $chapter = $matches[1];
                $verse = $matches[2];
                DB::table('texts')->insert([
                    'chapter' => $chapter,
                    'verse' => $verse,
                    'subcategory_id' => 3,
                    'text' => $matches[3],
                ]);
            }
        }
    }
}