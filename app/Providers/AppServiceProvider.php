<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Service\TweetService\TweetService;
use App\Service\TweetService\TweetServiceInterface;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TweetServiceInterface::class, function (Application $app) {
            return new TweetService($app->make(TwitterOAuth::class));
        });

        $this->app->singleton(TwitterOAuth::class, function (Application $app) {
        	/** @var Repository $config */
        	$config = $app->make(Repository::class);
            $clientConfig = $config->get('twitter_oauth');

            foreach($clientConfig as $key => $value) {
            	if (empty($value)) {
            		$class = TwitterOAuth::class;
            		throw new \RuntimeException("$key is incorrectly configured for $class. Check the environment configuration.");
	            }
            }

            $client = new TwitterOAuth(
            	$clientConfig['consumer_key'],
	            $clientConfig['consumer_secret'],
	            $clientConfig['oauth_access_token'],
	            $clientConfig['oauth_access_token_secret']
            );

            return $client;
        });
    }
}
