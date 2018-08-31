<?php

namespace App\Service\TweetService;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Model\Tweet\TweetModel;
use App\Model\User\UserModel;

class TweetService implements TweetServiceInterface
{
    /** @var TwitterOAuth*/
    protected $twitterClient;

    public function __construct(?TwitterOAuth $client)
    {
        $this->twitterClient = $client;
    }

    public function findTweetByTweetId($id): ?TweetModel
    {
        $tweetData = $this->fetchTweetDataById($id);
        //TODO whatif tweet does not exist/exception is thrown

        return $this->hydrateTweet($tweetData);

    }

    public function findRetweetsByTweetId($id): array
    {
        $retweetsData = $this->fetchRetweetsDataById($id);
        //TODO whatif tweet does not exist/exception is thrown
        $tweetModels = [];
        foreach($retweetsData as $retweetData) {
            $tweetModels[] = $this->hydrateTweet($retweetData);
        }
        return $tweetModels;
    }

    protected function hydrateTweet($data): TweetModel
    {
        $data['user'] = new UserModel($data['user']);
        $tweet = new TweetModel($data);
        $tweet->setTweetService($this);
        return $tweet;
    }

    protected function fetchTweetDataById($id) {
        return $this->getTwitterClient()->get(
            'statuses/show/'.$id
        );
    }

    protected function fetchRetweetsDataById($id) {
        return $this->getTwitterClient()->get(
            'statuses/retweets/'.$id
        );
    }

    /** @return TwitterOAuth */
    public function getTwitterClient(): TwitterOAuth
    {
        return $this->twitterClient;
    }
}
