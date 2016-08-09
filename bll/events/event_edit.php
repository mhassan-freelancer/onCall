<?php
require ("../../session_protect.php");
require ("../../role_check_administrator.php");
require("../../includes/Db.class.php");
require  '../../vendor/autoload.php';
require '../../includes/PasswordStorage.php';
require ('../../includes/config.php');

$db = new DB();
use Respect\Validation\Validator as v;

$text = "";
$eventId = 0;
if(isset($_POST['event']))
    $text = trim($_POST['event']);
if(isset($_POST['eventId']))
    $eventId = $_POST['eventId'];

$error = false;
$errorArray = array();
if($eventId == 0 || $eventId == null) {
    $error = true;
    array_push($errorArray, "Invalid Event Id.");
}
if(empty($text) || $text == null) {
    $error = true;
    array_push($errorArray, "Please enter event text.");
}

if($error)
{
    $_SESSION['error'] =$errorArray;
    header('location:'.BASE_URL.'events.php');
}
else{
    $db->bind("eventId", $eventId);
    $db->bind("text", $text);
    $db->query("UPDATE events SET text = :text WHERE id = :eventId ");

    $_SESSION['success'] = "Event updated successfully.";
    header('location:'.BASE_URL.'events.php');
}
?>