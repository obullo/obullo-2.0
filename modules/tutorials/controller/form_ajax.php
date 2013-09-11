<?php

Class Form_Ajax extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new form\start();
    }         

    function index()
    {        
        view('form.ajax');
    }
    
    function post()
    {   
        new form_Json\start();
        
        $user = new Models\User();
        $user->user_password = i\post('user_password');
        $user->user_email    = i\post('user_email');
  
        if($user->save())
        {
            echo form_Json\success($user);
            return;
        } 
        else
        {
            echo form_Json\error($user);
            return;
        }

        view('form.ajax');
    }
}

/* End of file form_ajax.php */
/* Location: .modules/tutorials/controller/form_ajax.php */