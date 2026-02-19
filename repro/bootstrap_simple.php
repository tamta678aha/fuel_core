<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);
define('CRLF', chr(13).chr(10));
define('MBSTRING', function_exists('mb_get_info'));

$root = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR;
define('DOCROOT', $root);
define('APPPATH', $root);
define('COREPATH', $root);

require COREPATH . 'classes/autoloader.php';
class_alias('Fuel\\Core\\Autoloader', 'Autoloader');
\Autoloader::register();
\Autoloader::add_namespace('Fuel\\Core', COREPATH.'classes/');

require COREPATH . 'classes/fuel.php';
class_alias('Fuel\\Core\\Fuel', 'Fuel');
class_alias('Fuel\\Core\\FuelException', 'FuelException');

require COREPATH . 'classes/arr.php';
class_alias('Fuel\\Core\\Arr', 'Arr');

require COREPATH . 'classes/config.php';
class_alias('Fuel\\Core\\Config', 'Config');

require COREPATH . 'classes/str.php';
class_alias('Fuel\\Core\\Str', 'Str');

require COREPATH . 'classes/image.php';
require COREPATH . 'classes/image/driver.php';
class_alias('Fuel\\Core\\Image_Driver', 'Image_Driver');
require COREPATH . 'classes/image/imagemagick.php';
class_alias('Fuel\\Core\\Image_Imagemagick', 'Image_Imagemagick');

// Mock Config items
\Config::set('image', array());
