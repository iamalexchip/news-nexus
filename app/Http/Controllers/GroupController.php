<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function local()
    {
    	$data = [
    		'sliderItems' => Article::get(),
    	];
        return view('group.local', $data);
    }
}
