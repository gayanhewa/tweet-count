<?php

namespace TweetCount\Controllers;

use TweetCount\Repositories\Contracts\APIConnector;

class HistogramServiceController
{
  protected $connector;

  public function __construct(APIConnector $connector)
  {
      $this->connector = $connector;
  }


  public function getStatsByUsername($username)
  {

    $result = [ 'error' => false, 'message' => $this->connector->getStatusesByUsername($username) ];

    return json_encode($result);

  }

  public function getMostActiveHourByUsername($username)
  {

    $result = [ 'error' => false, 'message' => $this->connector->getMostActiveHourByUsername($username) ];

    return json_encode($result);
  }

}
