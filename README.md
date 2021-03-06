
### Php Framework

Fast & simple development.

### Philosophy

* The smaller parts that consist the whole framework should be compatible with each other.

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/obullo/framework?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge) [![Follow us on twitter !](https://img.shields.io/badge/twitter-follow me-green.svg?style=flat-square)](http://twitter.com/obullo)

### Status

----

There is no release yet, we are still working on it.

### Test Installation

Create your composer.json file and dependencies

```json
{
    "autoload": {
        "psr-4": {
            "": "app/classes"
        },
        "files": [
            "vendor/ircmaxell/password-compat/lib/password.php"
        ]
    },
    "require": {
        "psr/http-message": "^1.0",
        "ircmaxell/password-compat": "^1.0",
        "league/container": "^2.0",
        "league/event": "^2.1"
    }
}
```

Create your obullo version

```php
composer require obullo/obullo dev-master
```

Update dependencies

```
composer update
composer dump-autoload
```

### Configuration of Vhost File

Put the latest version to your web root (<kbd>/var/www/project/</kbd>). Create your apache vhost file and set your project root as <kbd>public</kbd>.

```xml
<VirtualHost *:80>
        ServerName project.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/project/public
</VirtualHost>
```

### Configuration of Index.php

When you setup your application you have two options to work with Http middlewares.

#### Configuring Zend Stratigility

It's an advanced middleware solution from zend.

Open your index.php and update <kbd>$app</kbd> variable to Http\Zend\Stratigility\MiddlewarePipe;

```php
/*
|--------------------------------------------------------------------------
| Choose your middleware app
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
```

Learn more details about <a href="https://github.com/zendframework/zend-stratigility" target="_blank">zend middleware</a>.
