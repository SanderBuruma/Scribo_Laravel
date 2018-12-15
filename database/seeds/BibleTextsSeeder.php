<?php

use Illuminate\Database\Seeder;
use App\Subcategory;
class BibleTextsSeeder extends Seeder
{
    public function run()
    {
        $file = file(dirname(__DIR__).'/seeds/BibleDRA.txt');
        $book = "";
        foreach ($file as $key => $line) {
            if (preg_match("/(\d\d?):(\d\d?).{2,2}(.*?)\n/",$line,$matches)) {
                DB::table('texts')->insert([
                    'chapter' => $matches[1],
                    'verse' =>  $matches[2],
                    'subcategory_id' => $subcategory->id,
                    'text' => $matches[3],
                    'length' => strlen($matches[3]),
                ]);
            } else if (preg_match("/(.*?) Chapter \d+/",$line,$matches)) {
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
    }
}