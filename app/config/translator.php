<?php

/*
|--------------------------------------------------------------------------
| Translator Config
|--------------------------------------------------------------------------
| Configure set translator package options.
|
*/
return array(

    'notice' => false,     // Puts 'translate:' texts everywhere it is help you for multilingual development.

    // Locale Settings
    'locale' => array(
        'default'  => 'en',   // This determines which set of language files should be used.
        'fallback' => 'en',   // If language not determined fallback locale will be set.
        'setCookie' => true,  // Writes locale name ( en ) to cookie 
    ),
            
    // Uri Settings
    'uri' => array(
        'segment'       => true, // Uri segment number e.g. http://example.com/en/home
        'segmentNumber' => 0       
    ),

    // Cookies
    'cookie' => array(
        'name'   =>'locale',
        'prefix' => '',
        'domain' => '',       // Set to .your-domain.com for site-wide cookies
        'path'   => '/',      // Typically will be a forward slash
        'expire' => (365 * 24 * 60 * 60),  // 365 day; //  @see  Cookie expire time.   http://us.php.net/strtotime
        'secure' => false,    // Cookies will only be set if a secure HTTPS connection exists.
    ),

    // Available Languages
    'languages' => array(
                        'en' => 'english',
                        'es' => 'spanish',
                        'de' => 'deutsch',
                        'tr' => 'turkish',
                        ),

    // Iso Language Codes
    // http://www.microsoft.com/resources/msdn/goglobal/default.mspx
    'isoCodes' => array(
                        'en' => 'en_US',
                        'es' => 'es_US',
                        'de' => 'de_DE',
                        'tr' => 'tr_TR',
    ),
);

/* End of file translator.php */
/* Location: ./app/config/translator.php */