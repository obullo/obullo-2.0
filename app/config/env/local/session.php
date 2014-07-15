<?php
/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    'handler' => 'Cache',              // Available handlers: Cache, Database, Mongo
    'cookie' => array(
        'name'     => 'session',       // The name you want for the cookie
        'domain'   => '',              // Set to .your-domain.com for site-wide cookies
        'path'     => '/',             // Typically will be a forward slash
        'secure'   => false,           // When set to true, the cookie will only be set if a secure connection exists.
        'httpOnly' => false,           // When true the cookie will be made accessible only through the HTTP protocol
        'prefix'   => '',              // Set a prefix to your cookie
    ),
    'storageKey' => 'o2_sessions:',    // Don't remove ":" colons. Your cache handler keeps keys in folders using colons.
    'lifetime'       => 7200,          // The number of SECONDS you want the session to last. By default " 2 hours ". "0" is no expiration.
    'expireOnClose'  => true,          // Whether to cause the session to expire automatically when the browser window is closed
    'timeToUpdate'   => 1,             // How many seconds between framework refreshing "Session" meta data Information"
    'rememberMeSeconds' => 604800,     // Remember me ttl for session reminder class. By default " 1 Week ".
    'metaData' => array(
        'enabled' => false,
        'matchIp' => false,         // Whether to match the user's IP address when reading the session data
        'matchUserAgent' => true    // Whether to match the User Agent when reading the session data
    )
);

/* End of file session.php */
/* Location: .app/env/local/session.php */