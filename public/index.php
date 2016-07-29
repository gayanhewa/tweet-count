<?php
date_default_timezone_set('UTC');
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
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



// Forcing the content type header. When ever someone tests the api on a browser
// it duplicates the api calls sine browsers like chrome send a subsequent
// background request to fetch the favicon GET /favicon
// this isn't necessary since most cases the api will be consumed
// programatically
// $app->before(function (Symfony\Component\HttpFoundation\Request $request) {
//     if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
//         $data = json_decode($request->getContent(), true);
//         $request->request->replace(is_array($data) ? $data : array());
//     }else{
//        die('Invalid Content Type Header');
//     }
// });



$app->get('/{username}', 'histogram.controller:getStatsByUsername');
$histogram = $app['controllers_factory'];
$histogram->get('/{username}', 'TweetCount\Controllers\HistogramServiceController::getStatsByUsername');
$app->mount('/histogram', $histogram);

$app->run();
