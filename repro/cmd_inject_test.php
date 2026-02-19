<?php
require 'repro/bootstrap.php';

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
    // We need to pass config to constructor.
    // Image_Driver constructor calls \Config::get('image').
    // We can pass override config.
    $config = array(
        'driver' => 'imagemagick',
        'imagemagick_dir' => '/usr/bin/',
        'temp_dir' => '/tmp/',
        'temp_append' => 'img',
        'debug' => true,
    );

    $img = new Test_Image_Imagemagick($config);

    // The vulnerability is in load() method if it uses user input for filename
    // and that filename is used in shell command without proper escaping.
    // load() uses $image_fullpath.

    $payload = "image.png' || echo 'VULNERABLE: CMD INJECTED' || '";
    // We need a file that exists for realpath() in Image_Driver::load() to succeed.
    // Image_Driver::load() checks is_file($filename).
    // So we need to create a file with the payload name?
    // That is usually impossible or hard.

    // However, if we look at Image_Imagemagick::load:
    // $this->exec('convert', "-auto-orient '".$image_fullpath."'[0] '".$this->image_temp."'");

    // If $image_fullpath contains single quote, it breaks out of '...'.
    // But Image_Driver::load resolves realpath.
    // If the file exists, realpath returns absolute path.
    // If we can control the filename of an uploaded file, or a file on disk.
    // e.g. "image.png'injection.png"

    // If we assume we can pass a file path that exists.
    // Let's create a file with a malicious name if the filesystem allows it.
    // In Linux, ' is allowed in filenames.

    $malicious_filename = "repro/vuln'test.png";
    touch($malicious_filename);

    echo "Loading file: $malicious_filename\n";
    $img->load($malicious_filename);

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
