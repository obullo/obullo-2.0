<?php

return array(
    
    'params' => [

        'cache.key' => 'Auth',
        'db.adapter'=> '\Obullo\Authentication\Adapter\Database',
        'db.model'  => '\Obullo\Authentication\Model\Pdo\User',
        'db.provider' => [
            'name' => 'database',
            'params' => [
                'connection' => 'default'
            ]
        ],
        'db.tablename' => 'users',
        'db.id' => 'id',
        'db.identifier' => 'username',
        'db.password' => 'password',
        'db.rememberToken' => 'remember_token',
        'db.select' => [
            'date',
        ],
        'cache' => [            
            'storage' => '\Obullo\Authentication\Storage\Redis',
            'provider' => [
                'params' => [
                    'driver' => 'redis',
                    'connection' => 'default'
                ]
            ],
            'block' => [
                'permanent' => [
                    'lifetime' => 3600,
                ],
                'temporary'  => [
                    'lifetime' => 300
                ]
            ]
        ],
        'login' => [
            'rememberMe'  => [
                'cookie' => [
                    'name' => '__rm',
                    'domain' => null,
                    'path' => '/',
                    'secure' => false,
                    'httpOnly' => true,
                    'prefix' => '',
                    'expire' => 6 * 30 * 24 * 3600,
                ]
            ],
        ],
        'security' => [
            'passwordNeedsRehash' => [
                'cost' => 6
            ],
        ],
        'session' => [
            'regenerateSessionId' => true,
        ],
        'middleware' => [
            'unique.session' => false
        ]
    ]
);