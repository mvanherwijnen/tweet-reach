<?php

use App\Service\TweetService\TweetServiceInterface;

return [
    'tweet' => [
        'repository' => TweetServiceInterface::class,
        'method' => 'findTweetByTweetId'
    ]
];