<?php session_start();
require("../includes/Db.class.php");
require  '../vendor/autoload.php';

require '../includes/PasswordStorage.php';

$db = new DB();
use Respect\Validation\Validator as v;

$userId = 0;
if(isset($_GET['id']))
    $userId = $_GET['id'];

$db->bind("userId", $userId);
$userObj = $db->row("select * from user where id = :userId");

if($userObj != null)
{
    $db->bind("userId", $userId);
    $db->query("UPDATE user SET enabled = 0 WHERE id = :userId ");

    $_SESSION['success'] = "User ".$userObj['email']." disabled successfully.";
    header('location:/onCall/system_users.php');
    exit();
}

?>