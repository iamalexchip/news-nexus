<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class MoreController extends Controller
{
    /**
     * Standard Article pagination number set in config.news
     *
     * @var string
     */
    public $article_pagination;

    function __construct()
    {
        $this->article_pagination = config('news.article_pagination');
    }
	
    /**
	 * Loads more articles for a category
	 */
    public function home()
    {
        $articles = Article::paginate($this->article_pagination);
        return response()->json([
                'body' => view('block.articles.potrait')->with('articles', $articles)->render(),
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage()
            ]);
    }

}
