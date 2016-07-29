<?php

namespace TweetCount\Repositories\Contracts;

interface APIConnector
{
    /**
     *  Returns a collection of status updates for a given user
     */
    public function getStatusesByUsername($username);
}
