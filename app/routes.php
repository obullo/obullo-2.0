<?php
/*
|--------------------------------------------------------------------------
| Routes 
|--------------------------------------------------------------------------
| Typically there is a one-to-one relationship between a URL string and its 
| corresponding ( folders / controller / method ).
|
*/
/**
 * Set your root domain without ".www" e.g."example.com"
 */
$router->setDomainRoot('framework');
$router->setDefaultPage('welcome');
$router->setSubfolderLevel(3);

// $router->get(
//     '{id}/{name}/{any}', 'welcome/index/$1/$2/$3',
//     function ($id, $name, $any) {
//         echo $id.'-'.$name.'-'.$any;
//     }
// )->where(array('id' => '[0-9]+', 'name' => '[a-z]+', 'any' => '.*'));

// $router->get('[0-9]+/.*', 'welcome/index/$1/$2/');

// $router->get(
//     '{id}/{name}/{any}', 'welcome/index/$1/$2/$3',
//     function ($id, $name, $any) use ($c) {
//         echo $id.'-'.$name.'-'.$any;
//     }
// )->where(['id' => '[0-9]+', 'name' => '[a-z]+', 'any' => '.+']);

// $router->middleware('Maintenance');

// $router->group(
//     function () {

//         // $this->get('[0-9]+/.*', 'welcome/index/$1/$2');

//         // $this->attach('.*'); // all urls
//         // $this->attach('welcome');
//         // $this->attach('welcome/test');
//     }
//     ['domain' => 'test.*\d.framework'],
// );

// $router->get(
//     'welcome/[0-9]+/[a-z]+', 'welcome/$1/$2',
//     function () use ($c) {
//         $container->get('view')->load('views::dummy');
//     }
// )->add('maintenance')->attach('welcome/.*'); 

/**
 * Generic users
 */
$router->domain('framework')
    ->group(
        'examples/',
        function () {

            // echo 'EXAMPLES';
                // print_r($this->group->getOptions());

            $this->group(
                'forms/', function () {
                    // echo 'FORMS';

                    // print_r($this->group->getOptions());

                }
            );
            // $this->match(['get', 'post'], 'widgets/tutorials/helloForm')->middleware('Csrf');

            // $this->get('(?:en|de|es|tr)', 'welcome');     // example.com/en
            // $this->get('(?:en|de|es|tr)(/)', 'welcome');  // example.com/en/
            // $this->get('(?:en|de|es|tr)/(.*)', '$1');     // example.com/en/examples/helloWorld
        }
    );// ->add(['Guest']); // ->match(['.*']);

/**
 * Authorized users
 */
$router->group(
    function () {

        // print_r($this->group->getOptions());

        // $this->attach('membership/restricted');

        // $this->get('tutorials/helloWorld.*', 'tutorials/helloLayout');
        // $this->attach('.*'); // all url
    }
)->add(['Guest']);


/**
 * Çalışmıyor.
 */
// $router->get('welcome/')->add('Guest')->attach('welcome/.*')

/**
 * Çalışmıyor.
 */
// ->attach(['((?!examples/helloWorld).)*$']); 

// ((?!examples/helloWorld).)*$  // url not contains "examples/hello_world"

// Example Api

// $router->match(array('get', 'post'), 'welcome', 'welcome/test');