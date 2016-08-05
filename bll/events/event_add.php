<?php session_start();
require("../../includes/Db.class.php");
require  '../../vendor/autoload.php';
require '../../includes/PasswordStorage.php';

$db = new DB();
use Respect\Validation\Validator as v;

$event = "";
if(isset($_POST['event']))
    $event = trim($_POST['event']);

$error = false;
$errorArray = array();
if(empty($event) || $event == null) {
    $error = true;
    array_push($errorArray, "Please enter event text.");
}

if($error)
{
    $_SESSION['error'] = $errorArray;
    header('location:/onCall/event_add.php');
}
else{
    $db->bind("text",$event);
    $isEventExist = $db->single("select * from events where text = :text");

    if(!$isEventExist)
    {
        $db->bind("text",$event);
        $db->query("Insert into events (text) VALUES (:text)");
        $_SESSION['success'] = "Event Added Successfully";
        header('location:/onCall/event_add.php');
    }
    else
    {
        $_SESSION['error'] = "Event Already Exists";
        header('location:/onCall/events.php');
    }
}
?>