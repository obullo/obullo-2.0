<?php

namespace Model;

/**
 * User model
 */
Class User extends \Model
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('service/provider/db');      
    }

    public function test()
    {
        echo 'ok !';
    }

}