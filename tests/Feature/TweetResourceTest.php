<?php

namespace Tests\Feature;

use App\Service\TweetService\TweetServiceInterface;
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
        $response = $this->get('/api/tweet/'.$id);

        $response->assertStatus(200);
    }

    public function testGuestCanGetRelationRetweets()
    {
        $id = 123;
        $response = $this->get('/api/tweet/'.$id.'/retweets');

        $response->assertStatus(200);
    }
}
