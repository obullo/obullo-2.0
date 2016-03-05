<?php
/*
|--------------------------------------------------------------------------
| Disable php.ini errors to use set_error_handler() func
|--------------------------------------------------------------------------
*/
ini_set('display_errors', 1);
/*
|--------------------------------------------------------------------------
| Disable all php errors to use set_error_handler() func
|--------------------------------------------------------------------------
*/
error_reporting(1);
/*
|--------------------------------------------------------------------------
| Constants.
|--------------------------------------------------------------------------
*/
require '../constants';
/*
|--------------------------------------------------------------------------
| Register Autoloader
|--------------------------------------------------------------------------
*/
require '../vendor/autoload.php';
/*
|--------------------------------------------------------------------------
| Only for parse errors
|--------------------------------------------------------------------------
*/
register_shutdown_function(
    function () {
        $error = error_get_last();
        if (! empty($error) && $error['type'] == E_PARSE) {
            echo $error['message']." File: ".$error['file']." Line : ".$error['line'];
        }
    }
);
require OBULLO .'Application/Autoloader.php';
Obullo\Application\Autoloader::register();
/*
|--------------------------------------------------------------------------
| Set timezone identifier
|--------------------------------------------------------------------------
*/
date_default_timezone_set('Europe/London');
/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/
require OBULLO .'Application/Http/Bootstrap.php';
/*
|--------------------------------------------------------------------------
| Middleware pipe
|--------------------------------------------------------------------------
*/
$app = new Obullo\Http\Zend\Stratigility\MiddlewarePipe($container);
/*
|--------------------------------------------------------------------------
| Create your http server
|--------------------------------------------------------------------------
*/
$server = Obullo\Http\Zend\Diactoros\Server::createServerFromRequest(
    $app,
    Obullo\Log\Benchmark::start($app->getRequest()),
    $app->getResponse()
);
/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/
$server->listen();