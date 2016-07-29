<?php

namespace TweetCount\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterStatusFetchService
{
  protected $api;

  public function __construct(TwitterOAuth $twitterAPI)
  {
      $this->api = $twitterAPI;
  }

  public function fetch($username, $max_id = null, $count = 200, $options = [])
  {

      $params = [];
      $params['screen_name'] = $username;
      $params['count'] = $count;

      if (!is_null($max_id)) {
        // avoid duplicating the item at the end
        $params['max_id'] = $max_id-1;
      }

      foreach($options as $option=>$value) {
        $params[$option] = $value;
      }

      // fetch the status for that user from twitter
      $statuses = (array) $this->api->get("statuses/user_timeline", $params);

      // if there are any errors in the response. Quit the loop
      if (isset($statuses['errors']) && count($statuses['errors']) > 0) {
        throw new \Exception($statuses['errors'][0]->message);
      }

      return $statuses;
  }

}
