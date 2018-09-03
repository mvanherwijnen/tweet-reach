<?php

namespace Tests\Unit\Service;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Service\TweetService\TweetService;
use Mockery;
use Tests\TestCase;

class TweetServiceTest extends TestCase
{
	public function testFindTweetByTweetId()
	{
	    $id = 3;
	    $client = Mockery::mock(TwitterOAuth::class);
	    $client->shouldReceive('get')
            ->with('statuses/show/'.$id)
            ->once()
            ->andReturns([
                'id' => 1,
                'user' => [
                    'id' => 4,
                    'name' => 'bar',
                    'followers_count' => 15
                ],
                'text' => 'foo'
            ]);
		$tweetService = new TweetService($client);
		$model = $tweetService->findTweetByTweetId($id);
		$this->assertEquals(1, $model->getId());
	}

	public function testFindTweetByTweetIdReturnsNullOnNotFound()
    {
        $id = 3;
        $client = Mockery::mock(TwitterOAuth::class);
        $client->shouldReceive('get')
            ->with('statuses/show/'.$id)
            ->once()
            ->andReturns([
                'errors' => [
                    'code' => 123,
                    'reason' => 'Model not found'
                ]
            ]);
        $tweetService = new TweetService($client);
        $model = $tweetService->findTweetByTweetId($id);
        $this->assertNull($model);
    }

	public function testFindRetweetsByTweetId()
	{
        $id = 3;
        $client = Mockery::mock(TwitterOAuth::class);
        $client->shouldReceive('get')
            ->with('statuses/retweets/'.$id)
            ->once()
            ->andReturns([
                [
                    'id' => 1,
                    'user' => [
                        'id' => 4,
                        'name' => 'bar',
                        'followers_count' => 15
                    ],
                    'text' => 'foo'
                ],
                [
                    'id' => 2,
                    'user' => [
                        'id' => 5,
                        'name' => 'bar2',
                        'followers_count' => 7
                    ],
                    'text' => 'foo2'
                ]
            ]);
        $tweetService = new TweetService($client);
        $result = $tweetService->findRetweetsByTweetId($id);
        $this->assertCount(2, $result);
	}
}
