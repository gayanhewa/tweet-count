<?php

namespace TweetCount\Services;

use \Carbon\Carbon;

class TwitterHourlyCountService
{

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
        if (! isset($current_status['count'][$date->hour])) {
          $current_status['count'][$date->hour] = 0;
        }

        // increment if an entry is found
        $current_status['count'][$date->hour]++;

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
