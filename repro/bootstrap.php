<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('CRLF') or define('CRLF', chr(13).chr(10));
define('MBSTRING', function_exists('mb_get_info'));

$root = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR;
define('DOCROOT', $root);
define('APPPATH', $root);
define('PKGPATH', $root . 'packages' . DIRECTORY_SEPARATOR);
define('VENDORPATH', $root . 'vendor' . DIRECTORY_SEPARATOR);
define('COREPATH', $root);

require VENDORPATH . 'autoload.php';

// Pre-load Autoloader
require COREPATH . 'classes/autoloader.php';
class_alias('Fuel\\Core\\Autoloader', 'Autoloader');
\Autoloader::register();

// Load bootstrap.php to setup class map
require COREPATH . 'bootstrap.php';

// Trigger loading and aliasing of core classes using the map
class_exists('Fuel');
class_exists('Config');
class_exists('Event');
class_exists('Errorhandler');
class_exists('Input');
class_exists('Arr');
class_exists('Str');
class_exists('Security');
class_exists('Finder');
class_exists('View');
class_exists('Lang');
class_exists('Uri');
class_exists('Debug');
class_exists('Cli');

// Manually fix Config drivers aliases which seem to fail autoloading in this env
require_once COREPATH . 'classes/config/interface.php';
require_once COREPATH . 'classes/config/file.php';
class_alias('Fuel\\Core\\Config_File', 'Config_File');
require_once COREPATH . 'classes/config/php.php';
class_alias('Fuel\\Core\\Config_Php', 'Config_Php');

// Init core classes
\Fuel\Core\Event::_init();
\Fuel\Core\Finder::_init();
\Fuel\Core\Lang::_init();

// Set log path to /tmp/ BEFORE Log is loaded/initialized
\Config::set('log_path', '/tmp/');
\Config::set('log_threshold', \Fuel::L_ALL);

// Set Security config
\Config::set('security.output_filter', array('Security::htmlentities'));
\Config::set('security.csrf_autoload', false);
\Config::set('security.csrf_token_key', 'fuel_csrf_token');

// Mock Config if needed
if (!file_exists(APPPATH.'config/config.php')) {
    if (!is_dir(APPPATH.'config')) {
        mkdir(APPPATH.'config', 0777, true);
    }
    file_put_contents(APPPATH.'config/config.php', '<?php return array();');
}

try {
    \Fuel::init(array());
} catch (\Exception $e) {
    // Suppress config load errors
}
