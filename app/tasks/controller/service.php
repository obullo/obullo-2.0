<?php

defined('STDIN') or die('Access Denied');

use Obullo\Cli\Controller\Service as ServiceController;

/**
 * Service controller
 */
Class Service extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $service = new ServiceController($this->c, func_get_args());
        $service->run();
    }
}

/* End of file service.php */
/* Location: .app/tasks/controller/service.php */