<?php

use Illuminate\Database\Seeder;
use App\Subcategory;
class SaintBook1Seeder extends Seeder
{
    public function run()
    {
        $file = file(dirname(__DIR__).'/seeds/TheHiddenTreasureoftheMass.txt');
        $book = "";
        $verseCount = 1;
        $chapterCount = 1;
        foreach ($file as $key => $line) {
            if (preg_match('/.{75,}/',$line,$matches)) {
                DB::table('texts')->insert([
                    'chapter' => $chapterCount,
                    'verse' =>  $verseCount,
                    'subcategory_id' => 274,
                    'text' => $matches[0],
                    'length' => strlen($matches[0]),
                ]);
                $verseCount++;
                if ($verseCount > 50) {
                    $chapterCount++;
                    $verseCount = 1;
                    print_r("\n".$matches[0]);
                }
            }

        }
    }
}