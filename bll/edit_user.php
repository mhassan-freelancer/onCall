<?php session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require ("../session_protect.php");
require ("../role_check_admin.php");
require(__DIR__."/../includes/Db.class.php");
require  __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../includes/config.php';

$db = new DB();
use Respect\Validation\Validator as v;

$firstname =  $lastname = $username = $email = $password ="";
$moudleId = 0;
$isadmin = 0;
$userid = 0;
$isenabled = 0;
$oncall = 0;

if(isset($_POST['firstname']))
     $firstname = $_POST['firstname'];
if(isset($_POST['lastname']))
     $lastname = $_POST['lastname'];
if(isset($_POST['username']))
     $username = $_POST['username'];
if(isset($_POST['email']))
     $email = $_POST['email'];
if(isset($_POST['module']))
     $moudleId= $_POST['module'];
if(isset($_POST['isadmin']))
      $isadmin = $_POST['isadmin'];
if(isset($_POST['id']))
    $userid = $_POST['id'];
if(isset($_POST['isenabled']))
    $isenabled = $_POST['isenabled'];
if(isset($_POST['oncall']))
    $oncall = $_POST['oncall'];

$error = false;
$errorArray = array();
if(!v::email()->validate($email))
{
    $error = true;
    array_push($errorArray, "Email not valid");
}
$alphaInput = v::alpha();
if(!$alphaInput->validate($firstname))
{
    $error = true;
    array_push($errorArray, "No numbers or special Characters allowed");
}
if(!$alphaInput->validate($lastname))
{
    $error = true;
    array_push($errorArray, "No numbers or special Characters allowed");
}
$alphanumeric = v::alnum();
if(!$alphanumeric->validate($username))
{
    $error = true;
    array_push($errorArray, "Only Alpha Numeric Allowed");
}
if(!$userid>0)
{
    $error = true;
    array_push($errorArray, "User ID invalid");
}

if($error)
{
    $_SESSION['error'] =$errorArray;
    header('location:'.BASE_URL.'edit_user.php?id='.$userid);
}
else{
    $db->bind('userid',$userid );
    $isEmailExist = $db->single("select * from user where id = :userid ");

    if($isEmailExist)
    { $db = new DB();

       $data = (array(
            "firstname"=>$firstname,
            "lastname"=>$lastname,
            "username"=>$username,
            "email"=>$email,
            "alarm"=>$moudleId,
            "isadmin"=>$isadmin,
            "enabled"=>$isenabled,
            "userid"=>$userid));


        $db->bind("userId", $userid);


        $db->bind("first_name", $firstname);
        $db->bind("last_name", $lastname);
        $db->bind("username", $username);
        $db->bind("email", $email);
        $db->bind("enabled", (string)$isenabled);
        $db->bind("isadmin", (string)$isadmin);
        $db->bind("oncall", (string)$oncall);

        $db->query("UPDATE user set first_name = :first_name, last_name = :last_name, username = :username ,
        email =:email , enabled = :enabled , admin = :isadmin, alarm = :oncall where id = :userId");


        $_SESSION['success'] ="User Update Successfully";

        header('location:'.BASE_URL.'edit_user.php?id='.$userid);
    }
    
}



?>