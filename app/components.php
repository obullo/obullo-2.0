<?php
/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
| Specifies the your application components which they available by default.
*/
/*
|--------------------------------------------------------------------------
| Event
|--------------------------------------------------------------------------
*/
$c['event'] = function () use ($c) {
    return new Obullo\Event\Event($c);
};
/*
|--------------------------------------------------------------------------
| Exception
|--------------------------------------------------------------------------
*/
$c['exception'] = function () use ($c) {
    return new Obullo\Error\Exception($c);
};
/*
|--------------------------------------------------------------------------
| Translator
|--------------------------------------------------------------------------
*/
$c['translator'] = function () use ($c) {
    return new Obullo\Translation\Translator($c);
};
/*
|--------------------------------------------------------------------------
| Http Request
|--------------------------------------------------------------------------
*/
$c['request'] = function () use ($c) { 
    return new Obullo\Http\Request($c);
};
/*
|--------------------------------------------------------------------------
| Http Response
|--------------------------------------------------------------------------
*/
$c['response'] = function () use ($c) { 
    return new Obullo\Http\Response($c);
};
/*
|--------------------------------------------------------------------------
| Input Validate Filter
|--------------------------------------------------------------------------
*/
$c['is'] = function () use ($c) {
    return new Obullo\Filters\Is($c);
};
/*
|--------------------------------------------------------------------------
| Input Clean Filter
|--------------------------------------------------------------------------
*/
$c['clean'] = function () use ($c) {
    return new Obullo\Filters\Clean($c);
};
/*
|--------------------------------------------------------------------------
| Http User Agent
|--------------------------------------------------------------------------
*/
$c['agent'] = function () use ($c) {
    return new Obullo\Http\UserAgent($c);
};
/*
|--------------------------------------------------------------------------
| Layers
|--------------------------------------------------------------------------
*/
$c['layer'] = function () use ($c) { 
    return new Obullo\Layer\Request($c);
};
/*
|--------------------------------------------------------------------------
| Uri
|--------------------------------------------------------------------------
*/
$c['uri'] = function () use ($c) {
    return new Obullo\Uri\Uri($c);
};
/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/
$c['router'] = function () use ($c) { 
    return new Obullo\Router\Router($c);
};


/* End of file components.php */
/* Location: .app/components.php */