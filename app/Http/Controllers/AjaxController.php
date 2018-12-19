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
		$textIds = text::select('texts.id')->get();
		$textIdsCount = count($textIds);
		if (isset($request->textId)) {
			$text = Text::find((++$request->textId)%($textIdsCount));
		} else if (isset($request->bible)) {
			$expl = explode(" ",$request->bible);
			$text = Text::
			where('subcategory_id',	'=',	$expl[0])
			->where('chapter',			'=',	$expl[1])
			->where('verse',				'=',	$expl[2])
			->first();
		} else {
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

	public function leaderboard() {
		$leaderboard = DB::select('SELECT user_id,name,(sum(texts.length)/sum(time_taken))*12 as WPM
		FROM races
		INNER JOIN texts ON races.text_id=texts.id
		INNER JOIN users ON races.user_id=users.id
		GROUP BY user_id
		ORDER BY WPM DESC
		LIMIT 10'
		);
		return $leaderboard;
	}
}
