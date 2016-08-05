<?php
	
	
$ini_array = parse_ini_file("config.ini");

$dbhost = $ini_array['DB_HOST'];
$dbuser = $ini_array['DB_USER'];
$dbpass = $ini_array['DB_PASSWORD'];
$dbname = $ini_array['DB_NAME'];

$dbconfig = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)	
	or die("Could not connect to the database");
	
?>
	