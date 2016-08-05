<?php session_start();
require("../includes/Db.class.php");
require  '../vendor/autoload.php';
require '../includes/PasswordStorage.php';
require ('../includes/config.php');

$db = new DB();
use Respect\Validation\Validator as v;

$userId = 0;
if(isset($_POST['userId']))
    $userId = $_POST['userId'];

$firstname =  $lastname = $username = $email = $curPassword = $newPassword = $confirmPassword = "";
if(isset($_POST['firstname']))
    $firstname = $_POST['firstname'];
if(isset($_POST['lastname']))
    $lastname = $_POST['lastname'];
if(isset($_POST['username']))
    $username = $_POST['username'];
if(isset($_POST['cur_password']))
    $curPassword  = trim($_POST['cur_password']);
if(isset($_POST['password']))
    $newPassword  = trim($_POST['password']);
if(isset($_POST['cPassword']))
    $confirmPassword  = trim($_POST['cPassword']);
if(isset($_POST['email']))
    $email = $_POST['email'];

$error = false;
$errorArray = array();
if(empty($firstname) || $firstname == null) {
    $error = true;
    array_push($errorArray, "Please enter first name.");
}
if(empty($lastname) || $lastname == null) {
    $error = true;
    array_push($errorArray, "Please enter last name.");
}
$passwordValidator= v::noWhitespace();
if(!$passwordValidator->validate($curPassword)) {
    $error = true;
    array_push($errorArray, "Please enter current password.");
}
if(!$passwordValidator->validate($newPassword) && $newPassword != null)
{
    $error = true;
    array_push($errorArray, "Please enter new password.");
}
if(($newPassword != null) && (!$passwordValidator->validate($confirmPassword))) {
    $error = true;
    array_push($errorArray, "Please enter confirm password.");
}
if(($newPassword != null) && ($confirmPassword != null)) {
    if($newPassword != $confirmPassword)
    {
        $error = true;
        array_push($errorArray, "New Password and confirm password does not matched.");
    }
}

if($error)
{
    $_SESSION['error'] = $errorArray;
    header('location:'.BASE_URL.'account_settings.php');
}
else
{
    $db->bind("userId", $userId);
    $userObj = $db->row("select * from user where id = :userId");

    $isvaliduser = PasswordStorage::verify_password($curPassword, $userObj['password']);

    if(!$isvaliduser)
    {
        $_SESSION['error'] = "Username or password is incorrect. ";
        header('location:'.BASE_URL.'account_settings.php');
        exit();
    }

    $db->bind("userId", $userObj['id']);

    if(empty($newPassword) || $newPassword == null)
    {}
    else
    {
        $db->bind("password", PasswordStorage::create_hash($newPassword));


        $db->bind("first_name", $firstname);
        $db->bind("last_name", $lastname);


        $db->query("UPDATE user set first_name = :first_name, last_name = :last_name, password = :password where id = :userId");
        
    }

    header('location:'.BASE_URL.'logout.php');
}

?>