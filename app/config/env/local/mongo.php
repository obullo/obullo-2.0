<?php

return array(

    'default' => array(
        'connection'   => 'default',
        'database' => 'db',
    ),

    'servers' => array(

        'default' => array(
            'host' => $c['env']['MONGO_HOST'],
            'username' => $c['env']['MONGO_USERNAME'],
            'password' => $c['env']['MONGO_PASSWORD'],
            'port' => '27017',
            'options' => array('connect' => true)
        )
        ,
        'default2' => array(
            'host' => $c['env']['MONGO_HOST'],
            'username' => $c['env']['MONGO_USERNAME'],
            'password' => $c['env']['MONGO_PASSWORD'],
            'port' => '27017',
            'options' => array('connect' => true)
        )

    ),
);

/* End of file mongo.php */
/* Location: .app/config/env/local/mongo.php */