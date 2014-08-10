<?php

    // namespace
    namespace Plugin;

    // dependency check
    if (class_exists('\\Plugin\\Config') === false) {
        throw new \Exception(
            '*Config* class required. Please see ' .
            'https://github.com/onassar/TurtlePHP-ConfigPlugin'
        );
    }

    // dependency check
    if (class_exists('\\Postmark\\Mail') === false) {
        throw new \Exception(
            '*Mail* class required. Please see ' .
            'https://github.com/Znarkus/postmark-php'
        );
    }

    // dependency check
    if (class_exists('\\PostmarkEmail') === false) {
        throw new \Exception(
            '*PostmarkEmail* class required. Please see ' .
            'https://github.com/onassar/PHP-Email'
        );
    }

    // dependency check
    if (class_exists('\\Mailgun\\Mailgun') === false) {
        throw new \Exception(
            '*Mailgun\\Mailgun* class required. Please see ' .
            'https://github.com/mailgun/mailgun-php'
        );
    }

    // dependency check
    if (class_exists('\\MailgunEmail') === false) {
        throw new \Exception(
            '*MailgunEmail* class required. Please see ' .
            'https://github.com/onassar/PHP-Email'
        );
    }

    /**
     * Emailer
     * 
     * Emailer plugin for TurtlePHP
     * 
     * @author   Oliver Nassar <onassar@gmail.com>
     * @abstract
     */
    abstract class Emailer
    {
        /**
         * _configPath
         *
         * @var    string
         * @access protected
         * @static
         */
        protected static $_configPath = 'config.default.inc.php';

        /**
         * _config
         *
         * @var    array
         * @access protected
         * @static
         */
        protected static $_config;

        /**
         * _initiated
         *
         * @var    boolean
         * @access protected
         * @static
         */
        protected static $_initiated = false;

        /**
         * _resources
         *
         * @var    array
         * @access protected
         * @static
         */
        protected static $_resources = array(
            'mailgun' => null,
            'postmark' => null
        );

        /**
         * _sendThroughMailgun
         * 
         * @access protected
         * @param  string $recipient (default: LOGGING)
         * @param  string $subject (default: '(logging)')
         * @param  string $body (default: '(logging)')
         * @param  string $tag (default: 'logging')
         * @param  boolean $sendAsHtml (default: true)
         * @param  boolean|array $from (default: false)
         * @return string|false messageId if sent; false if exception or not sent at
         *         all
         */
        protected static function _sendThroughMailgun(
            $recipient = LOGGING,
            $subject = '(logging)',
            $body = '(logging)',
            $tag = 'logging',
            $sendAsHtml = true,
            $from = false
        ) {
            // Resource loading
            if (is_null(self::$_resources['mailgun'])) {
                self::$_resources['mailgun'] = (new \MailgunEmail(
                    self::$_config['mailgun']['apiKey'],
                    self::$_config['mailgun']['from']
                ));
            }

            // Send
            $response = self::$_resources['mailgun']->send(
                $recipient,
                $subject,
                $body,
                $tag,
                $sendAsHtml,
                $from
            );

            // Failed
            if (is_object($response) && get_class($response) === 'Exception') {
                error_log($response->getMessage());
                return false;
            }

            // Message Id response
            return $response;
        }

        /**
         * _sendThroughPostmark
         * 
         * @access protected
         * @param  string $recipient (default: LOGGING)
         * @param  string $subject (default: '(logging)')
         * @param  string $body (default: '(logging)')
         * @param  string $tag (default: 'logging')
         * @param  boolean $sendAsHtml (default: true)
         * @param  boolean|array $from (default: false)
         * @return string|false messageId if sent; false if exception or not sent at
         *         all
         */
        protected static function _sendThroughPostmark(
            $recipient = LOGGING,
            $subject = '(logging)',
            $body = '(logging)',
            $tag = 'logging',
            $sendAsHtml = true,
            $from = false
        ) {
            // Resource loading
            if (defined('POSTMARKAPP_API_KEY') === false) {
                $postmarkConfig = self::$_config['postmark'];
                define('POSTMARKAPP_API_KEY', $postmarkConfig['key']);
                define(
                    'POSTMARKAPP_MAIL_FROM_ADDRESS',
                    $postmarkConfig['from']['email']
                );
                define(
                    'POSTMARKAPP_MAIL_FROM_NAME',
                    $postmarkConfig['from']['name']
                );
            }

            // Emailing reference
            if (is_null(self::$_resources['postmark'])) {
                self::$_resources['postmark'] = (new \PostmarkEmail());
            }

            // Send
            $response = self::$_resources['postmark']->send(
                $recipient,
                $subject,
                $body,
                $tag,
                $sendAsHtml,
                $from
            );

            // Failed
            if (is_object($response) && get_class($response) === 'Exception') {
                error_log($response->getMessage());
                return false;
            }

            // Message Id response
            return $response;
        }

        /**
         * init
         * 
         * @access public
         * @static
         * @return void
         */
        public static function init()
        {
            if (is_null(self::$_initiated) === false) {
                self::$_initiated = true;
                require_once self::$_configPath;
                $config = \Plugin\Config::retrieve();
                self::$_config = $config['TurtlePHP-EmailerPlugin'];
                DEFINE(__NAMESPACE__ . '\\LOGGING', self::$_config['default']);
            }
        }

        /**
         * _isWhitelistEmail
         * 
         * Performs straight comparison as well as regex match to see whether
         * email is in the plugin's whitelist.
         * 
         * @access protected
         * @return boolean
         */
        protected static function _isWhitelistEmail($email)
        {
            $whitelist = self::$_config['whitelist'];
            return in_array($email, $whitelist);
        }

        /**
         * send
         * 
         * @access public
         * @return string|false Id of the message that was sent (from the
         *         sending service), or false if it couldn't be sent
         */
        public static function send()
        {
            $args = func_get_args();
            if (
                self::$_config['send'] === true
                || !isset($args[0])// no args sent = logging email
                || self::_isWhitelistEmail($args[0])
            ) {
                if (self::$_config['sender'] === 'mailgun') {
                    return call_user_func_array(
                        array('self', '_sendThroughMailgun'),
                        $args
                    );
                } else if (self::$_config['sender'] === 'postmark') {
                    return call_user_func_array(
                        array('self', '_sendThroughPostmark'),
                        $args
                    );
                }
            }
            return false;
        }

        /**
         * setConfigPath
         * 
         * @access public
         * @param  string $path
         * @return void
         */
        public static function setConfigPath($path)
        {
            self::$_configPath = $path;
        }
    }

    // Config
    $info = pathinfo(__DIR__);
    $parent = ($info['dirname']) . '/' . ($info['basename']);
    $configPath = ($parent) . '/config.inc.php';
    if (is_file($configPath)) {
        Emailer::setConfigPath($configPath);
    }

    // Load global functions
    require_once 'global.inc.php';
