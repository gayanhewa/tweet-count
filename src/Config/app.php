<?php
/**
 *  Hardcoded keys , ideally pulled in from ENV varaibles
 */
return [
  'connector' => [
    'twitter' => [
      'consumer_key' => getenv('consumer_key'),
      'consumer_secret' => getenv('consumer_secret'),
      'access_token' => getenv('access_token'),
      'access_secret' => getenv('access_secret')
    ]
  ]
];
