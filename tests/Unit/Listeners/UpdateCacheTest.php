<?php

namespace Tests\Unit\Listeners;

use App\Listeners\UpdateCache;
use Illuminate\Cache\Events\KeyForgotten;
use Tests\TestCase;

class UpdateCacheTest extends TestCase
{
	public function testUpdateCacheTweetModel()
	{
		$service = null;//mock tweetservice;
		$cache = null;//mock
		$config = null;//mock
		$listener = new UpdateCache(
			$service,
			$cache,
			$config
		);
		$keyForgotten = new KeyForgotten('/api/tweet/123');
		$listener->handle($keyForgotten);
	}

	public function testUpdateCacheRetweets()
	{
		$service = null;//mock tweetservice;
		$cache = null;//mock
		$config = null;//mock
		$listener = new UpdateCache(
			$service,
			$cache,
			$config
		);
		$keyForgotten = new KeyForgotten('/api/tweet/123/retweets');
		$listener->handle($keyForgotten);
	}
}
