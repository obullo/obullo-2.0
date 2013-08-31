<?php
namespace url {
    
    // ------------------------------------------------------------------------
    
    /**
    * Url Helper
    *
    * @package       packages
    * @subpackage    url
    * @category      url
    * @link
    * 
    */
    
    Class start
    { 
        function __construct()
        {
            \log\me('debug', 'Url Helper Initialized.');
        }
    }
    
    /**
    * Base URL
    *
    * Returns the "base_url" item from your config file
    *
    * @access    public
    * @return    string
    */
    function base($uri = '')
    {
        $config = '\\'.getComponent('config');
        return $config::getInstance()->baseUrl($uri);
    }

    // ------------------------------------------------------------------------

    /**
    * Asset folder URL
    *
    * Returns the "assets" using configuration item
    *
    * @access    public
    * @param     string url
    * @abstract  bool $no_slash  no trailing slash
    * @return    string
    */
    function assets($uri = '', $no_ext_uri_slash = false, $no_folder = false)
    {
        $config = '\\'.getComponent('config');
        return $config::getInstance()->assetUrl($uri, $no_folder, $no_ext_uri_slash);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Site URL
    *
    * Create a local URL based on your basepath. Segments can be passed via the
    * first parameter either as a string or an array.
    *
    * @access    public
    * @param     string url
    * @param     bool  $suffix switch off suffix by manually if its true in config.php
    * @return    string
    */
    function site($uri = '', $suffix = true)
    {
        $config = '\\'.getComponent('config');
        return $config::getInstance()->siteUrl($uri, $suffix);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Get current url
    *
    * @access   public
    * @return   string
    */
    function current()
    {
        $config = '\\'.getComponent('config');
        $uri    = '\\'.getComponent('uri');
        return $config::getInstance()->siteUrl($uri::getInstance()->uriString());
    }

    // ------------------------------------------------------------------------

    /**
    * Get current module name
    *
    * @access   public
    * @param    string uri
    * @return   string
    */
    function module($uri = '')
    {
        $module = getInstance()->router->fetchDirectory();
        
        if($uri == '')
        {
            return $module; 
        }

        return $module .'/'. ltrim($uri, '/');
    }

    // ------------------------------------------------------------------------

    /**
    * Anchor Link
    *
    * Creates an anchor based on the local URL.
    *
    * @access    public
    * @param     string    the URL
    * @param     string    the link title
    * @param     mixed     any attributes
    * @param     bool      switch off suffix by manually
    * @version   0.1
    * @version   0.2       Sharp character url support
    * @version   0.3       Added $suffix parameter
    * @return    string
    */
    function anchor($uri = '', $title = '', $attributes = '', $suffix = true)
    {
        $ssl = false;  // ssl support
        if(strpos($uri, 'https://') === 0)
        {
            if(config('ssl')) // Global ssl config.
            {
                $ssl = true;
            }
            
            $uri = str_replace('https://',  '',  $uri);
        }

        $title = (string) $title;
        $sharp = false;

        // ' # ' sharp support for anchors. ( Obullo changes )..
        if(strpos($uri, '#') > 0)
        {
            $sharp_uri = explode('#', $uri);
            $uri       = $sharp_uri[0];
            $sharp     = true;
        }

        if ( ! is_array($uri))
        {
            $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? getInstance()->config->siteUrl($uri, $suffix) : $uri;
        }
        else
        {
            $site_url = getInstance()->config->siteUrl($uri, $suffix);
        }

        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes != '')
        {
            $attributes = _parseAttributes($attributes);
        }

        if($sharp == true AND isset($sharp_uri[1]))
        {
            $site_url = $site_url.'#'.$sharp_uri[1];  // Obullo changes..
        }

        # if ssl used do not use https:// for standart anchors.
        # if your HTTP server NGINX add below the line to your fastcgi_params file.
        # fastcgi_param  HTTPS		  $ssl_protocol;
        # then $_SERVER['HTTPS'] variable will be available for PHP (fastcgi).

        if(isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] != '' AND $_SERVER['HTTPS'] != 'off')
        {
            if($ssl == false)
            {
                $site_url = rtrim(config('domain_root'), '/') . $site_url;
            }
        }

        if($ssl)
        {
            $site_url = rtrim(config('domain_root'), '/') . $site_url;
            $site_url = str_replace('http://',  'https://',  $site_url);
        }

        return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
    }
    
    // ------------------------------------------------------------------------

    /**
    * Anchor Link - Pop-up version
    *
    * Creates an anchor based on the local URL. The link
    * opens a new window based on the attributes specified.
    *
    * @access	public
    * @param	string	the URL
    * @param	string	the link title
    * @param	mixed	any attributes
    * @param    bool    switch off suffix by manually
    * @version  0.1     added suffix parameters
    * @return	string
    */
    function anchorPopup($uri = '', $title = '', $attributes = false, $suffix = true)
    {
        $ssl = false;  // ssl support
        if(strpos($uri, 'https://') === 0)
        {
            if(config('ssl')) // Global ssl config.
            {
                $ssl = true;
            }
            
            $uri = str_replace('https://',  '',  $uri);
        }

        $title = (string) $title;

        $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? getInstance()->uri->siteUrl($uri, $suffix) : $uri;

        # if ssl used do not use https:// for standart anchors.
        # if your HTTP server NGINX add below the line to your fastcgi_params file.
        # fastcgi_param  HTTPS		  $ssl_protocol;
        # then $_SERVER['HTTPS'] variable will be available for PHP (fastcgi).

        if(isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] != '' AND $_SERVER['HTTPS'] != 'off')
        {
            if($ssl == false)
            {
                $site_url = rtrim(config('domain_root'), '/') . $site_url;
            }
        }

        if($ssl)
        {
            $site_url = rtrim(config('domain_root'), '/') . $site_url;
            $site_url = str_replace('http://',  'https://',  $site_url);
        }

        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes === false)
        {
            return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank');\">".$title."</a>";
        }

        if ( ! is_array($attributes))
        {
            $attributes = array();
        }

        foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0', ) as $key => $val)
        {
            $atts[$key] = ( ! isset($attributes[$key])) ? $val : $attributes[$key];
            unset($attributes[$key]);
        }

        if ($attributes != '')
        {
            $attributes = _parseAttributes($attributes);
        }

        return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank', '"._parseAttributes($atts, true)."');\"$attributes>".$title."</a>";
    }
    
    // ------------------------------------------------------------------------

    /**
    * Prep URL
    *
    * Simply adds the http:// part if missing
    *
    * @access	public
    * @param	string	the URL
    * @return	string
    */
    function prep($str = '')
    {
        if ($str == 'http://' OR $str == '')
        {
            return '';
        }

        if ( ! parse_url($str, PHP_URL_SCHEME))
        {
            $str = 'http://'.$str;
        }

        return $str;
    }
    
    // ------------------------------------------------------------------------

    /**
    * Create URL Title
    *
    * Takes a "title" string as input and creates a
    * human-friendly URL string with either a dash
    * or an underscore as the word separator.
    *
    * @access	public
    * @param	string	the string
    * @param	string	the separator: dash, or underscore
    * @return	string
    */
    function title($str, $separator = 'dash', $lowercase = false)
    {
        if ($separator == 'dash')
        {
            $search		= '_';
            $replace	= '-';
        }
        else
        {
            $search		= '-';
            $replace	= '_';
        }

        $trans = array(
                        '&\#\d+?;' => '',
                        '&\S+?;'   => '',
                        '\s+'	   => $replace,
                        '[^a-z0-9\-\._]'    => '',
                        $replace.'+'        => $replace,
                        $replace.'$'        => $replace,
                        '^'.$replace        => $replace,
                        '\.+$'              => ''
                      );

        $str = strip_tags($str);

        foreach ($trans as $key => $val)
        {
            $str = preg_replace("#".$key."#i", $val, $str);
        }

        if ($lowercase === true)
        {
            $str = strtolower($str);
        }

        return trim(stripslashes($str));
    }

    // ------------------------------------------------------------------------

    /**
    * Header Redirect
    *
    * Header redirect in two flavors
    * For very fine grained control over headers, you could use the Output
    * Library's setHeader() function.
    *
    * @access   public
    * @param    string    the URL
    * @param    string    the method: location or refresh[param]
    * @version  0.1       added sharp support and suffix parameter
    * @return   string
    */
    function redirect($uri = '', $method = 'location', $http_response_code = 302, $suffix = true)
    {
        if ( ! preg_match('#^https?://#i', $uri))
        {
            $sharp = false;

            // ' # ' sharp support for urls. ( Obullo changes )..
            if(strpos($uri, '#') > 0)
            {
                $sharp_uri = explode('#', $uri);
                $uri       = $sharp_uri[0];
                $sharp     = true;
            }

            $uri = getInstance()->config->siteUrl($uri, $suffix);

            if($sharp == true AND isset($sharp_uri[1]))
            {
                $uri = $uri.'#'.$sharp_uri[1];  // Obullo changes..
            }
        }

        if(strpos($method, '['))    // Obullo changes.. refresh parameter ..
        {
            $index = explode('[', $method);
            $param = str_replace(']', '', $index[1]);

            header("Refresh:$param;url=".$uri);
            return;
        }
        
        switch($method)
        {
            case 'refresh'    : header("Refresh:0;url=".$uri);
                break;
            default           : header("Location: ".$uri, true, $http_response_code);
                break;
        }
        exit;
    }

    // ------------------------------------------------------------------------

    /**
    * Parse out the attributes
    *
    * Some of the functions use this
    *
    * @access	private
    * @param	array
    * @param	bool
    * @return	string
    */
    function _parseAttributes($attributes, $javascript = false)
    {
        if (is_string($attributes))
        {
            return ($attributes != '') ? ' '.$attributes : '';
        }

        $att = '';
        foreach ($attributes as $key => $val)
        {
            if ($javascript == true)
            {
                $att .= $key . '=' . $val . ',';
            }
            else
            {
                $att .= ' ' . $key . '="' . $val . '"';
            }
        }

        if ($javascript == true AND $att != '')
        {
            $att = substr($att, 0, -1);
        }

        return $att;
    }

}

/* End of file url.php */
/* Location: ./packages/url/releases/0.0.1/url.php */