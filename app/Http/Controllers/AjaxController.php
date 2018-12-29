<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Text;
use App\Race;
use App\Subcategory;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
	public function text(Request $request) {
		# grab fully random text.
		if 				 (isset($request->textId)) {
			$textIds = text::select('texts.id')->get();
			$textIdsCount = count($textIds);
			$text = Text::find((++$request->textId)%($textIdsCount));
		} else 	if (isset($request->specific)) {
			$expl = explode(" ",$request->specific);
			$text = Text::
			where('subcategory_id',	'=',	$expl[0])
			->where('chapter',			'=',	$expl[1])
			->where('verse',				'=',	$expl[2])
			->first();
		} else {
			$textIds = text::select('texts.id')->get();
			$textIdsCount = count($textIds);
			$text = Text::find(random_int(1,$textIdsCount));
		} 
		$text->title = Subcategory::find($text->subcategory_id)->name;
		return $text;
	}

	public function chapter(Request $request) {
		$text = Text::where('subcategory_id','=',"$request->book")->orderByDesc('chapter')->first();
		return $text->chapter;
	}

	public function verse(Request $request) {
		$text = Text::
		where('subcategory_id','=',"$request->book")
		->where('chapter','=',$request->chapter)
		->orderByDesc('verse')
		->first();
		return $text->verse;
	}

	public function returnSaints() {
		$saints = DB::select('SELECT subcategories.name, subcategories.id, count(texts.length) as text_count
		FROM subcategories
		INNER JOIN texts ON subcategories.id=texts.subcategory_id
		WHERE subcategories.category_id = 4
		GROUP BY subcategories.name
		ORDER BY id ASC'
		);
		return $saints;
	}

	public function leaderboard() {

		$stats = DB::select('SELECT user_id, name, (sum(texts.length)/sum(races.time_taken))*12 as WPM, count(*) as races_count
		FROM races
		INNER JOIN texts ON races.text_id=texts.id
		INNER JOIN users ON races.user_id=users.id
		GROUP BY user_id
		ORDER BY WPM DESC
		LIMIT 10'
		);
		return $stats;

	}
}
