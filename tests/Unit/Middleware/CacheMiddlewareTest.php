<?php

namespace Tests\Unit\Middleware;

use App\Jobs\WriteToCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CacheMiddlewareTest extends TestCase
{
    public function testRequestsCacheRepository()
    {
    	$path = 'api/tweet/123';
		Cache::shouldReceive('get')
			->once()
			->with($path)
			->andReturn(['result' => 'cached']);
		//call middleware
    }

    public function testFiresWriteToCacheOnResponseCode200()
    {
	    $path = 'api/tweet/123';
	    $data = ['result' => 'cached'];
		Queue::fake();
		//call middleware
		Queue::assertPushed(WriteToCache::class, function ($job) use ($path, $data) {
			return $job->requestPath === $path && $job->data = $data;
		});
    }

    public function testDoesNotFireWriteToCacheOnResponseCodeNon200()
    {
	    $path = 'api/tweet/123';
	    $data = ['result' => 'cached'];
	    Queue::fake();
	    //call middleware
	    Queue::assertNotPushed(WriteToCache::class);
    }
}
