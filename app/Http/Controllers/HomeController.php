<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subcategory;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = Subcategory::select('id','name','category_id')->where('category_id','=','2')->get();
        return view('pages.home')->withSubcategories($subcategories);
    }
}
