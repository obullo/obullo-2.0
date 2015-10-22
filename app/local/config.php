<?php

return array(

    /**
     * Errors
     * 
     * Debug : Friendly UI debugging feature, you can disable it in "production" environment.
     */
    'error' => [
        'debug' => true,
    ],

    /**
     * Log
     *
     * Enabled: On / off logging
     *
     * If logging is enabled all errors will pass through logging system otherwise they will be raised as native php errors.
     */
    'log' => [
        'enabled' => true,
    ],

    /**
     * Url
     * 
     * Webhost : Your web host name default "localhost" should be "example.com" in production config
     * baseurl : Base Url "/" URL of your framework root, generally a '/' trailing slash. 
     * assets
     *     url    : Assets url of your framework generally a '/' you may want to change it with your "cdn" provider.
     *     folder : Full path of assets folder
     * rewrite : 
     *     index.php : Typically this will be your index.php file, If mod_rewrite enabled is should be blank. 
     */
    'url' => [
        'webhost'  => 'framework',
        'baseurl'  => '/',
        'assets'   => [
            'url' => '/',
            'folder' => '/resources/assets/',
        ],    
        'rewrite' => [
            'index.php' => ''
        ]
    ],

    /**
     * Debugger
     *
     * Enabled : On / off http debugger web socket data activity
     * socket  : Websocket connection url and port
     */
    'http' => [
        'debugger' => [
            'enabled' => false,
            'socket' => 'ws://127.0.0.1:9000'
        ]
    ],

    /**
     * Locale
     *
     * Timezone : This pref tells the system whether to use your server's "local" time as the master now reference, or convert it to "gmt".
     * charset  : This pref determines which character set is used by default.
     * date:
     *   php_date_default_timezone : Sets timezone using php date_default_timezone_set(); function.
     *   format : Sets default application date format.
     */
    'locale' => [
        'timezone' => 'gmt',
        'charset'  => 'UTF-8',
        'date' => [
            'php_date_default_timezone' => 'Europe/London',
            'format' => 'H:i:s d:m:Y'
        ]
     ],

    /**
     * Layers
     *
     * Cache : On / off layer cache feature.
     */
    'layer' => [
        'cache' => false
    ],

    /**
     * Cookies
     *
     * Domain   : Set to .your-domain.com for site-wide cookies
     * path     : Typically will be a forward slash,
     * secure   : Cookies will only be set if a secure HTTPS connection exists.
     * httpOnly : When true the cookie will be made accessible only through the HTTP protocol
     * expire   : 1 week - Cookie expire time
     * prefix   : Set a prefix if you need to avoid collisions
     */
    'cookie' => [ 
        'domain' => '',
        'path'   => '/',
        'secure' => false,
        'httpOnly' => false,
        'expire' => 604800,
        'prefix' => '',
    ],

    /**
     * Proxy
     *
     * Ips : Reverse Proxy IPs , If your server is behind a reverse proxy, you must whitelist the proxy IP
     *       addresses from which the Application should trust the HTTP_X_FORWARDED_FOR
     *       header in order to properly identify the visitor's IP address.
     *       Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
     */
    'trusted' => [
        'ips' => '',
    ],

    /**
     * Sets your encryption key and protection settings.
     *
     * Encryption 
     *   key:   If you use the Encryption class you MUST set an encryption key.
     *   
     * Csrf
     *   protection : Enables a CSRF session token to be set. When set to true, token will be checked on a submitted form.
     *   If you are accepting user data, it is strongly recommended CSRF protection be enabled.
     *   Token
     *     name : Csrf token name
     *     refresh : Refresh the csrf token every x seconds default 30 seconds.
     */
    'security' => [
        'encryption' => [
            'key' => 'write-your-secret-key',
        ],
        'csrf' => [                      
            'protection' => true,
            'token' => [
                'name' => 'csrf_token',
                'refresh' => 30,
            ],    
         ],     
    ],

);