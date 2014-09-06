<?php
/*
|--------------------------------------------------------------------------
| Application "local" environment
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    | If debug enabled framework converts all php errors to exceptions.
    */                             
    'error' => array(
        'debug' => true,       // Friendly debugging feature should be "Disabled"" in "PRODUCTION" environment.
        'reporting' => true,  // Php "Native Error" reporting should be "Enabled" in "TEST" environment should be "Disabled"" in "PRODUCTION".
    ),
    /*
    |--------------------------------------------------------------------------
    | Log
    |--------------------------------------------------------------------------
    | @see Syslog Protocol http://tools.ietf.org/html/rfc5424
    |
    | Constants:
    |
    | 0  LOG_EMERG: System is unusable
    | 1  LOG_ALERT: Action must be taken immediately
    | 2  LOG_CRIT: Critical conditions
    | 3  LOG_ERR: Error conditions
    | 4  LOG_WARNING: Warning conditions
    | 5  LOG_NOTICE: Normal but significant condition
    | 6  LOG_INFO: Informational messages
    | 7  LOG_DEBUG: Debug-level messages
    */
    'log' =>   array(
        'enabled' => true,       // On / Off logging.
        'output'  => false,      // On / Off debug html output. When it is enabled all handlers will be disabled.
        'channel'   => 'system',       // Default channel name should be general.
        'line'      => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
        'path'      => array(
            'app'   => 'data/logs/app.log',   // Application log path  ( Only for File Handler )
            'cli'   => 'data/logs/cli.log',   // Cli log path  
            'ajax'  => 'data/logs/ajax.log',  // Ajax log path
            'worker' => 'data/logs/worker.log',  // Queue worker log path
        ),
        'format'    => 'Y-m-d H:i:s',  // Date format
        'queries'   => true,           // If true "all" SQL Queries gets logged.
        'benchmark' => true,           // If true "all" Application Benchmarks gets logged.
    ),
    /*
    |--------------------------------------------------------------------------
    | Http Url
    |--------------------------------------------------------------------------
    */
    'url' => array(
        'root'   => 'framework', // Your Virtual host name default "localhost" should be ".example.com" in production config.
        'base'   => '/',         // Base Url "/" URL of your framework root, generally a '/' trailing slash. 
        'assets' => '/',         // Assets Url of your framework generally a '/' trailing slash.
        'rewrite' => array(
            'indexPage' => '',   // Typically this will be your index.php file, If mod_rewrite enabled is should be blank.
            'suffix'    => '',   // Allows you to add a suffix to all URLs generated by Framework.
        )
    ),
    /*
    |--------------------------------------------------------------------------
    | Http Uri
    |--------------------------------------------------------------------------
    */
    'uri' => array(             // Auto detects the URI protocol 
        'protocol' => 'AUTO',   // Default option is 'AUTO', Options: REQUEST_URI, QUERY_STRING, PATH_INFO Example : http://example.com/login?param=1&param2=yes
        'permittedChars' => 'a-z 0-9~%.:_-',  // Allowed URL Characters ,this lets you specify with a regular expression which characters are permitted within your URLs.
        'queryStrings' => true,  // Allows based URLs: example.com/directory/controller?who=me&what=something&where=here
        'extensions' => array('.json','.xml'),   // Allows extension based URLs: example.com/api/get/users.json
    ),
    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    */
    'locale' => array(
        'timezone' => 'gmt',      // This pref tells the system whether to use your server's "local" time as the master now reference, or convert it to "gmt".
        'charset'  => 'UTF-8',    //  This determines which character set is used by default.
        'date' => array(
            'php_date_default_timezone' => 'Europe/London',  // Sets timezone using php date_default_timezone_set(); function.
            'format' => 'H:i:s d:m:Y'
        )
     ),
    /*
    |--------------------------------------------------------------------------
    | Databases
    |--------------------------------------------------------------------------
    */
   'database' => '@include.database.php',
    /*
    |--------------------------------------------------------------------------
    | Sessions
    |--------------------------------------------------------------------------
    */
    'session' => '@include.session.php',
    /*
    |--------------------------------------------------------------------------
    | NoSQL Databases
    |--------------------------------------------------------------------------
    */
   'nosql' => '@include.nosql.php',
    /*
    |--------------------------------------------------------------------------
    | Mail
    |--------------------------------------------------------------------------
    */
    'mail' => '@include.mail.php',
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    'cache' =>  '@include.cache.php',
    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    */
    'queue' => '@include.queue.php',
    /*
    |--------------------------------------------------------------------------
    | View Schemes
    |--------------------------------------------------------------------------
    */
    'schemes' => array(
        'default' => function () {
            $this->assign('header', '@layer.views/header');
            $this->assign('sidebar', '@layer.views/sidebar');
            $this->assign('footer', $this->template('footer'));
        },
        'welcome' => function () {
            $this->assign('footer', $this->template('footer'));
        },
    ),
    /*
    |--------------------------------------------------------------------------
    | Layers
    |--------------------------------------------------------------------------
    */
    'layers' => array(
        'cache' => false     // if you use expiration ( ttl ) as last parameter, layers will do cache using your cache service.
    ),
    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'security' => array(
        'encryption' => array(
            'key' => 'write-your-secret-key',  // If you use the Encryption class you MUST set an encryption key.
        ),
        'csrf' => array(                      
            'protection'  => false,          // Enables a CSRF cookie token to be set. When set to true, token will be
            'token_name'  => 'csrf_token',   // checked on a submitted form. If you are accepting user data, it is strongly
            'cookie_name' => 'csrf_cookie',  // recommended CSRF protection be enabled.
            'expire'      => '7200',         // The number in seconds the token should expire.
         ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Cookies
    |--------------------------------------------------------------------------
    */
    'cookie' => array( 
        'prefix' => '',                          // Set a prefix if you need to avoid collisions
        'domain' => '',                          // Set to .your-domain.com for site-wide cookies
        'path'   => '/',                         // Typically will be a forward slash
        'expire' => (7 * 24 * 60 * 60),          // 1 week - Cookie expire time.
        'secure' => false,                       // Cookies will only be set if a secure HTTPS connection exists.
    ),
    /*
    |--------------------------------------------------------------------------
    | Proxy
    |--------------------------------------------------------------------------
    */
    'proxy' => array(     // Reverse Proxy IPs , If your server is behind a reverse proxy, you must whitelist the proxy IP
        'ips' => '',      // addresses from which the Application should trust the HTTP_X_FORWARDED_FOR
    ),                    // header in order to properly identify the visitor's IP address.
                          // Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */
    'output' => array(
        'compress' => false,  // Enables Gzip output compression for faster page loads.  When enabled,
    ),                        // the Response class will test whether your server supports Gzip.
                              // Even if it does, however, not all browsers support compression
                              // so enable only if you are reasonably sure your visitors can handle it.
);

/* End of file config.php */
/* Location: .app/env/local/config.php */
