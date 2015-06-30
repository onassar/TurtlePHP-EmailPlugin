<?php

    /**
     * Namespace
     * 
     */
    namespace Plugin\Emailer;

    /**
     * getConfig
     * 
     * @access public
     * @return mixed
     */
    function getConfig()
    {
        $args = func_get_args();
        array_unshift($args, 'TurtlePHP-EmailerPlugin');
        return call_user_func_array('\getConfig', $args);
    }
