<?php
namespace Ob\Security;

/**
 * Security Class
 *
 * @package     Obullo
 * @subpackage	security
 * @category	Securities
 * @author	Obullo Team
 * @link
 */

Class Security {
	
    protected $_xss_hash          = '';
    protected $_csrf_hash         = '';
    protected $_csrf_expire       = 7200;  // Two hours (in seconds)
    protected $_csrf_token_name   = 'ob_csrf_token';
    protected $_csrf_cookie_name  = 'ob_csrf_token';

    /* never allowed, string replacement */
    protected $_never_allowed_str = array(
                                    'document.cookie'	=> '[removed]',
                                    'document.write'	=> '[removed]',
                                    '.parentNode'	=> '[removed]',
                                    '.innerHTML'	=> '[removed]',
                                    'window.location'	=> '[removed]',
                                    '-moz-binding'	=> '[removed]',
                                    '<!--'		=> '&lt;!--',
                                    '-->'		=> '--&gt;',
                                    '<![CDATA['		=> '&lt;![CDATA['
    );

    /* never allowed, regex replacement */
    protected $_never_allowed_regex = array(
                                    "javascript\s*:"		=> '[removed]',
                                    "expression\s*(\(|&\#40;)"	=> '[removed]', // CSS and IE
                                    "vbscript\s*:"		=> '[removed]', // IE, surprise!
                                    "Redirect\s+302"		=> '[removed]'
    );
    
    public static $instance;
    
    /**
    * Constructor
    */
    public function __construct()
    {
        foreach(array('csrf_expire', 'csrf_token_name', 'csrf_cookie_name') as $key)  // CSRF config
        {
            if (false !== ($val = \Ob\config($key)))
            {
                $this->{'_'.$key} = $val;
            }
        }

        // Append application specific cookie prefix
        if (config('cookie_prefix'))
        {
            $this->_csrf_cookie_name = config('cookie_prefix').$this->_csrf_cookie_name;
        }

        // Set the CSRF hash
        $this->_csrfSetHash();

        \Ob\log\me('debug', "Security Class Initialized");
    }
        
    // --------------------------------------------------------------------

    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Verify Cross Site Request Forgery Protection
    *
    * @return	object
    */
    public function csrfVerify()
    {
        // If no POST data exists we will set the CSRF cookie
        if (count($_POST) == 0)
        {
            return $this->csrfSetCookie();
        }

        // Do the tokens exist in both the _POST and _COOKIE arrays?
        if ( ! isset($_POST[$this->_csrf_token_name]) OR ! isset($_COOKIE[$this->_csrf_cookie_name]))
        {
            $this->csrfShowError();
        }

        // Do the tokens match?
        if ($_POST[$this->_csrf_token_name] != $_COOKIE[$this->_csrf_cookie_name])
        {
            $this->csrfShowError();
        }

        // We kill this since we're done and we don't want to 
        // polute the _POST array
        unset($_POST[$this->_csrf_token_name]);

        // Nothing should last forever
        unset($_COOKIE[$this->_csrf_cookie_name]);
        
        $this->_csrfSetHash();
        $this->csrfSetCookie();

        \Ob\log\me('debug', "CSRF token verified ");

        return $this;
    }
    
    // --------------------------------------------------------------------

    /**
    * Set Cross Site Request Forgery Protection Cookie
    *
    * @return	object
    */
    public function csrfSetCookie()
    {
        $expire = time() + $this->_csrf_expire;
        $secure_cookie = (\Ob\config('cookie_secure') === true) ? 1 : 0;

        if ($secure_cookie)
        {
            # if your HTTP server NGINX add below the line to your fastcgi_params file.
            # fastcgi_param  HTTPS		  $ssl_protocol;
            # then $_SERVER['HTTPS'] variable will be available for PHP (fastcgi).

            $req = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : false;

            if ( ! $req OR $req == 'off')
            {
                return false;
            }
        }

        setcookie($this->_csrf_cookie_name, $this->_csrf_hash, $expire, \Ob\config('cookie_path'), \Ob\config('cookie_domain'), $secure_cookie);

        \Ob\log\me('debug', "CRSF cookie Set");

        return $this;
    }
    
    // --------------------------------------------------------------------
   
    /**
    * Show CSRF Error
    *
    * @return	void
    */
    public function csrfShowError()
    {
        \Ob\showError('The action you have requested is not allowed.');
    }
    
    // --------------------------------------------------------------------
        
    /**
    * Get CSRF Hash 
    *
    * Getter Method 
    *
    * @return 	string 	self::_csrf_hash
    */
    public function getCsrfHash()
    {
        return $this->_csrf_hash;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get CSRF Token Name
    *
    * Getter Method
    *
    * @return 	string 	self::csrf_token_name
    */
    public function getCsrfTokenName()
    {
        return $this->_csrf_token_name;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * XSS Clean
    *
    * Sanitizes data so that Cross Site Scripting Hacks can be
    * prevented.  This function does a fair amount of work but
    * it is extremely thorough, designed to prevent even the
    * most obscure XSS attempts.  Nothing is ever 100% foolproof,
    * of course, but I haven't been able to get anything passed
    * the filter.
    *
    * Note: This function should only be used to deal with data
    * upon submission.  It's not something that should
    * be used for general runtime processing.
    *
    * This function was based in part on some code and ideas I
    * got from Bitflux: http://channel.bitflux.ch/wiki/XSS_Prevention
    *
    * To help develop this script I used this great list of
    * vulnerabilities along with a few other hacks I've
    * harvested from examining vulnerabilities in other programs:
    * http://ha.ckers.org/xss.html
    *
    * @param	mixed	string or array
    * @return	string
    */
    public function xssClean($str, $is_image = false)
    {
        /*
         * Is the string an array?
         *
         */
        if (is_array($str))
        {
            while (list($key) = each($str))
            {
                $str[$key] = $this->xssClean($str[$key]);
            }

            return $str;
        }

        /*
         * Remove Invisible Characters
         */
        $str = Ob\removeInvisibleCharacters($str);

        // Validate Entities in URLs
        $str = $this->_validateEntities($str);

        /*
         * URL Decode
         *
         * Just in case stuff like this is submitted:
         *
         * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
         *
         * Note: Use rawurldecode() so it does not remove plus signs
         *
         */
        $str = rawurldecode($str);

        /*
         * Convert character entities to ASCII
         *
         * This permits our tests below to work reliably.
         * We only convert entities that are within tags since
         * these are the ones that will pose security problems.
         *
         */

        $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
        $str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array($this, '_decode_entity'), $str);

        /*
         * Remove Invisible Characters Again!
         */
        $str = Ob\removeInvisibleCharacters($str);

        /*
         * Convert all tabs to spaces
         *
         * This prevents strings like this: ja	vascript
         * NOTE: we deal with spaces between characters later.
         * NOTE: preg_replace was found to be amazingly slow here on 
         * large blocks of data, so we use str_replace.
         */

        if (strpos($str, "\t") !== false)
        {
            $str = str_replace("\t", ' ', $str);
        }

        /*
         * Capture converted string for later comparison
         */
        $converted_string = $str;

        // Remove Strings that are never allowed
        $str = $this->_doNeverAllowed($str);

        /*
         * Makes PHP tags safe
         *
         * Note: XML tags are inadvertently replaced too:
         *
         * <?xml
         *
         * But it doesn't seem to pose a problem.
         */
        if ($is_image === true)
        {
            // Images have a tendency to have the PHP short opening and 
            // closing tags every so often so we skip those and only 
            // do the long opening tags.
            $str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str);
        }
        else
        {
            $str = str_replace(array('<?', '?'.'>'),  array('&lt;?', '?&gt;'), $str);
        }

        /*
         * Compact any exploded words
         *
         * This corrects words like:  j a v a s c r i p t
         * These words are compacted back to their correct state.
         */
        $words = array(
                        'javascript', 'expression', 'vbscript', 'script', 
                        'applet', 'alert', 'document', 'write', 'cookie', 'window'
                );

        foreach ($words as $word)
        {
            $temp = '';

            for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++)
            {
                $temp .= substr($word, $i, 1)."\s*";
            }

            // We only want to do this when it is followed by a non-word character
            // That way valid stuff like "dealer to" does not become "dealerto"
            $str = preg_replace_callback('#('.substr($temp, 0, -3).')(\W)#is', array($this, '_compactExplodedWords'), $str);
        }

        /*
         * Remove disallowed Javascript in links or img tags
         * We used to do some version comparisons and use of stripos for PHP5, 
         * but it is dog slow compared to these simplified non-capturing 
         * preg_match(), especially if the pattern exists in the string
         */
        do
        {
            $original = $str;

            if (preg_match("/<a/i", $str))
            {
                $str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, '_jsLinkRemoval'), $str);
            }

            if (preg_match("/<img/i", $str))
            {
                $str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, '_jsImgRemoval'), $str);
            }

            if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str))
            {
                $str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $str);
            }
        }
        while($original != $str);

        unset($original);

        // Remove evil attributes such as style, onclick and xmlns
        $str = $this->_removeEvilAttributes($str, $is_image);

        /*
         * Sanitize naughty HTML elements
         *
         * If a tag containing any of the words in the list
         * below is found, the tag gets converted to entities.
         *
         * So this: <blink>
         * Becomes: &lt;blink&gt;
         */
        $naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
        $str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitizeNaughtyHtml'), $str);

        /*
         * Sanitize naughty scripting elements
         *
         * Similar to above, only instead of looking for
         * tags it looks for PHP and JavaScript commands
         * that are disallowed.  Rather than removing the
         * code, it simply converts the parenthesis to entities
         * rendering the code un-executable.
         *
         * For example:	eval('some code')
         * Becomes:		eval&#40;'some code'&#41;
         */
        $str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);


        // Final clean up
        // This adds a bit of extra precaution in case
        // something got through the above filters
        $str = $this->_doNeverAllowed($str);

        /*
         * Images are Handled in a Special Way
         * - Essentially, we want to know that after all of the character 
         * conversion is done whether any unwanted, likely XSS, code was found.  
         * If not, we return true, as the image is clean.
         * However, if the string post-conversion does not matched the 
         * string post-removal of XSS, then it fails, as there was unwanted XSS 
         * code found and removed/changed during processing.
         */

        if ($is_image === true)
        {
            return ($str == $converted_string) ? true: false;
        }

        \Ob\log\me('debug', "XSS Filtering completed");
        
        return $str;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Random Hash for protecting URLs
    *
    * @return	string
    */
    public function xssHash()
    {
        if ($this->_xss_hash == '')
        {
            if (phpversion() >= 4.2)
            {
                mt_srand();
            }
            else
            {
                mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
            }

            $this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
        }

        return $this->_xss_hash;
    }
    
    // --------------------------------------------------------------------

    /**
     * HTML Entities Decode
     *
     * This function is a replacement for html_entity_decode()
     *
     * In some versions of PHP the native function does not work
     * when UTF-8 is the specified character set, so this gives us
     * a work-around.  More info here:
     * http://bugs.php.net/bug.php?id=25670
     *
     * NOTE: html_entity_decode() has a bug in some PHP versions when UTF-8 is the
     * character set, and the PHP developers said they were not back porting the
     * fix to versions other than PHP 5.x.
     *
     * @param	string
     * @param	string
     * @return	string
     */
    public function entityDecode($str, $charset='UTF-8')
    {
        if (stristr($str, '&') === false) return $str;

        // The reason we are not using html_entity_decode() by itself is because
        // while it is not technically correct to leave out the semicolon
        // at the end of an entity most browsers will still interpret the entity
        // correctly.  html_entity_decode() does not convert entities without
        // semicolons, so we are left with our own little solution here. Bummer.

        if (function_exists('html_entity_decode') && (strtolower($charset) != 'utf-8'))
        {
            $str = html_entity_decode($str, ENT_COMPAT, $charset);
            $str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
            return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
        }

        // Numeric Entities
        $str = preg_replace('~&#x(0*[0-9a-f]{2,5});{0,1}~ei', 'chr(hexdec("\\1"))', $str);
        $str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);

        // Literal Entities - Slightly slow so we do another check
        if (stristr($str, '&') === false)
        {
            $str = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
        }

        return $str;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Filename Security
    *
    * @param	string
    * @return	string
    */
    public function sanitizeFilename($str, $relative_path = false)
    {
        $bad = array(
                                        "../",
                                        "<!--",
                                        "-->",
                                        "<",
                                        ">",
                                        "'",
                                        '"',
                                        '&',
                                        '$',
                                        '#',
                                        '{',
                                        '}',
                                        '[',
                                        ']',
                                        '=',
                                        ';',
                                        '?',
                                        "%20",
                                        "%22",
                                        "%3c",		// <
                                        "%253c",	// <
                                        "%3e",		// >
                                        "%0e",		// >
                                        "%28",		// (
                                        "%29",		// )
                                        "%2528",	// (
                                        "%26",		// &
                                        "%24",		// $
                                        "%3f",		// ?
                                        "%3b",		// ;
                                        "%3d"		// =
                                );

        if ( ! $relative_path)
        {
            $bad[] = './';
            $bad[] = '/';
        }

        $str = Ob\removeInvisibleCharacters($str, false);
        return stripslashes(str_replace($bad, '', $str));
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Compact Exploded Words
    *
    * Callback function for xssClean() to remove whitespace from
    * things like j a v a s c r i p t
    *
    * @access   public
    * @param    type
    * @return   type
    */
    public function _compactExplodedWords($matches)
    {
        return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
    }
    
    // --------------------------------------------------------------------
	
    /*
     * Remove Evil HTML Attributes (like evenhandlers and style)
     *
     * It removes the evil attribute and either:
     * 	- Everything up until a space
     *		For example, everything between the pipes:
     *		<a |style=document.write('hello');alert('world');| class=link>
     * 	- Everything inside the quotes 
     *		For example, everything between the pipes:
     *		<a |style="document.write('hello'); alert('world');"| class="link">
     *
     * @param string $str The string to check
     * @param boolean $is_image true if this is an image
     * @return string The string with the evil attributes removed
     */
    protected function _removeEvilAttributes($str, $is_image)
    {
        // All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
        $evil_attributes = array('on\w*', 'style', 'xmlns');

        if ($is_image === true)
        {
            /*
             * Adobe Photoshop puts XML metadata into JFIF images, 
             * including namespacing, so we have to allow this for images.
             */
            unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
        }

        do {
            $str = preg_replace(
                    "#<(/?[^><]+?)([^A-Za-z\-])(".implode('|', $evil_attributes).")(\s*=\s*)([\"][^>]*?[\"]|[\'][^>]*?[\']|[^>]*?)([\s><])([><]*)#i",
                    "<$1$6",
                    $str, -1, $count
            );
        } while ($count);

        return $str;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Sanitize Naughty HTML
    *
    * Callback function for xssClean() to remove naughty HTML elements
    *
    * @access   private
    * @param    array
    * @return   string
    */
    public function _sanitizeNaughtyHtml($matches)
    {
        // encode opening brace
        $str = '&lt;'.$matches[1].$matches[2].$matches[3];

        // encode captured opening or closing brace to prevent recursive vectors
        $str .= str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);

        return $str;
    }
    
    // --------------------------------------------------------------------

    /**
    * JS Link Removal
    *
    * Callback function for xssClean() to sanitize links
    * This limits the PCRE backtracks, making it more performance friendly
    * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
    * PHP 5.2+ on link-heavy strings
    *
    * @access    private
    * @param    array
    * @return    string
    */
    public function _jsLinkRemoval($match)
    {
        $attributes = $this->_filterAttributes(str_replace(array('<', '>'), '', $match[1]));
        return str_replace($match[1], preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
    }
    
    // --------------------------------------------------------------------

    /**
    * JS Image Removal
    *
    * Callback function for xssClean() to sanitize image tags
    * This limits the PCRE backtracks, making it more performance friendly
    * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
    * PHP 5.2+ on image tag heavy strings
    *
    * @access   private
    * @param    array
    * @return   string
    */
    public function _jsImgRemoval($match)
    {
        $attributes = $this->_filterAttributes(str_replace(array('<', '>'), '', $match[1]));
        return str_replace($match[1], preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
    }
    
    // --------------------------------------------------------------------

    /**
    * Attribute Conversion
    *
    * Used as a callback for XSS Clean
    *
    * @access   public
    * @param    array
    * @return   string
    */

    public function _convertAttribute($match)
    {
        return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Filter Attributes
    *
    * Filters tag attributes for consistency and safety
    *
    * @access   public
    * @param    string
    * @return   string
    */
    public function _filterAttributes($str)
    {
        $out = '';

        if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
        {
            foreach ($matches[0] as $match)
            {
                $out .= preg_replace("#/\*.*?\*/#s", '', $match);
            }
        }

        return $out;
    }
    
    // --------------------------------------------------------------------

    /**
     * HTML Entity Decode Callback
     *
     * Used as a callback for XSS Clean
     *
     * @param	array
     * @return	string
     */
    protected function _decodeEntity($match)
    {
        return $this->entityDecode($match[0], strtoupper(config('charset')));
    }

    // --------------------------------------------------------------------
    
    /**
    * Validate URL entities
    *
    * Called by xssClean()
    *
    * @param 	string	
    * @return 	string
    */
    public function _validateEntities($str)
    {
        /*
         * Protect GET variables in URLs
         */

         // 901119URL5918AMP18930PROTECT8198

        $str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->xssHash()."\\1=\\2", $str);

        /*
         * Validate standard character entities
         *
         * Add a semicolon if missing.  We do this to enable
         * the conversion of entities to ASCII later.
         *
         */
        $str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

        /*
         * Validate UTF16 two byte encoding (x00)
         *
         * Just as above, adds a semicolon if missing.
         *
         */
        $str = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);

        /*
         * Un-Protect GET variables in URLs
         */
        $str = str_replace($this->xssHash(), '&', $str);

        return $str;
    }
    
    // --------------------------------------------------------------------

    /**
     * Do Never Allowed
     *
     * A utility function for xssClean()
     *
     * @param 	string
     * @return 	string
     */
    protected function _doNeverAllowed($str)
    {
        foreach ($this->_never_allowed_str as $key => $val)
        {
            $str = str_replace($key, $val, $str);
        }

        foreach ($this->_never_allowed_regex as $key => $val)
        {
            $str = preg_replace("#".$key."#i", $val, $str);
        }

        return $str;
    }
    
    // ------------------------------------------------------------------------

    /**
    * Strip Image Tags
    *
    * @access	public
    * @param	string
    * @return	string
    */
    public function stripImageTags($str)
    {
        $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        $str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

        return $str;
    }
    // ------------------------------------------------------------------------

    /**
    * Convert PHP tags to entities
    *
    * @access	public
    * @param	string
    * @return	string
    */
    public function encodePhpTags($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }
    
    // --------------------------------------------------------------------

    /**
     * Set Cross Site Request Forgery Protection Cookie
     *
     * @return	string
     */
    protected function _csrfSetHash()
    {
        if ($this->_csrf_hash == '')
        {
            // If the cookie exists we will use it's value.  
            // We don't necessarily want to regenerate it with
            // each page load since a page could contain embedded 
            // sub-pages causing this feature to fail
            if (isset($_COOKIE[$this->_csrf_cookie_name]) && 
                    $_COOKIE[$this->_csrf_cookie_name] != '')
            {
                    return $this->_csrf_hash = $_COOKIE[$this->_csrf_cookie_name];
            }

            return $this->_csrf_hash = md5(uniqid(rand(), true));
        }

        return $this->_csrf_hash;
    }
    
}

// END Security Class

/* End of file Security.php */
/* Location: ./ob/security/releases/0.0.1/src/security.php */