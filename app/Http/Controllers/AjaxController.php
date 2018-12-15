<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Text;
use App\Subcategory;

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
}
