<?php

namespace Tests\Unit\Jobs;

use App\Jobs\WriteToCache;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;
use Mockery;
use Tests\TestCase;

class WriteToCacheTest extends TestCase
{
	public function testWriteToCache()
	{
		$path = '/api/tweet/123';
		$data = ['cached_data' => 'foo'];
		$handler = new WriteToCache($path, $data);
		$config = Mockery::mock(Config::class);
		$config->shouldReceive('get')
			->with('cache.minutes_in_cache')
			->once()
			->andReturns(5);
		$cache = Mockery::mock(Cache::class);
		$cache->shouldReceive('set')
			->with($path, $data, 5)
			->once();
		$handler->handle($config, $cache);
	}
}
