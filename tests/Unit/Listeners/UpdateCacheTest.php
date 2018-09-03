<?php

namespace Tests\Unit\Listeners;

use App\Listeners\UpdateCache;
use App\Model\Tweet\TweetModel;
use App\Service\TweetService\TweetServiceInterface;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;
use Mockery;
use Tests\TestCase;

class UpdateCacheTest extends TestCase
{
	public function testUpdateCacheTweetModel()
	{
		$id = 6;
		$path = 'api/tweet/'.$id;
		$data = ['cached' => 'true'];
		$model = Mockery::mock(TweetModel::class);
		$model->shouldReceive('extract')
			->once()
			->andReturns($data);
		$service = Mockery::mock(TweetServiceInterface::class);
		$service->shouldReceive('findTweetByTweetId')
			->with($id)
			->once()
			->andReturns($model);
		$cache = Mockery::mock(Cache::class);
		$cache->shouldReceive('set')
			->with($path, $data, 5)
			->once();
		$config = Mockery::mock(Config::class);
		$config->shouldReceive('get')
			->with('cache.minutes_in_cache')
			->andReturns(5);
		$listener = new UpdateCache(
			$service,
			$cache,
			$config
		);
		$keyForgotten = new KeyForgotten($path);
		$listener->handle($keyForgotten);
	}

	public function testUpdateCacheRetweets()
	{
		$id = 6;
		$path = 'api/tweet/'.$id.'/retweets';
		$data = ['cached' => 'true'];
		$model = Mockery::mock(TweetModel::class);
		$model->shouldReceive('extract')
		      ->once()
		      ->andReturns($data);
		$model2 = Mockery::mock(TweetModel::class);
		$model2->shouldReceive('extract')
		      ->once()
		      ->andReturns($data);
		$service = Mockery::mock(TweetServiceInterface::class);
		$service->shouldReceive('findRetweetsByTweetId')
		        ->with($id)
		        ->once()
		        ->andReturns([$model, $model2]);
		$cache = Mockery::mock(Cache::class);
		$cache->shouldReceive('set')
		      ->with($path,
			      [
			        'count' => 2,
			        'items' => [
				        $data,
				        $data
			        ]
		          ], 5)
		      ->once();
		$config = Mockery::mock(Config::class);
		$config->shouldReceive('get')
		       ->with('cache.minutes_in_cache')
		       ->andReturns(5);
		$listener = new UpdateCache(
			$service,
			$cache,
			$config
		);
		$keyForgotten = new KeyForgotten($path);
		$listener->handle($keyForgotten);
	}
}
