<?php

namespace App\Listeners;

use App\Model\AbstractModel;
use App\Service\Cache\CacheAwareInterface;
use App\Service\Cache\CacheAwareTrait;
use App\Service\Config\ConfigAwareInterface;
use App\Service\Config\ConfigAwareTrait;
use App\Service\TweetService\TweetServiceInterface;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;

class UpdateCache implements CacheAwareInterface, ConfigAwareInterface
{
	use CacheAwareTrait;
	use ConfigAwareTrait;
    /** @var TweetServiceInterface */
    protected $tweetService;

    public function handle(KeyForgotten $event){
        //TODO Replay request. Now we end up with a handler that contains duplicate code and is too concrete
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
		$minutesInCache = $this->getConfig()->get('cache.minutes_in_cache');
        $this->getCache()->set($path, $data, $minutesInCache);
    }

    public function __construct(
    	TweetServiceInterface $service,
		Cache $cache,
		Config $config
    ){
        $this->tweetService = $service;
        $this->setCache($cache);
        $this->setConfig($config);
    }

    /**
     * @return TweetServiceInterface
     */
    public function getTweetService(): TweetServiceInterface
    {
        return $this->tweetService;
    }


}
