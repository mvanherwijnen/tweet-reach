<?php

namespace  App\Service\TweetService;

use App\Model\Tweet\TweetModel;

interface TweetServiceInterface
{
    public function findTweetByTweetId($id): ?TweetModel;

    public function findRetweetsByTweetId($id): array;
}
