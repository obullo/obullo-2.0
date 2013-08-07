<?php 
namespace Ob\sess {

    /**
    * Procedural Session Implementation. 
    * Less coding and More Control.
    * 
    * @author      Obullo Team.
    * @version     0.1
    */
    function start($params = array())
    {                       
        \Ob\log\me('debug', "Session Cookie Driver Initialized"); 

        $sess   = Session::getInstance();
        
        foreach (array('sess_encrypt_cookie','sess_expiration', 'sess_die_cookie', 'sess_match_ip', 
        'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 
        'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
        {
            $sess->$key = (isset($params[$key])) ? $params[$key] : \Ob\config($key);
        }

        // _unserialize func. use strip_slashes() func.
        new \Ob\string\start();

        $sess->now = _get_time();

        // Set the expiration two years from now.
        if ($sess->sess_expiration == 0)
        {
            $sess->sess_expiration = (60 * 60 * 24 * 365 * 2);
        }

        // Set the cookie name
        $sess->sess_cookie_name = $sess->cookie_prefix . $sess->sess_cookie_name;
        
        // Cookie driver changes ...
        // -------------------------------------------------------------------- 
        
        // Run the Session routine. If a session doesn't exist we'll 
        // create a new one.  If it does, we'll update it.
        if ( ! read())
        {
            create();
        }
        else
        {    
            update();
        }

        // Delete 'old' flashdata (from last request)
        _flashdata_sweep();

        // Mark all new flashdata as old (data will be deleted before next request)
        _flashdata_mark();

        // Delete expired sessions if necessary
        _gc();

        log\me('debug', "Session routines successfully run"); 

        return TRUE;
    }
    
// --------------------------------------------------------------------

/**
* Fetch the current session data if it exists
*
* @access    public
* @return    array() sessions.
*/
    function read()
    {    
        $sess = Session::getInstance();
        
        // Fetch the cookie
        $session = i_cookie($sess->sess_cookie_name);

        // No cookie?  Goodbye cruel world!...
        if ($session === FALSE)
        {               
            log\me('debug', 'A session cookie was not found.');
            return FALSE;
        }
        
        // Decrypt the cookie data
        if ($sess->sess_encrypt_cookie == TRUE)  // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key     = config('encryption_key');
            $session = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($session), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        }
        else
        {    
            // encryption was not used, so we need to check the md5 hash
            $hash    = substr($session, strlen($session)-32); // get last 32 chars
            $session = substr($session, 0, strlen($session)-32);

            // Does the md5 hash match?  This is to prevent manipulation of session data in userspace
            if ($hash !==  md5($session . $sess->encryption_key))
            {
                log\me('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
                
                destroy();
                return FALSE;
            }
        }
        
        // Unserialize the session array
        $session = _unserialize($session);
        
        // Is the session data we unserialized an array with the correct format?
        if ( ! is_array($session) OR ! isset($session['session_id']) 
        OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) 
        OR ! isset($session['last_activity'])) 
        {               
            destroy();
            return FALSE;
        }
        
        // Is the session current?
        if (($session['last_activity'] + $sess->sess_expiration) < $sess->now)
        {
            destroy();
            return FALSE;
        }

        // Does the IP Match?
        if ($sess->sess_match_ip == TRUE AND $session['ip_address'] != i_ip_address())
        {
            destroy();
            return FALSE;
        }
        
        // Does the User Agent Match?
        if ($sess->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr(i_user_agent(), 0, 50)))
        {
            destroy();
            return FALSE;
        }
        
        // Cookie driver changes ...
        // -------------------------------------------------------------------- 
        
        // Session is valid!
        $sess->userdata = $session;
        unset($session);
        
        return TRUE;
    }
    
// --------------------------------------------------------------------

/**
* Write the session data
*
* @access    public
* @return    void
*/
    function write()
    {
        _set_cookie();
        
        return; 
    }
    
/**
* Create a new session
*
* @access    public
* @return    void
*/
    function create()
    {    
        $sess = Session::getInstance();
        
        $sessid = '';
        while (strlen($sessid) < 32)
        {
            $sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $sessid .= i_ip_address();

             
        $sess->userdata = array(
                            'session_id'     => md5(uniqid($sessid, TRUE)),
                            'ip_address'     => i_ip_address(),
                            'user_agent'     => substr(i_user_agent(), 0, 50),
                            'last_activity'  => $sess->now
                            );
        
        // Write the cookie
        // none abstract $this->_set_cookie();
        
        // --------------------------------------------------------------------  
        // Write the cookie
        _set_cookie(); 
    }
    
// --------------------------------------------------------------------

/**
* Update an existing session
*
* @access    public
* @return    void
*/
    function update()
    {
        $sess = Session::getInstance();
        
        // We only update the session every five minutes by default
        if (($sess->userdata['last_activity'] + $sess->sess_time_to_update) >= $sess->now)
        {
            return;
        }

        // Save the old session id so we know which record to 
        // update in the database if we need it
        // $old_sessid = $sess->userdata['session_id'];
        $new_sessid = '';
        
        while (strlen($new_sessid) < 32)
        {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $new_sessid .= i_ip_address();
        
        // Turn it into a hash
        $new_sessid = md5(uniqid($new_sessid, TRUE));
        
        // Update the session data in the session data array
        $sess->userdata['session_id']    = $new_sessid;
        $sess->userdata['last_activity'] = $sess->now;
        
        // _set_cookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie.
        $cookie_data = NULL;
        
        // Write the cookie
        // none abstract $this->_set_cookie($cookie_data);
        
        // --------------------------------------------------------------------  
        
        // Write the cookie
        _set_cookie($cookie_data);
    }

// --------------------------------------------------------------------

/**
* Destroy the current session
*
* @access    public
* @return    void
*/
    function destroy()
    {   
        $sess = Session::getInstance();
        
        // Kill the cookie
        setcookie(           
                    $sess->sess_cookie_name, 
                    addslashes(serialize(array())), 
                    ($sess->now - 31500000), 
                    $sess->cookie_path, 
                    $sess->cookie_domain, 
                    FALSE
        );
    }
    
// --------------------------------------------------------------------

/**
* Fetch a specific item from the session array
*
* @access   public
* @param    string
* @return   string
*/        
    function get($item, $prefix = '')
    {
        $sess = Session::getInstance();
        
        return ( ! isset($sess->userdata[$prefix.$item])) ? FALSE : $sess->userdata[$prefix.$item];
    }
    
// --------------------------------------------------------------------

/**
* Alias of get(); function.
*
* @access   public
* @param    string
* @return   string
*/
    function data($item, $prefix = '')
    {
        return get($prefix.$item);
    }
    
// --------------------------------------------------------------------

/**
* Fetch all session data
*
* @access    public
* @return    mixed
*/
    function alldata()
    {
        $sess = Session::getInstance();
        
        return ( ! isset($sess->userdata)) ? FALSE : $sess->userdata;
    }
    
// --------------------------------------------------------------------

/**
* Add or change data in the "userdata" array
*
* @access   public
* @param    mixed
* @param    string
* @return   void
*/       
    
    function set($newdata = array(), $newval = '', $prefix = '')
    {
        $sess = Session::getInstance();
        
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $sess->userdata[$prefix.$key] = $val;
            }
        }

        write();
    }

// --------------------------------------------------------------------

/**
* Delete a session variable from the "userdata" array
*
* @access    array
* @return    void
*/       
    function remove($newdata = array(), $prefix = '')
    {
        $sess = Session::getInstance();
        
        if (is_string($newdata))
        {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                unset($sess->userdata[$prefix.$key]);
            }
        }

        write();
    }

// ------------------------------------------------------------------------

/**
* Add or change flashdata, only available
* until the next request
*
* @access   public
* @param    mixed
* @param    string
* @return   void
*/
    function set_flash($newdata = array(), $newval = '')  // ( obullo changes ... )
    {
        $sess = Session::getInstance();
        
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }
        
        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $flashdata_key = $sess->flashdata_key.':new:'.$key;
                set($flashdata_key, $val);
            }
        }
    } 
// ------------------------------------------------------------------------

/**
* Keeps existing flashdata available to next request.
*
* @access   public
* @param    string
* @return   void
*/
    function keep_flash($key) // ( obullo changes ...)
    {
        $sess = Session::getInstance();
        
        // 'old' flashdata gets removed.  Here we mark all 
        // flashdata as 'new' to preserve it from _flashdata_sweep()
        // Note the function will return FALSE if the $key 
        // provided cannot be found
        $old_flashdata_key = $sess->flashdata_key.':old:'.$key;
        $value = get($old_flashdata_key);

        $new_flashdata_key = $sess->flashdata_key.':new:'.$key;
        set($new_flashdata_key, $value);
    }
    
// ------------------------------------------------------------------------

/**
* Fetch a specific flashdata item from the session array
*
* @access   public
* @param    string  $key you want to fetch
* @param    string  $prefix html open tag
* @param    string  $suffix html close tag
* 
* @version  0.1
* @version  0.2     added prefix and suffix parameters.
* 
* @return   string
*/
    function get_flash($key, $prefix = '', $suffix = '')  // obullo changes ...
    {
        $sess = Session::getInstance();
        
        $flashdata_key = $sess->flashdata_key.':old:'.$key;
        
        $value = get($flashdata_key);
        
        if($value == '')
        {
            $prefix = '';
            $suffix = '';
        }
        
        return $prefix.$value.$suffix;
    }

// ------------------------------------------------------------------------

/**
*  Alias of sess_get_flash. 
*/
    function sess_flash($key, $prefix = '', $suffix = '')
    {
        return get_flash($key, $prefix, $suffix);
    }

// ------------------------------------------------------------------------

/**
* Identifies flashdata as 'old' for removal
* when _flashdata_sweep() runs.
*
* @access    private
* @return    void
*/
    function _flashdata_mark()
    {
        $sess = Session::getInstance();
        
        $userdata = sess_alldata();
        foreach ($userdata as $name => $value)
        {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2)
            {
                $new_name = $sess->flashdata_key.':old:'.$parts[1];
                set($new_name, $value);
                remove($name);
            }
        }
    }
    
// ------------------------------------------------------------------------

/**
* Removes all flashdata marked as 'old'
*
* @access    private
* @return    void
*/
    
    function _flashdata_sweep()
    {              
        $userdata = sess_alldata();
        foreach ($userdata as $key => $value)
        {
            if (strpos($key, ':old:'))
            {
                remove($key);
            }
        }
    }

// --------------------------------------------------------------------

/**
* Get the "now" time
*
* @access    private
* @return    string
*/
    function _get_time()
    {   
        $sess = Session::getInstance();
        
        $time = time();
        if (strtolower($sess->time_reference) == 'gmt')
        {
            $now  = time();
            $time = mktime( gmdate("H", $now), 
            gmdate("i", $now), 
            gmdate("s", $now), 
            gmdate("m", $now), 
            gmdate("d", $now), 
            gmdate("Y", $now)
            );
        }
        return $time;
    }
    
// --------------------------------------------------------------------

/**
* Write the session cookie
*
* @access    public
* @return    void
*/
    function _set_cookie($cookie_data = NULL)
    {
        $sess = Session::getInstance();
        
        if (is_null($cookie_data))
        {
            $cookie_data = $sess->userdata;
        }

        // Serialize the userdata for the cookie
        $cookie_data = _serialize($cookie_data);
        
        if ($sess->sess_encrypt_cookie == TRUE) // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key         = config('encryption_key');
            $cookie_data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cookie_data, MCRYPT_MODE_CBC, md5(md5($key))));
        }
        else
        {
            // if encryption is not used, we provide an md5 hash to prevent userside tampering
            $cookie_data = $cookie_data . md5($cookie_data . $sess->encryption_key);
        }
        
        // ( Obullo Changes .. set cookie life time 0 )
        $expiration = (config('sess_die_cookie')) ? 0 : $sess->sess_expiration + time();

        // Set the cookie
        setcookie(
                    $sess->sess_cookie_name,
                    $cookie_data,
                    $expiration,
                    $sess->cookie_path,
                    $sess->cookie_domain,
                    0
                );
    }

// --------------------------------------------------------------------

/**
* Serialize an array
*
* This function first converts any slashes found in the array to a temporary
* marker, so when it gets unserialized the slashes will be preserved
*
* @access   private
* @param    array
* @return   string
*/    

    function _serialize($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                $data[$key] = str_replace('\\', '{{slash}}', $val);
            }
        }
        else
        {
            if (is_string($val))
            $data = str_replace('\\', '{{slash}}', $data);
        }
        
        return serialize($data);
    }

// --------------------------------------------------------------------

/**
* Unserialize
*
* This function unserializes a data string, then converts any
* temporary slash markers back to actual slashes
*
* @access    private
* @param    array
* @return    string
*/

    function _unserialize($data)
    {
        $data = @unserialize(strip_slashes($data));
        
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if(is_string($val))
                $data[$key] = str_replace('{{slash}}', '\\', $val);
            }
            
            return $data;
        }
        
        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }

// --------------------------------------------------------------------

/**
* Garbage collection
*
* This deletes expired session rows from database
* if the probability percentage is met
*
* @access    public
* @return    void
*/
    function _gc()
    {
        return;
    }
    
}

/* End of file session_cookie.php */
/* Location: ./ob/session/releases/0.0.1/src/session_cookie.php */