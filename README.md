[![Build Status](https://travis-ci.org/gayanhewa/tweet-count.svg?branch=master)](https://travis-ci.org/gayanhewa/tweet-count)
# TweetCount Service

This is simple service that lets you query twitter user and gets the tweeting frequency.

## Installation

Clone this repo :

        git clone git@github.com:gayanhewa/tweet-count.git

To before executing the API ensure dependencies are installed by running :

        composer install
        
You can run this with the built in web server to test :

        php -S localhost:8080 public/index.php
        
Make sure you export the env variables for the config :

        source env.sh

Testing :
        
        curl http://localhost:8080/histogram/mashable

The solution provides 4 endpoints :

### GET /hello/{name}

A simple endpoint that echo's back the name entered.

### GET /histogram/{username}

This endpoint gets back the hourly stats of tweets for the given username. Includes stats for the current day.

### GET /histogram/{username}/mostactive

This endpoint echo's back the most active hour for that user with the number of tweets available. Calculated from stats for the current day.

## Folder Structure :
- public/index.php - the Entry point to the application
- src - Main Source Directory
- tests - Tests for the application
- src/Config - All config files are included
- Controllers - Controllers used with Routing
- Providers - Service Providers
- Repositories/Contracts - Interfaces for the Repositories
- Repositories - Repository implementations
- Services - Service Classes

## Design Decisions :

- Routes have been bind into Controllers that is kept thin
- Repositories have been used to encapsulate the API connector , giving flexibility to re-implement if changes with SDK's used or different Social network stats are needed ( ie. Add Facebook post stats for 24 hours ) .
- Services have been used to encapsulate the key business logic
- 3rd party twitter client for PHP was used to communicate with twitter API

## Notes :
- All times are in UTC
- Twitter API keys must be exported as ENV variables , sample file included.
