<?php
$ini_array = parse_ini_file("config.ini.php");
if (!defined('BASE_URL')) {
    define('BASE_URL', $ini_array['baseUrl']);

}
if (!defined('BASE_PATH')) {
    define('BASE_PATH',$ini_array['basePath'] );
}
if (!defined('PHP_MAILER')) {
    define('PHP_MAILER',$ini_array['phpMailer'] );
}



?>