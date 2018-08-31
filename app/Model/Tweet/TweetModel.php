<?php

namespace App\Model\Tweet;

use App\Model\AbstractModel;
use App\Model\User\UserModel;
use App\Service\TweetService\TweetServiceAwareInterface;
use App\Service\TweetService\TweetServiceAwareTrait;

class TweetModel extends AbstractModel implements TweetServiceAwareInterface
{
    use TweetServiceAwareTrait;

    protected $map = [
        'text',
        'user',
    ];

    public $supportedRelations = ['retweets'];

    /** @var string */
    protected $text;

    /** @var UserModel */
    protected $user;

    /** @var array */
    protected $retweets;

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return UserModel
     */
    public function getUser(): UserModel
    {
        return $this->user;
    }

    /**
     * @param UserModel $user
     */
    public function setUser(UserModel $user): void
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getRetweets(): array
    {
        if (empty($this->retweets)) {
            $this->retweets = $this->getTweetService()
                ->findRetweetsByTweetId($this->getId());
        }
        return $this->retweets;
    }

    /**
     * @param array $retweets
     */
    public function setRetweets(array $retweets): void
    {
        $this->retweets = $retweets;
    }


}
