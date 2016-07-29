<?php

namespace TweetCount\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use TweetCount\Repositories\TwitterAPIConnector;
use TweetCount\Controllers\HistogramServiceController;
use Silex\Application;
use Abraham\TwitterOAuth\TwitterOAuth;
use TweetCount\Services\TwitterHourlyCountService;
use TweetCount\Services\TwitterStatusFetchService;

class CoreServiceProvider implements ServiceProviderInterface
{

  protected $app;
  protected $config;

  public function __construct(Application $app)
  {
      $this->app = $app;
      $this->config = $app['config.app'];
  }

  public function register(Container $container)
  {

    $container['twitter.oauthtoken'] = function() {

      return new TwitterOAuth(
        $this->config['consumer_key'],
        $this->config['consumer_secret'],
        $this->config['access_token'],
        $this->config['access_secret']);
      );

    };

    $container['twitter.hourly.count.service'] = function() {
      return new TwitterHourlyCountService();
    };

    $container['twitter.status.fetch.service'] = function() {
      return new TwitterStatusFetchService();
    };

    $container['api.connector'] = function() use($container){
      return new TwitterAPIConnector($container['twitter.hourly.count.service'], $container['twitter.status.fetch.service']);
    };

    $container['histogram.controller'] = function() use($container) {
      return new HistogramServiceController($container['api.connector']);
    };

  }


}
