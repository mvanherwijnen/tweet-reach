<?php

namespace Tests\Util;

use App\Service\TweetService\TweetService;

class TweetServiceMock extends TweetService
{
    protected function fetchTweetDataById($id): array
    {
        return [
            'id' => $id,
            'text' => 'foo',
            'user' => [
                'id' => 6,
                'name' => 'bar',
                'follower_count' => 12
            ]
        ];
    }

    protected function fetchRetweetsDataById($id): array
    {
        return [
            [
                'id' => 1,
                'text' => 'foo',
                'user' => [
                    'id' => 6,
                    'name' => 'bar',
                    'follower_count' => 6
                ]
            ],
            [
                'id' => 2,
                'text' => 'foo',
                'user' => [
                    'id' => 6,
                    'name' => 'bar',
                    'follower_count' => 12
                ]
            ],
            [
                'id' => 3,
                'text' => 'foo',
                'user' => [
                    'id' => 6,
                    'name' => 'bar',
                    'follower_count' => 24
                ]
            ]
        ];
    }
}
