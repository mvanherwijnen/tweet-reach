<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\CacheMiddleware;
use App\Jobs\WriteToCache;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class CacheMiddlewareTest extends TestCase
{
    public function testRequestsCacheRepository()
    {
	    $path = 'api/tweet/123';
	    $cachedResponse = ['result' => 'cached'];
	    $cache = Mockery::mock(Cache::class);
	    $cache->shouldReceive('get')
		    ->once()
		    ->with($path)
		    ->andReturns($cachedResponse);
	    $request = Mockery::mock(Request::class);
	    $request->shouldReceive('path')
		    ->once()
		    ->andReturns($path);

		$middleware = new CacheMiddleware($cache);
		/** @var JsonResponse $response */
		$response = $middleware->handle($request, function(){});
		$response = $response->getData(true);
		$this->assertEquals($response, $cachedResponse);
    }

    public function testFiresWriteToCacheOnResponseCode200()
    {
	    $path = 'api/tweet/123';
	    $data = ['result' => 'cached'];
	    $cache = Mockery::mock(Cache::class);
	    $cache->shouldReceive('get')
		    ->once()
	        ->with($path)
	        ->andReturns(null);
	    $request = Mockery::mock(Request::class);
	    $request->shouldReceive('path')
		    ->once()
		    ->andReturns($path);
	    $response = Mockery::mock(JsonResponse::class);
	    $response->shouldReceive('getStatusCode')
		    ->once()
		    ->andReturns(200);
	    $response->shouldReceive('getData')
		    ->once()
		    ->with(true)
		    ->andReturns($data);

	    Queue::fake();
	    $middleware = new CacheMiddleware($cache);
	    $middleware->handle($request, function() use ($response){return $response;});
		Queue::assertPushed(WriteToCache::class);
    }

    public function testDoesNotFireWriteToCacheOnResponseCodeNon200()
    {
	    $path = 'api/tweet/123';
	    $data = ['result' => 'cached'];
	    $cache = Mockery::mock(Cache::class);
	    $cache->shouldReceive('get')
	          ->once()
	          ->with($path)
	          ->andReturns(null);
	    $request = Mockery::mock(Request::class);
	    $request->shouldReceive('path')
	            ->once()
	            ->andReturns($path);
	    $response = Mockery::mock(JsonResponse::class);
	    $response->shouldReceive('getStatusCode')
	             ->once()
	             ->andReturns(404);
	    $response->shouldNotReceive('getData');

	    Queue::fake();
	    $middleware = new CacheMiddleware($cache);
	    $middleware->handle($request, function() use ($response){return $response;});
	    Queue::assertNotPushed(WriteToCache::class);
    }
}
