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

    // Default email to send logging emails to
    $default = 'onassar+logging@gmail.com';

    // Mailgun API and sender settings
    $mailgun = array(
        'apiKey' => '***',
        'from' => array(
            'domain' => 'domain.com',
            'email' => 'email@address.com',
            'name' => 'name'
        )
    );

    // Postmark API and sender settings
    $postmark = array(
        'key' => '***',
        'from' => array(
            'email' => 'email@address.com',
            'name' => 'name'
        )
    );

    // Whether or not to actually send the email (don't use locally)
    $send = false;

    // Which service to use when sending mail
    $sender = 'mailgun';

    // Email addresses, which regarldess of <send> boolean, will receive mail
    // Can be a regular expression
    $whitelist = array(
        'onassar@gmail.com'
    );

    /**
     * Config storage
     * 
     */

    // Store
    \Plugin\Config::add(
        'TurtlePHP-EmailerPlugin',
        array(
            'default' => $default,
            'mailgun' => $mailgun,
            'postmark' => $postmark,
            'send' => $send,
            'sender' => $sender,
            'whitelist' => $whitelist
        )
    );
