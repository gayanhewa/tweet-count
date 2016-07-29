<?php

class TwitterHourlyCountTest extends \PHPUnit_Framework_TestCase
{

  public function testCountHourlyTweets()
  {
    // init
    $counter = new TweetCount\Services\TwitterHourlyCountService();

    // prep
    $statuses = [
        [
          'created_at'=> 'Fri Jul 29 00:12:58 +0000 2016'
        ],
        [
          'created_at'=> 'Fri Jul 29 00:15:58 +0000 2016'
        ],
        [
          'created_at'=> 'Fri Jul 29 01:10:58 +0000 2016'
        ],
        [
          'created_at'=> 'Fri Jul 29 01:12:50 +0000 2016'
        ],
        [
          'created_at'=> 'Fri Jul 29 01:20:18 +0000 2016'
        ]
    ];
    $statuses = array_map(function($item){
      return (object) $item;
    }, $statuses);


    $current_status = [];

    $result = $counter->count($statuses, $current_status);

    // hour 00:00
    $this->assertEquals(3, $result['status'][1]);

    // hour 01:00
    $this->assertEquals(2, $result['status'][0]);
  }

  public function testCountHourlyTweetsEmpty()
  {
    // init
    $counter = new TweetCount\Services\TwitterHourlyCountService();

    $current_status = [];

    $result = $counter->count([], $current_status);

    $this->assertEquals(0, count($result['status']));

  }
}
