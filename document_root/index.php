<?php

/**
 *  Punkt startowy aplikacji
 *
 *
 */

//katalog nadrzędny, zawierający aplikację oraz wszystko wokół
defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR));

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'application');

defined('CONFIGS_PATH')
    || define('CONFIGS_PATH', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs');

defined('APPLICATION_INI')
    || define('APPLICATION_INI', CONFIGS_PATH . DIRECTORY_SEPARATOR . 'application.ini');

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

//ładowacz klas via composer
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$application = new Zend_Application(APPLICATION_ENV, APPLICATION_INI);

$application->bootstrap()
    ->run();
