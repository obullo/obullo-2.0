<?php

namespace Service;

use Obullo\Queue\Queue as OQueue;

/**
 * Queue Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/providers
 */
Class Queue implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c)
    {
        $c['queue'] = function () use ($c) {
            $queue = new OQueue($c, $c->load('config')['queue']);
            return $queue->getHandler();
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Queue.php */