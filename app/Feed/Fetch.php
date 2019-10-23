<?php

namespace App\Feed;

use App\Models\Source;
use App\Models\Article;

use Carbon\Carbon;
use DiDom\Document;

use Illuminate\Support\Facades\Log;

class Fetch
{
	function __construct($url)
	{
		$this->items = $this->feed($url);
	}

	/**
	 * Fetches a feed's articles
	 *
	 * @param $url Feed url
	 *
	 * @return array
	 */
	public static function source($id)
	{
		$source = Source::find($id);

		$items = $this->feed($source->url);
		$newItems = 0;

		// Fetch and flag if fetch fails or if therre isn't anything new       	
    	foreach ($items as $feedItem) {
    		if ($fetcher->addArticle($feedItem, $source))
    		{
    			$newItems++;
    		}
    	}

    	// update last update ?hm How about only when actual items are found?
    	$source->last_update = Carbon::now();
    	$source->save();

		return $newItems;	
	}

	/**
	 * Fetches a feed's articles
	 *
	 * @param $url Feed url
	 *
	 * @todo summary function should remove all img tags
	 * @todo image function
	 * @todo encoding for article content when saving to DB
	 * @todo add categories to article
	 *
	 * @return array
	 */
	public function addArticle($feedItem, $source)
	{
		$article = Article::where('guid', $feedItem->guid)->first();

		if ($article->exists()) {

    		$this->checkForEditorialChanges($feedItem, $article);
    		return false;

    	}

    	// REMOVE NEXT LINE AFTER ABOVE CODE IS WORKING FINE
    	return true;

    	if ($this->hasInappropriateContent($item, $source)) return;*/

    	$item = Article::create([
    		'source_id' 	=> $source->id,
    		'guid' 			=> $feedItem->guid,
    		'title' 		=> $feedItem->title,
    		'url' 			=> $feedItem->link,
    		//'summary' 	=> $this->summary($item),
    		//'content' 	=> $this->content($item),
    		//'image' 		=> $this->image($item),
    		'published' 	=> $feedItem->pubDate
    	]);

    	return true;
	}

	public function checkForEditorialChanges($feeditem, $article)
	{
		if (!$this->itemWasUpdated($feedItem, $artIcle)) return;

		// Update item
		$article->title = $feeditem->getTitle();
    	$article->url = $feedItem->getLink();
    	$article->summary = $this->summary($feedItem);
    	$article->image = $this->image($feedItem);
    	$article->published = $feedItem->pubDate;
    	$article->save();
	}

	/**
  	 * Determines if item has been updated somehow since publishing
  	 * It doesn't check if the item has been deleted though (Hmm?)
  	 * Maybe >ping way is to check if an item disappears from RSS?
  	 *
  	 * @return bool
  	 */
	public function itemWasUpdated($feedItem, $artIcle)
	{
		$lastModified = Carbon::instance($feedItem->pubDate);
		$published = Carbon::parse($article->published);

		/// Check update
		if (!$lastModified->eq($published)) {
			Log::info('Item has been updated', [
				'how' => 'timestamp',
				'article_id' => $article->id
			]);

			return true;
		}

		// Check URL
		if ($item->url != $feedItem->link) {
			Log::info('Item has been updated', [
				'how' => 'url',
				'article_id' => $article->id
			]);

			return true;
		}

		/*/ Check summary
		if ($item->summary != $this->summary($contentItem)) {
			Log::info('Item has been updated', [
				'how' => 'summary',
				'item_id' => $item->id
			]);

			return true;
		}*/

		// Check title
		if($item->title != $feedItem->title) {
			Log::info('Item has been updated', [
				'article_id' => $article->id,
				'how' => 'title'
			]);

			return true;
		}

		return false;
	}

}
