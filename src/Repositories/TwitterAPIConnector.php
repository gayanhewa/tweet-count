<?php

namespace TweetCount\Repositories;

use TweetCount\Repositories\Contracts\APIConnector;
use TweetCount\Services\TwitterHourlyCountService;
use TweetCount\Services\TwitterStatusFetchService;

class TwitterAPIConnector implements APIConnector
{

    protected $status = [];
    protected $counter;
    protected $api;

    // Injecting the App object from the main run time.
    public function __construct(TwitterHourlyCountService $twitterCountService, TwitterStatusFetchService $twitterStatusFetchService)
    {

      $this->counter = $twitterCountService;
      $this->api = $twitterStatusFetchService;
    }
    /**
     *  Returns a collection of status updates for a given user
     */
    public function getStatusesByUsername($username)
    {

      try{

        // control variable for the infinite while loop
        $gate = 1;
        $max_id = null;

        while($gate) {

          // fetch the status for that user from twitter
          $statuses = $this->api->fetch($username, $max_id);

          // if there are any errors in the response. Quit the loop
          if (! $statuses) {
            $gate = 0;
            break;
          }

          list($gate, $this->status) = $this->counter->count($statuses, $this->status);

          // If the user has twitted more than 200 status updaes within the day
          // we will have to query again. To make sure counts are in order we use
          // twitter max_id , to get responses from the last item. Offsetting
          // everything else we have processed.
          // https://dev.twitter.com/rest/public/timelines
          if (count($this->status) > 0) {

            $last_item = $this->status[count($this->status)-1];
            $max_id = $last_item[count($last_item)-1];

          }

        }

      } catch (\Exception $e) {
         // for simplicity sake
         error_log($e->getMessage());
         $this->status = ['Something went wrong, please try again.'];
      }

      return $this->status;
    }

}
