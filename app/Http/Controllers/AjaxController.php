<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Text;

class AjaxController extends Controller
{
    public function text() {
        # grab fully random text.
        $textIds = text::select('texts.id')->get();
        $text = Text::find(random_int(0,count($textIds)));
        return $text;
    }
}
