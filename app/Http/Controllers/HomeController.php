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
    public function index()
    {
    	$data = [
    		'sliderArticles' => Article::hasImage()->take(4)->get(),// latest articles
            'articles' => Article::take(15)->get(),
            'trendingArticles' => Article::take(2)->get()
    	];
        return view('home', $data);
    }
}
