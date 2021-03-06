<?php

namespace Tests\App;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;

class Middleware extends TestController
{
    /**
     * Has middleware
     * 
     * @return void
     */
    public function has()
    {
        $this->middleware->add('ParsedBody');
        $this->assertTrue($this->middleware->has('ParsedBody'), "I add ParsedBody middleware and i expect that the value is true.");
    }

    /**
     * Add middleware
     * 
     * @return void
     */
    public function add()
    {
        $this->middleware->add('TrustedIp');
        $this->assertTrue($this->middleware->exists('TrustedIp'), "I add TrustedIp middleware and i expect that the value is true.");
    }

    /**
     * Get middleware
     * 
     * @return void
     */
    public function get()
    {
        $this->middleware->add('TrustedIp');
        $router = $this->middleware->get('TrustedIp');

        $this->assertInstanceOf('Http\Middlewares\TrustedIp', $router, "I add TrustedIp middleware and i expect it is an instance of Http\Middlewares\TrustedIp object.");
    }

    /**
     * Remove middleware
     * 
     * @return void
     */
    public function remove()
    {
        $this->middleware->add('TrustedIp');
        $this->middleware->remove('TrustedIp');

        $this->assertFalse($this->middleware->exists('TrustedIp'), "I add TrustedIp middleware then i remove and i expect that the value is false.");
    }

    /**
     * Returns to middleware queue
     * 
     * @return array
     */
    public function getQueue()
    {
        $this->middleware->add('TrustedIp');
        $names   = array();
        $objects = array();
        foreach (array_values($this->middleware->getQueue()) as $object) {
            $objects[] = $object;
            $names[]   = get_class($object);
        };
        $this->assertInstanceOf(
            "Http\Middlewares\TrustedIp",
            end($objects),
            "I add TrustedIp middleware to end of the queue then i expect it is an instance of Http\Middlewares\TrustedIp object."
        );
        TestOutput::varDump($names);
    }

    /**
     * Returns to all middleware names
     * 
     * @return array
     */
    public function getNames()
    {
        $this->middleware->add('TrustedIp');
        $names = $this->middleware->getNames();

        $this->assertArrayContains('TrustedIp', $names, "I expect middleware names contain TrustedIp key.");
        TestOutput::varDump($names);
    }

    /**
     * Get regsitered path of middleware
     * 
     * @return string
     */
    public function getPath()
    {
        $path = $this->middleware->getPath('TrustedIp');

        $this->assertEqual('Http\Middlewares\TrustedIp', $path, "I expect TrustedIp middleware path equal to Http\Middlewares\TrustedIp string.");
        TestOutput::varDump($path);
    }

}