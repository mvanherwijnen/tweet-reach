<?php

namespace Tests\Unit\Jobs;

use App\Jobs\WriteToCache;
use Tests\TestCase;

class WriteToCacheTest extends TestCase
{
	public function testWriteToCache()
	{
		$path = '/api/tweet/123';
		$data = ['cached_data' => 'foo'];
		$handler = new WriteToCache($path, $data);
		$config = null;//mock
		$cache = null;//mock
		$handler->handle($config, $cache);
	}
}
