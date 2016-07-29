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


    return json_encode($this->connector->getStatusesByUsername($username));

  }

}
