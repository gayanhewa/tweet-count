<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use TweetCount\Services\TwitterHourlyCountService;
use TweetCount\Services\TwitterStatusFetchService;

class TwitterStatusFetchServiceTest extends \PHPUnit_Framework_TestCase
{
  protected $container;

  public function setUp()
  {
    
    $this->container['config'] = require __DIR__.'/../src/Config/test.php';

    $container['twitter.oauthtoken'] = new TwitterOAuth(
        $this->container['config']['connector']['twitter']['consumer_key'],
        $this->container['config']['connector']['twitter']['consumer_secret'],
        $this->container['config']['connector']['twitter']['access_token'],
        $this->container['config']['connector']['twitter']['access_secret']
      );

    $container['twitter.hourly.count.service'] = new TwitterHourlyCountService();

    $container['twitter.status.fetch.service'] =  new TwitterStatusFetchService($container['twitter.oauthtoken']);

    $this->container = $container;

  }

  public function testFetch()
  {
    $service = $this->container['twitter.status.fetch.service'];

    $result = $service->fetch('mashable', null, 2);
    $this->assertEquals(2, count($result));
  }
}
