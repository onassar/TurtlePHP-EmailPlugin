TurtlePHP-EmailerPlugin
======================

``` php
require_once APP . '/plugins/TurtlePHP-ConfigPlugin/Config.class.php';
require_once APP . '/vendors/postmark-php/src/Postmark/Mail.php';
require_once APP . '/vendors/PHP-Email/PostmarkEmail.class.php';
require_once APP . '/vendors/mailgun/autoload.php';
require_once APP . '/vendors/PHP-Email/MailgunEmail.class.php';
require_once APP . '/plugins/TurtlePHP-EmailerPlugin/Emailer.class.php';
\Plugin\Emailer::init();
```

``` php
...
\Plugin\Emailer::setConfigPath('/path/to/config/file.inc.php');
\Plugin\Emailer::init();
```
