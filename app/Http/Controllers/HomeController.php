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
    		'sliderArticles' => Article::latest()->withImage()->take(4)->get(),
            'articles' => Article::latest()->paginate($this->article_pagination),
            'trendingArticles' => Article::take(2)->get()
    	];
        return view('home', $data);
    }
}
