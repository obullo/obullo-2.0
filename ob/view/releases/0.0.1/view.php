<?php
namespace Ob;

/**
 * View Class
 *
 * Display html files.
 *
 * @package       Obullo
 * @subpackage    view
 * @category      templates
 * @author        Obullo Team
 */

Class View {

    public $view_var          = array(); // String type view variables
    public $view_array        = array(); // Array type view variables
    public $view_data         = array(); // Mixed type view variables
    
    public static $instance;
    
    /**
    * Constructor
    *
    * Sets the View variables and runs the compilation routine
    *
    * @version   0.1
    * @access    public
    * @return    void
    */
    public function __construct()
    {
        log\me('debug', "View Class Initialized");
    }
    
    // ------------------------------------------------------------------------

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
    * Load view files.
    * 
    * @param string $path the view file path
    * @param string $filename view name
    * @param mixed  $data view data
    * @param booelan $string fetch the file as string or include file
    * @param booealan $return return false and don't show view file errors
    * @param string $func default view
    * @return void | string
    */
    public function load($path, $filename, $data = '', $string = FALSE, $return = '', $func = 'view')
    {
        $return = NULL; // @deprecated
        
        if(function_exists('getInstance') AND  is_object(getInstance()))
        {
            foreach(array_keys(get_object_vars(getInstance())) as $key) // This allows to using "$this" variable in all views files.
            {
                // Don't do lazy loading => isset() in here object variables always
                // must be ## NEW ##. 
                // e.g. $this->config->item('myitem')
                
                $this->{$key} = &getInstance()->$key;          
            }
        }
        
        //-----------------------------------
                
        $data = $this->_set_view_data($data); // Enables you to set data that is persistent in all views.

        //-----------------------------------

        if(is_array($data) AND count($data) > 0) 
        { 
            extract($data, EXTR_SKIP); 
        }

        ob_start();

        // If the PHP installation does not support short tags we'll
        // Please open it your php.ini file. ( short_tag = on ).

        include($path . $filename . EXT);
        
        log\me('debug', ucfirst($func).' file loaded: '.error\secure_path($path). $filename . EXT);

        if($string === TRUE)
        {
            $output = ob_get_contents();
            @ob_end_clean();

            return $output;
        }
        
        // Render possible Exceptional errors.
        $output = ob_get_contents();
        
        // Set Layout views inside to Output Class for caching functionality.
        Output::getInstance()->append_output($output);

        @ob_end_clean();

        return;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Enables you to set data that is persistent in all views
    *
    * @author CJ Lazell
    * @param array $data
    * @access public
    * @return void
    */
    public function _set_view_data($data = '')
    {
        if($data == '')
        {
            return;
        }
        
        if(is_object($data)) // object to array.
        {
            return get_object_vars($data);
        }
        
        if(is_array($data) AND count($data) > 0 AND count($this->view_data) > 0)
        {
            $this->view_data = array_merge((array)$this->view_data, (array)$data);
        }
        else 
        {
            $this->view_data = $data;
        }
        
        return $this->view_data;
    }
    
}

// END View Class

/* End of file View.php */
/* Location: ./ob/view/releases/0.0.1/view.php */