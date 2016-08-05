<?php
$ini_array = parse_ini_file("config.ini.php");
define('BASE_URL', $ini_array['baseUrl']);
define('BASE_PATH',$ini_array['basePath'] );

?>