<?php

namespace  App\Service\TweetService;


trait TweetServiceAwareTrait
{
    /** @var TweetServiceInterface */
    protected $tweetService;

    /**
     * @return TweetServiceInterface
     */
    public function getTweetService(): TweetServiceInterface
    {
        return $this->tweetService;
    }

    /**
     * @param TweetServiceInterface $tweetService
     */
    public function setTweetService(TweetServiceInterface $tweetService): void
    {
        $this->tweetService = $tweetService;
    }
}
