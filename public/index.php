<?php
date_default_timezone_set('UTC');
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// debug is enabled by default
$app['debug'] = true;

/**
 *  Routing for the main applications sits below. Since this service is rather
 *  small , we will have the routes here it self
 */

$app['config.app'] = require_once __DIR__.'/../src/Config/app.php';

// Opting for service controllers. Adds more less of a complete IOC container
// capability for effective DI resolution
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// Service bindings for the current app
$app->register(new TweetCount\Providers\CoreServiceProvider($app));

/**
 *  Handle exceptions and 404 error responses
 */
$app->error(function (\Exception $e, Symfony\Component\HttpFoundation\Request $request, $code) use($app) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
            if($app['debug'] == true) {
              $message = $e->getMessage();
            }
    }

    return new Symfony\Component\HttpFoundation\Response(json_encode(['error'=>true, 'message' => $message]));
});

$app->get('/', function() use($app) {
  return 'Try /hello/:name';
});

$app->get('/hello/{name}', function($name) use($app) {

  return 'Hello ' . $app->escape($name);

});

$histogram = $app['controllers_factory'];
$histogram->get('/{username}', 'histogram.controller:getStatsByUsername');
$histogram->get('/{username}/mostactive', 'histogram.controller:getMostActiveHourByUsername');

$app->mount('/histogram', $histogram);

$app->run();
