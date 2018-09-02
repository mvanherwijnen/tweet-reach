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
        if (array_key_exists('errors', $tweetData)) {
        	return null;
        }
	    return $this->hydrateTweet($tweetData);

    }

    public function findRetweetsByTweetId($id): array
    {
        $retweetsData = $this->fetchRetweetsDataById($id);
	    if (array_key_exists('errors', $retweetsData)) {
		    return null;
	    }
        $tweetModels = [];
        foreach($retweetsData as $retweetData) {
            $tweetModels[] = $this->hydrateTweet($retweetData);
        }
        return $tweetModels;
    }

    protected function hydrateTweet($data): TweetModel
    {
	    $data = (array) $data;
	    $data['user'] = new UserModel($data['user']);
	    $tweet = new TweetModel($data);
        $tweet->setTweetService($this);
        return $tweet;
    }

    protected function fetchTweetDataById($id): array{
        return (array) $this->getTwitterClient()->get(
            'statuses/show/'.$id
        );
    }

    protected function fetchRetweetsDataById($id): array {
        return (array) $this->getTwitterClient()->get(
            'statuses/retweets/'.$id
        );
    }

    /** @return TwitterOAuth */
    public function getTwitterClient(): TwitterOAuth
    {
        return $this->twitterClient;
    }
}
