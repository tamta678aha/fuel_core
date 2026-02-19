# Vulnerability Findings in FuelPHP Core

## 1. Local File Inclusion (LFI) in View Class

**Vulnerability Type:** Local File Inclusion
**Affected Component:** `Fuel\Core\View` and `Fuel\Core\Finder`
**Description:**
The `View::forge($file)` method (and `View::set_filename`) allows specifying a view filename. This filename is passed to `Finder::search`. While `Finder` searches within configured paths, it does not strictly prevent directory traversal if the filename contains `../`.
Specifically, `View::set_filename` passes the file path to `Finder::search`. If the path is relative (e.g., `../config.php`), `Finder` may resolve it relative to the configured search paths (like `APPPATH/views`). If traversal characters are used, it can break out of the `views` directory and include any PHP file on the system that the web user has access to.

**Reproduction:**
See `repro/lfi_test.php`.
```php
$view = \View::forge('../repro/target');
echo $view->render();
```
This successfully includes `repro/target.php` which is outside the `views` directory.

## 2. Command Injection in Image_Imagemagick Driver

**Vulnerability Type:** Command Injection
**Affected Component:** `Fuel\Core\Image_Imagemagick`
**Description:**
The `Image_Imagemagick` driver constructs shell commands to execute `convert` and other ImageMagick binaries. It uses the input filename in these commands.
The filename is wrapped in single quotes (`'`) but is not escaped using `escapeshellarg()`. If the filename contains a single quote, it can break out of the quoting and execute arbitrary commands.

**Reproduction:**
See `repro/cmd_inject_simple.php`.
```php
$img = new \Fuel\Core\Image_Imagemagick($config);
$img->load("pwned.png'; echo VULNERABLE; #.png");
```
This generates a shell command like:
`convert ... '/path/to/pwned.png'; echo VULNERABLE; #.png'[0] ...`
which executes `echo VULNERABLE`.

**Recommendation:**
1.  **LFI:** Sanitize input to `View::forge` to remove directory traversal characters or enforce strict checking that the resolved path is within the allowed directories.
2.  **Command Injection:** Use `escapeshellarg()` for all file paths and user-supplied arguments in `Image_Imagemagick::exec`.
