<?php session_start();
require("../../includes/Db.class.php");
require  '../../vendor/autoload.php';

require '../../includes/PasswordStorage.php';

$db = new DB();
use Respect\Validation\Validator as v;

$eventId = 0;
if(isset($_GET['id']))
    $eventId = $_GET['id'];

$db->bind("eventId", $eventId);
$parameterObj = $db->row("select * from events where id = :eventId");

if($parameterObj != null)
{
    $db->bind("eventId", $eventId);
    $db->query("DELETE FROM events WHERE id = :eventId ");

    $_SESSION['success'] ="Event Deleted Successfully";
    header('location:/onCall/events.php');
    exit();
}

?>