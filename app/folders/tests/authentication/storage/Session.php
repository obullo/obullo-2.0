<?php

namespace Tests\Authentication\Storage;

use Obullo\Tests\TestPreferences;
use Obullo\Authentication\Storage\AbstractTestStorage;
use Obullo\Authentication\Storage\Session as StorageSession;

class Session extends AbstractTestStorage
{
    protected $storage;

    /**
     * Constructor
     * 
     * @param object $container container
     */
    public function __construct($container)
    {
        $container->get('user');
        $container->get('session')->destroy();

        $this->storage = new StorageSession(
            $container,
            $container->get('request'),
            $container->get('session'),
            $container->get('user.params')
        );
        $this->storage->setIdentifier('user@example.com');

        TestPreferences::ignoreMethod(
            [
                'query',
                'createTemporary',
                'makeTemporary',
                'makePermanent',
                'getUserSessions',
                'getMemoryBlockLifetime', // This method not exists in memcached storage.
            ]
        ); 
    }
}
