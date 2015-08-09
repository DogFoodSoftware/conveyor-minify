<?php
// The minify configuration is based on the 'site' which is matched to the 
// 'SERVER_NAME' from the server vars.

$host = $_SERVER['SERVER_NAME'];
// Check to see if this is 'localhost' access. In that case, we expect
// to find a single, non-special http configuration file.
# TODO: reference doc
if ($host == 'localhost' || preg_match('/(\d{1,3}\.){2}\d{1,3}/', $host)) {
    $domain = 'default';
}
else {
    $host_bits = explode('.', $host);
    $domain = $host_bits[count($host_bits) - 2].'.'.
            $host_bits[count($host_bits) -1];
}

$minify_config_file = "/home/user/data/DogFoodSoftware/conveyor-minify/$domain.minify.php";
#error_log("including: $minify_config_file");
$minify_config = null;
if (file_exists($minify_config_file)) {
#    error_log("including...");
    $minify_config = include $minify_config_file;
#    error_log("good? ".(!empty($minify_config)));
    
    ob_start();                    // start buffer capture
    var_dump( $minify_config );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
#    error_log( $contents );
}
else {
    error_log("Did not find expected minify config file: '$minify_config_file'");
    return;
}

return $minify_config;
?>
