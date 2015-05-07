<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Service\ServiceInterface;

class Db implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(Container $c)
    {
        $c['db'] = function () use ($c) {
            return $c['app']->provider('database')->get(['connection' => 'default']);
        };
    }
}

// END Db service

/* End of file Db.php */
/* Location: .app/classes/Service/Db.php */