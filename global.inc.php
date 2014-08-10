<?php

    /**
     * sendEmail
     * 
     * @access public
     * @return string|false
     */
    function sendEmail()
    {
        $args = func_get_args();
        return call_user_func_array(
            array(
                '\\Plugin\\Emailer',
                'send'
            ),
            $args
        );
    }
