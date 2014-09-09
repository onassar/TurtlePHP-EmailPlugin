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

    // Default email to send logging emails to (when no args are passed)
    $default = 'onassar+logging@gmail.com';

    // Mailgun API and sender settings
    $mailgun = array(
        'apiKey' => '***',
        'publicKey' => '***',
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
        '/onassar(\+)?(\+.+)?@gmail\.com/',// onassar@gmail.com, onassar+random@gmail.com, etc.
        '/oliver(\+)?(\+.+)?@anchor\.to/'// oliver@anchor.to, oliver+random@anchor.to, etc.
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
