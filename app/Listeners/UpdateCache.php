<?php

namespace App\Listeners;

use App\Model\AbstractModel;
use App\Service\TweetService\TweetServiceInterface;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class UpdateCache
{
    /** @var TweetServiceInterface */
    protected $tweetService;

    public function handle(KeyForgotten $event){
        //TODO figure out how to replay a request. Now we end up with a handler that contains duplicate code and is too concrete
        $path = $event->key;
        $pathFragments = explode('/', $path);

        $relation = null;
        if (count($pathFragments) == 4) {
            list($empty, $api, $resource, $id) = $pathFragments;
        } else if (count($pathFragments) == 5){
            list($empty, $api, $resource, $id, $relation) = $pathFragments;
        } else {
            return;
        }

        if ($resource != 'tweet') {
            return;
        }

        if (empty($relation)) {
            $model = $this->getTweetService()->findTweetByTweetId($id);
            $data = $model->extract();
        } else {
            $models = $this->getTweetService()->findRetweetsByTweetId($id);
            $result = [];
            /** @var AbstractModel $model */
            foreach ($models as $model) {
                $result[] = $model->extract();
            }
            $data['count'] = count($models);
            $data['items'] = $result;
        }

        Cache::put($path, $data, Config::get('cache.minutes_in_cache'));
    }

    public function __construct(TweetServiceInterface $service)
    {
        $this->tweetService = $service;
    }

    /**
     * @return TweetServiceInterface
     */
    public function getTweetService(): TweetServiceInterface
    {
        return $this->tweetService;
    }


}
