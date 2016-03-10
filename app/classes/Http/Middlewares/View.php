<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\Controller\ControllerAwareInterface;

class View implements MiddlewareInterface, ControllerAwareInterface
{
    /**
     * Inject controller object
     * 
     * @param \Obullo\Controller\Controller $controller object
     * 
     * @return void
     */ 
    public function setController($controller)
    {
        if (method_exists($controller, '__invoke')) {  // Assign layout variables
            $controller();
        }
    }

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $err = null;

        return $next($request, $response, $err);
    }
}