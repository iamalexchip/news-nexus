<?php

namespace App\Feed;

use App\Models\Source;
use App\Models\Article;

use Carbon\Carbon;
use DiDom\Document;

use Illuminate\Support\Facades\Log;

class Parse
{
	/**
	 * XML feed items
	 *
	 * @var string
	 */
	private $feedItems;

	/**
	 * Gets a feed's items
	 *
	 * @param $url Feed url
	 *
	 * @return array
	 */
	public function feed($url)
	{
		try {

			$feed = simplexml_load_file($url);

		} catch (\Exception $e) {

			Log::alert('Error fetching feed', ['url' => $url]);
			return [];

		}
		
		$this->feedItems = $feed->xpath("//item");

		return true;
	}

	/**
	 * Processes feed items into objects with necessary data in properties
	 *
	 * @return array
	 */
	public function result()
	{
		foreach ($this->feedItems as $feedItem) {
			$items[] = [
				'guid' 			=> $this->get($feedItem, 'guid'),
	    		'title' 		=> $this->get($feedItem, 'title'),
	    		'url' 			=> $this->get($feedItem, 'link'),
	    		'summary'	 	=> $this->summary($feedItem),
	    		'content' 		=> $this->content($feedItem, 'description'),
	    		'image' 		=> $this->image($feedItem),
	    		'published' 	=> $this->get($feedItem, 'pubDate')
	    	];
    	}

    	return $items;
	}

	/**
	 * Processes feed items into objects with necessary data in properties
	 *
	 * @return array
	 */
	public function display()
	{
		foreach ($this->result() as $item) {
			echo "$count)".
				"<h3><a href='$item->url'>$item->title</a></h3>".
				"<p><b>published</b></p>".
				"<div>$item->content</div>".
				"<hr>";
		}
	}


	/**
	 * Gets the given node name from an item
	 *
	 * @param $feedItem XML feed item
	 * @param $node Name of the node to be retrieved 
	 *
	 * @return string
	 */
	private function get($feedItem, $node)
	{
		return (string) $feedItem->$node;
	}

	/**
	 * Given a content item, it will clean out and return the best available summary
	 *
	 * @param $feedItem Feed item
	 *
	 * @return string
	 */
	public function summary($feedItem)
	{
		$summary_max = config('news.summary_max');

		if(strlen($this->get($feedItem, 'description')) > 0) {
			$summary = $this->get($feedItem, 'description');
		} else {
			$summary = $this->get($feedItem, 'content:encoded');
		}

		if ($summary === '<![CDATA[ ]]>' || $summary === '<![CDATA[]]>' ) return null;

		$summary = str_replace(["<![CDATA[", "]]>"], "", $summary);
		$summary = strip_tags($summary);

		if (strlen($summary) > $summary_max) $summary = substr($summary, 0, $summary_max) . '...';

		return $summary;
	}

	/**
	 * Given a feed item, it will return its content in usable encoding
	 *
	 * @param $feedItem Feed item
	 *
	 * @return string
	 */
	public function content($feedItem)
	{
		if(strlen($this->get($feedItem, 'description')) > 0) {
			$content = $this->get($feedItem, 'description');
		} else {
			$content = $this->get($feedItem, 'content:encoded');
		}
		
		if ($content === '<![CDATA[ ]]>' || $content === '<![CDATA[]]>' ) return null;

		$content = str_replace(["<![CDATA[", "]]>"], "", $content);

		return $content;
	}

	/**
	 * Gets an article's image
	 *
	 * @param $feedItem Feed item
	 *
	 * @return string
	 */
	public function image($feedItem)
	{
		// look for image node
		if(strlen($this->get($feedItem, 'image')) > 0) {
			return $this->get($feedItem, 'image');
		}

		// look in enclosure
		if(!is_null($feedItem->enclosure->attributes)) {

			$enclosure_url = (string) $feedItem->enclosure->attributes()->url;
			$enclosure_type = (string) $feedItem->enclosure->attributes()->type;

			if (substr($enclosure_type, 0, 5) == 'image') {
				return $enclosure_url;
			}
		}

	}

}