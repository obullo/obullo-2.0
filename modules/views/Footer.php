<?php

namespace Views;

class Footer extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['view'];
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo $this->view->get(
            'footer',
            [
                'footer' => '<pre>--------------- EXAMPLE FOOTER LAYER ---------------</pre>'
            ]
        );
    }
}


/* End of file header.php */
/* Location: .controllers/views/header.php */