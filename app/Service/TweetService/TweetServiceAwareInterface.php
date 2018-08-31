<?php

namespace  App\Service\TweetService;

interface TweetServiceAwareInterface
{
    public function getTweetService(): TweetServiceInterface;

    public function setTweetService(TweetServiceInterface $service);
}
