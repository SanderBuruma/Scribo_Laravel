<?php

use Illuminate\Database\Seeder;

class BibleTextsSeeder extends Seeder
{
    public function run()
    {
        $file = file(dirname(__DIR__).'/seeds/BibleDRA.txt');
        foreach ($file as $key => $line) {
            if (preg_match("/(\d\d?):(\d\d?).{2,2}(.*?)\n/",$line,$matches)) {
                DB::table('texts')->insert([
                    'chapter' => $matches[1],
                    'verse' =>  $matches[2],
                    'subcategory_id' => 3,
                    'title' => $book,
                    'text' => $matches[3],
                ]);
            } else if (preg_match("/(.*?) Chapter \d+/",$line,$matches)) {
                if ($matches[1] != $book) {
                    $book = $matches[1];
                }
            }
        }
    }
}