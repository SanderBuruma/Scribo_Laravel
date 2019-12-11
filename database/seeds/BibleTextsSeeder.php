<?php

use Illuminate\Database\Seeder;
use App\Subcategory;
class BibleTextsSeeder extends Seeder
{
    public function run()
    {
        $file = file(dirname(__DIR__).'/seeds/BibleDRA.txt');
        $book = "";
        $blocks = [];
        $verseCount = 0;
        $verses = [];
        foreach ($file as $key => $line) {
            if (preg_match('/(\d\d?\d?):(\d\d?\d?).{2,2}(.*)/',$line,$matches)) {
                $verses[] = [
                    'chapter' => $matches[1],
                    'verse' =>  $matches[2],
                    'subcategory_id' => $subcategory->id,
                    'text' => $matches[3],
                    'length' => strlen($matches[3]),
                ];
                $verseCount++;
                if ($verseCount%1e3==0) {
                    $blocks[] = $verses;
                    $verses = [];
                }
            } else if (preg_match('/(.*?) Chapter \d+/',$line,$matches)) {
                if ($matches[1] != $book) {
                    $book = $matches[1];
					DB::table('subcategories')->insert([
						'name' => $matches[1],
                        'category_id' => 2,
					]);
                    $subcategory = Subcategory::where('name',$matches[1])->first();
                    print_r("ID:$subcategory->id/$subcategory->name\n");
                }
            }
        }
        $count = 0;
        // dd($blocks[0]);
        foreach ($blocks as $block) {
            print_r("Block: ".$count++ ."\n");
            DB::table('texts')->insert($block);
        }
        // DB::table('texts')->insert($verses);
    }
}
