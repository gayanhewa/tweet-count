<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use TweetCount\Services\TwitterHourlyCountService;
use TweetCount\Services\TwitterStatusFetchService;

class TwitterAPIConnectorTest extends \PHPUnit_Framework_TestCase
{
  protected $container;

  public function setUp()
  {

    $this->container['config'] = require_once __DIR__.'/../src/Config/test.php';

    $container['twitter.oauthtoken'] = new TwitterOAuth(
        $this->container['config']['connector']['twitter']['consumer_key'],
        $this->container['config']['connector']['twitter']['consumer_secret'],
        $this->container['config']['connector']['twitter']['access_token'],
        $this->container['config']['connector']['twitter']['access_secret']
      );

    $container['twitter.hourly.count.service'] = new TwitterHourlyCountService();

    $container['twitter.status.fetch.service'] = new TwitterStatusFetchService($container['twitter.oauthtoken']);

    $container['api.connector'] = new TweetCount\Repositories\TwitterAPIConnector($container['twitter.hourly.count.service'], $container['twitter.status.fetch.service']);

    $this->container = $container;

  }

  public function testGetStatusesByUsername()
  {
    $results = $this->container['api.connector']->getStatusesByUsername('gayanhewa');
    $this->assertEquals(0, count($results));

  }

}
