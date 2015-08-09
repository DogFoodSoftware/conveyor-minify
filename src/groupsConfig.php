<?php
// The minify configuration is based on the 'site' which is matched to the 
// 'SERVER_NAME' from the server vars.

$host = $_SERVER['SERVER_NAME'];
// Check to see if this is 'localhost' access. In that case, we expect
// to find a single, non-special http configuration file.
# TODO: reference doc
if ($host == 'localhost' || preg_match('/(\d{1,3}\.){2}\d{1,3}/', $host)) {
    $file_list = glob('/home/user/.conveyor/data/dogfoodsoftware.com/conveyor-apache/conf-inc/*.httpd.conf');
    ob_start();
    $dump = ob_get_contents();
    $default_site_conf = null;
    foreach ($file_list as $file) {
        $file = basename($file);
        if (preg_match('/^site-([\w\.-]+\.([a-z]+))\.httpd\.conf$/', $file, $matches)) {
            if ($default_site_conf == null) {
                $default_site_conf = $file;
                $domain = $matches[1];
            }
            else {
                error_log("Multiple default sites found. Please prune to single default.");
                return;
            }
        }
    }
    if (empty($default_site_conf)) {
        error_log("Did not find default site.");
    }
}
else { # Not 'localhost'
    $host_bits = explode('.', $host);
    $domain = $host_bits[count($host_bits) - 2].'.'.
        $host_bits[count($host_bits) -1];
}
$minify_config_file = "/home/user/.conveyor/runtime/$domain/$domain/conf/minify.php";
error_log("including: $minify_config_file");
$minify_config = null;
if (file_exists($minify_config_file)) {
    error_log("including...");
    $minify_config = include $minify_config_file;
    error_log("good? ".(!empty($minify_config)));
    
    ob_start();                    // start buffer capture
    var_dump( $minify_config );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
    error_log( $contents );
}
else {
    error_log("Did not find expected minify config file: '$minify_config_file'");
    return;
}

return $minify_config;
?>
