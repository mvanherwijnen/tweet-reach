<?php

namespace Tests\Feature\Resource;

use App\Service\TweetService\TweetServiceInterface;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Contracts\Foundation\Application;
use Tests\TestCase;
use Tests\Util\TweetServiceMock;

class TweetResourceTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app->bind(TweetServiceInterface::class, function (Application $app) {
            return new TweetServiceMock(null);
        });
    }

    public function testGuestCanGet()
    {
        $id = 123;
        $response = $this->json( 'GET', '/api/tweet/'.$id);
        $response
	        ->assertStatus(200)
	        ->assertJson([
		        'id' => 123,
		        'text' => 'foo',
		        'user' => [
			        'id' => 6,
			        'name' => 'bar',
			        'followers_count' => 12
		        ]
	        ]);
    }

    public function testModelNotFoundReturns404()
    {
	    $id = 404;
	    $response = $this->get('/api/tweet/'.$id);
	    $response->assertStatus(404);
    }

    public function testResponseIsCachedOnPathKey()
    {
	    $id = 123;
    	$path = 'api/tweet/'.$id;
	    $response = $this->get($path);
	    /** @var Cache $cache */
	    $cache = $this->app->make(Cache::class);
	    $cachedResponse = $cache->get($path);
	    $response = json_decode($response->content(), true);
	    $this->assertTrue($cachedResponse == $response);
    }

	public function testCachedResponseIsRetrieved()
	{
		$id = 123;
		$path = 'api/tweet/'.$id;
		/** @var Cache $cache */
		$cache = $this->app->make(Cache::class);
		$cachedResponse = ["result" => "cached"];
		$cache->set($path, $cachedResponse, 5);
		$cachedResponse = $cache->get($path);
		$response = $this->get($path);
		$response = json_decode($response->content(), true);
		$this->assertTrue($cachedResponse == $response);
	}

	public function testGuestCanGetRelationRetweets()
    {
        $id = 123;
        $response = $this->get('/api/tweet/'.$id.'/retweets');
        $response->assertStatus(200);
	    $response = json_decode($response->content(), true);
	    $this->assertCount(3, $response['items']);
    }

	public function testGuestGetRelationRetweetsOnEmptySetReturns200()
	{
		$id = 321;
		$response = $this->get('/api/tweet/'.$id.'/retweets');
		$response->assertStatus(200);
		$response = json_decode($response->content(), true);
		$this->assertCount(0, $response['items']);
	}
}
