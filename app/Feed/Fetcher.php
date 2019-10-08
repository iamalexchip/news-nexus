<?php

namespace App\Feed;

use App\Models\Feed;
use App\Models\Item;

use Carbon\Carbon;
use FeedIo;
use DiDom\Document;

use Illuminate\Support\Facades\Log;

class Fetcher
{
	public function __construct()
	{

	}

	public function readFeed($url)
	{
		// create a simple FeedIo instance
		$feedIo = FeedIo\Factory::create()->getFeedIo();

		try {

			$result = $feedIo->readSince($url, new \DateTime('-7 days'));

		} catch (\Exception $e) {

			// Log error
			Log::alert('Error fetching feed', ['url' => $url]);

			return [];

		}

		// $result->getFeed()->getLastModified();

		// iterate through items
		return $result->getFeed();
	}

	public function addItem($contentItem, $feed)
	{
		$item = Item::where('guid', $contentItem->getPublicId())->first();

		// Check if I exist
    	if (Item::where('guid', $contentItem->getPublicId())->exists()) {

    		$this->checkForEditorialChanges($contentItem, $item);
    		return;

    	}

    	if ($this->hasInappropriateContent($contentItem, $feed)) return;

    	$item = Item::create([
    		'source_id' 	=> $feed->id,
    		'guid' 			=> $contentItem->getPublicId(),
    		'title' 		=> $contentItem->getTitle(),
    		'url' 			=> $contentItem->getLink(),
    		'summary' 		=> $this->summary($contentItem),
    		//'content' 	=> $this->content($contentItem),
    		'image' 		=> $this->image($contentItem),
    		'published' 	=> $contentItem->getLastModified()
    	]);

    	return true;
	}

	public function checkForEditorialChanges($contentItem, $item)
	{
		if (!$this->itemWasUpdated($contentItem, $item)) return;

		// Update item
		$item->title = $contentItem->getTitle();
    	$item->url = $contentItem->getLink();
    	$item->summary = $this->summary($contentItem);
    	$item->image = $this->image($contentItem);
    	$item->published = $contentItem->getLastModified();
    	$item->save();
	}

 	/**
  	 * Determines if item has been updated somehow since publishing
  	 * It doesn't check if the item has been deleted though (Hmm?)
  	 * Maybe >ping way is to check if an item disappears from RSS?
  	 *
  	 * @todo Should we check image?
  	 *
  	 * @return bool
  	 */
	public function itemWasUpdated($contentItem, $item)
	{
		$lastModified = Carbon::instance($contentItem->getLastModified());
		$published = Carbon::parse($item->published);

		/// Check update
		if (!$lastModified->eq($published)) {
			Log::info('Item has been updated', [
				'how' => 'timestamp',
				'item_id' => $item->id
			]);

			return true;
		}

		// Check URL
		if ($item->url != $contentItem->getLink()) {
			Log::info('Item has been updated', [
				'how' => 'url',
				'item_id' => $item->id
			]);

			return true;
		}

		// Check summary
		if ($item->summary != $this->summary($contentItem)) {
			Log::info('Item has been updated', [
				'how' => 'summary',
				'item_id' => $item->id
			]);

			return true;
		}

		// Check title
		if($item->title != $contentItem->getTitle()) {
			Log::info('Item has been updated', [
				'how' => 'title',
				'item_id' => $item->id
			]);

			return true;
		}

		return false;
	}

	public function hasInappropriateContent($contentItem, $feed)
	{// todo FLesh
		return false;

		Log::alert('Feed published inappropriate', [
    		'url' => $contentItem->getLink(),
    		'source_id' => $feed->id,
    	]);
	}

	/**
	 * Given a content item, it will clean out and return the best available summary
	 *
	 * @todo PR this
	 *
	 * @param $contentItem Content Item
	 * @param bool $truncate Truncate the summarty if it's too long
	 *
	 * @return bool
	 */
	public function summary($contentItem, $truncate = true)
	{
		$summary = $contentItem->getValue('content:encoded') ?: $contentItem->getDescription();
		if ($summary === '<![CDATA[ ]]>' || $summary === '<![CDATA[]]>' ) return null;

		// if (!$truncate) return $summary;

		$summary = str_replace(["<![CDATA[", "]]>"], "", $summary);
		$summary = strip_tags($summary);

		if (strlen($summary) > 450) $summary = substr($summary, 0, 448) . '...';

		return $summary;
	}

	/**
	 * get feed item content
	 *
	 * @todo PR this
	 *
	 * @param $contentItem Content Item
	 * @param bool $truncate Truncate the summarty if it's too long
	 *
	 * @return bool
	 */
	public function content($contentItem, $truncate = true)
	{
		$content = $contentItem->getValue('content:encoded') ?: $contentItem->getDescription();
		if ($content === '<![CDATA[ ]]>' || $content === '<![CDATA[]]>' ) return null;

		// if (!$truncate) return $content;

		$content = str_replace(["<![CDATA[", "]]>"], "", $content);

		if (strlen($content) > 20000) $content = substr($content, 0, 19998) . '...';

		return $content;
	}

	/**
	 * Checks if given $text is HTML content or not
	 *
	 * @see http://stackoverflow.com/a/18242765/946175
	 *
	 * @param string $text Text
	 *
	 * @return bool
	 */
	protected function isHTML($text){
	   $processed = htmlentities($text);
	   if($processed == $text) return false;
	   return true;
	}

	/**
	 * Tries to find cover image src for $contentItem using all available ways
	 * starting with the least resource/time intensive
	 *
	 * @param $contentItem Content Item
	 *
	 * @return string|null
	 */
	public function image($contentItem)
	{
		//Look in summary
		if ($this->isHTML($this->summary($contentItem, false))) {

			if ($src = $this->findImageInFeedContent($contentItem) ) {
				return $src;
			}

		}

		$document = new Document($contentItem->getLink(), true);

		//Try in Open Graph
		if ($src = $this->findOpenGraphImage($document)) return $src;

		// Try in the source HTML
		$src = $this->findImageInHTML(
			$document = $document,
			$title = $contentItem->getTitle()
		);

		if (!$src) return $src;

		return null;
	}

	/**
	 * Tries to find item image in a content item with embedded HTML content
	 * @param DiDom\Document $contentItem  Content Item
	 *
	 * @return string|false
	 */
	private function findImageInFeedContent($contentItem)
	{
		$document = new Document($this->summary($contentItem));

		$imgs = $document->find('img');
		if (!count($imgs)) return false;

		return $imgs[0]->getAttribute('src') ?: false;
	}

	/**
	 * Retrieves open graph image from $document
	 *
	 * @param DiDom\Document $document  Document
	 *
	 * @return string|false
	 */
	private function findOpenGraphImage($document)
	{
		$og = $document->find('meta[property=og:image]');
		if (!count($og)) return false;

		return ($og[0]->getAttribute('content')) ?: false;
	}

	/**
	 * Given a full document and item title, it will try and find a cover image to use
	 *
	 * @param DiDom\Document $document  Document
	 * @param string $title  Title
	 * @param int $level  Heading size to look for
	 *
	 * @return string|false
	 */
	private function findImageInHTML($document, $title, $level = 1)
	{
		//Find biggest heading with article title
		$headings = $document->find('h' . $level);

		foreach ($headings as $heading) {

			if ($title === $heading->text()) {

				//Found starter node

				//return $document->html();

				$headingsPosition = strpos($document->html(), $heading->html());

				if (!$headingsPosition) continue;

				//Find first image after this title (save a few cycles)
				$imagePosition = strpos($document->html(), '<img', $headingsPosition);

				// @future Check distance between two
				if (!$imagePosition) continue;

				$matches = preg_match(
					"/<img\s?.*\ssrc=[\"|']([^'\"]+)[\"|']/",
					substr($document->html(), $headingsPosition),
					$output_array
				);

				if ($matches === 0) continue;

				// Find the image so we can check its dimensions
				$images = $document->find('img[src="'.$output_array[1].'"]');

				// @think Shouldn't we just exit at this point?
				if (!count($images)) continue;

				$image = $images[0];

				// Yeah
				if (!$image->hasAttribute('width') || !$image->hasAttribute('height')) continue;

				// http://stackoverflow.com/a/12932958/946175
				$width = $image->getAttribute('width');
				$height = $image->getAttribute('height');

				if ($width < 150 || $height < 150) continue;

				$aspectRatio = $width / $height;

				if ($aspectRatio < 0.5 || $aspectRatio > 2) continue;

				return $image->getAttribute('src');
			}

		}

		//We get here, can't find anything. Try another header size
		if ($level < 4) return $this->findImageInHTML($document, $title, $level + 1);

		return null;
	}
}
