<?php
namespace Monolog;
class Logger {
    const DEBUG = 100;
    const INFO = 200;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;
    public function __construct($name) {}
    public function pushHandler($handler) {}
    public function log($level, $message, array $context = array()) {}
}

namespace Monolog\Handler;
class StreamHandler {
    public function __construct($path, $level = null) {}
    public function setFormatter($formatter) {}
}

namespace Monolog\Formatter;
class LineFormatter {
    public function __construct($format = null, $dateFormat = null, $allowInlineLineBreaks = false, $ignoreEmptyContextAndExtra = false) {}
}
