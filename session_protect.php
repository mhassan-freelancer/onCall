<?php session_start();
include 'includes/config.php';
if(!isset($_SESSION['on_call_u_id']))
{
    header("location:".BASE_URL."login.php");
}
