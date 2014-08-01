<?php

    /**
     * Namespace
     * 
     */
    namespace Plugin\Emailer;

    /**
     * Data
     * 
     */
    $data = array(
        'cacheNamespace' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'username' => 'app',
        'password' => 'apples',
        'name' => 'mysql',
        'encoding' => 'utf8',
        'timezone' => 'ETC\/UTC'
    );

    /**
     * Config storage
     * 
     */

    // Store
    \Plugin\Config::add(
        'TurtlePHP-EmailerPlugin',
        $data
    );
