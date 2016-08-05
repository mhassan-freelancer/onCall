<?php session_start();
$_SESSION['on_call_u_id'] = null;
$_SESSION['on_call_u_firstname'] = null;
$_SESSION['on_call_u_username'] = null;
session_destroy();
include 'session_protect.php';
?>