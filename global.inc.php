<?php

    /**
     * sendEmail
     * 
     * @access public
     * @return string
     */
    function sendEmail()
    {
        $args = func_get_args();
        call_user_func_array(
            array(
                '\\Plugin\\Emailer',
                'send'
            ),
            $args
        );
    }
