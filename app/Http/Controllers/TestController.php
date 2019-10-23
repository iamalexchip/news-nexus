<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Feed\FetchIo;
use App\Feed\Fetch;
use App\Feed\Parse;

class TestController extends Controller
{
	public function feed(Request $request)
	{
		echo "<title>Test feed</title>";
		$parse = new Parse;
		$parse->feed($request->input('url'));
		$items = $parse->result();
		dd( $items[0] );
	}

	public function feedio(Request $request)
	{
		echo "<title>Test feed with feedio</title>";
		return dd(  FetchIo::url($request->input('url')) );
	}

}
