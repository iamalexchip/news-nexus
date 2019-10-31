<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class MoreController extends Controller
{
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
