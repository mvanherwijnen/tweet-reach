<?php

use App\Http\Middleware\DomainModelMiddleware;
use App\Service\TweetService\TweetServiceInterface;

return [
    'tweet' => [
    	DomainModelMiddleware::class => [
		    DomainModelMiddleware::REPOSITORY => TweetServiceInterface::class,
		    DomainModelMiddleware::METHOD => 'findTweetByTweetId'
	    ]
    ]
];