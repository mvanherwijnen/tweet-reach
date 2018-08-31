<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Service\TweetService\TweetService;
use App\Service\TweetService\TweetServiceInterface;
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
            $clientConfig = $app['config']['twitter_oath'];
            $consumerKey = $clientConfig['consumer_key'];
            $consumerSecret = $clientConfig['consumer_secret'];
            $client = new TwitterOAuth($consumerKey, $consumerSecret);
            return $client;
        });
    }
}
