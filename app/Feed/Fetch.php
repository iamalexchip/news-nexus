<?php

namespace App\Feed;

use Carbon\Carbon;
use App\Models\Source;

class Fetch
{

	public static function url($url)
	{
		$fetcher = new Fetcher;
		$contentItems = $fetcher->readFeed($url);

		$items = [];

		foreach ($contentItems as $contentItem) {
			
			$items[] = [

	    		'guid' 			=> $contentItem->getPublicId(),
	    		'title' 		=> $contentItem->getTitle(),
	    		'url' 			=> $contentItem->getLink(),
	    		'content' 		=> $contentItem->getDescription(),
	    		'published' 	=> $contentItem->getLastModified()
    		];
		}

		return $items;
	}

	public static function source($id)
	{
		$fetcher = new Fetcher;
		$source = Source::find($id);

		$items = $fetcher->readFeed($source->url);
		$newItems = 0;

		// Fetch and flag if fetch fails or if therre isn't anything new       	
    	foreach ($items as $contentItem) {
    		if ($fetcher->addItem($contentItem, $source))
    		{
    			$newItems++;
    		}
    	}

    	// update last update ?hm How about only when actual items are found?
    	$source->last_update = Carbon::now();
    	$source->save();

		return $newItems;	
	}

}
