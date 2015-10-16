<?php

return array(
    
    /**
     * Cache storage
     *
     * Key: Cache key name
     * lifetime : Session lifetime 3600 = 1 hour.
     */
    'storage' => [
        'key' => 'sessions:',
        'lifetime' => 3600,
    ],

    /**
     * Session cookie
     *
     * Expire   : if "0" session will expire automatically when the browser window is closed
     * name     : Session cookie name you want for the cookie
     * domain   : Set to .your-domain.com for site-wide cookies
     * path     : Typically will be a forward slash
     * secure   : When set to true, the cookie will only be set if a https:// connection exists.
     * httpOnly : When true the cookie will be made accessible only through the HTTP protocol
     * prefix   : Optionally you can set a prefix for your cookie
     */
    'cookie' => [
        'expire' => 0,
        'name'     => 'session',
        'domain'   => '',
        'path'     => '/',
        'secure'   => false,
        'httpOnly' => false, 
        'prefix'   => '', 
    ],
);