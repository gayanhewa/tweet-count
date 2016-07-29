<?php

namespace TweetCount\Services;

use \Carbon\Carbon;

class TwitterHourlyCountService
{

  /**
   *  Quick hack to format the time
   */
  protected $hour_map = [
    0 => '00:00',
    1 => '01:00',
    2 => '02:00',
    3 => '03:00',
    4 => '04:00',
    5 => '05:00',
    6 => '06:00',
    7 => '07:00',
    8 => '08:00',
    9 => '09:00',
    10 => '10:00',
    11 => '11:00',
    12 => '12:00',
    13 => '13:00',
    14 => '14:00',
    15 => '15:00',
    16 => '16:00',
    17 => '17:00',
    18 => '18:00',
    19 => '19:00',
    20 => '20:00',
    21 => '21:00',
    22 => '22:00',
    23 => '23:00'
  ];

  public function count(array $statuses, array $current_status = [])
  {

    $gate = 1;

    // Loop trough the provided tweets so we can group the count by the hour
    foreach($statuses as $status) {

      // Get todays date for the exit condition. The api only returns
      // tweets for the current day by hour
      $today = Carbon::now();

      $date = new Carbon($status->created_at);

      // Check if the api response has anything from the previous day. If so
      // we quite the process.
      if($date->day == $today->day) {

        $current_status['ids'][$date->hour][] = $status->id;
        // initialize the count
        if (! isset($current_status['count'][$this->hour_map[$date->hour]])) {
          $current_status['count'][$this->hour_map[$date->hour]] = 0;
        }

        // increment if an entry is found
        $current_status['count'][$this->hour_map[$date->hour]]++;

      }else{

        // We have processed all the status updates for today , quit the
        // loop

        $gate = 0;
        break;

      }

    }

    return [
      'gate' => $gate,
      'status' => $current_status
    ];

  }
}
