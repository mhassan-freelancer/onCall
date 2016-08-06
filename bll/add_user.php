<?php 
require ("../session_protect.php");
require("../includes/Db.class.php");
require  '../vendor/autoload.php';
require '../includes/PasswordStorage.php';
require ('../includes/config.php');

$db = new DB();
use Respect\Validation\Validator as v;

$firstname =  $lastname = $username = $email = $password ="";

$isadmin = false;
if(isset($_POST['firstname']))
     $firstname = $_POST['firstname'];
if(isset($_POST['lastname']))
     $lastname = $_POST['lastname'];
if(isset($_POST['username']))
     $username = $_POST['username'];
if(isset($_POST['password']))
     $password  = $_POST['password'];
if(isset($_POST['email']))
     $email = $_POST['email'];

if(isset($_POST['isadmin']))
      $isadmin = $_POST['isadmin'];


$error = false;
$errorArray = array();
if(!v::email()->validate($email))
{
    $error = true;
    array_push($errorArray, "Email not valid");
}
$passwordValidator= v::noWhitespace();
if(!$passwordValidator->validate($password))
{
    $error = true;
    array_push($errorArray, "No space allowed in password");
}
else
{
 $password = PasswordStorage::create_hash($password);
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

if($error)
{
    $_SESSION['error'] =$errorArray;
    header('location:/add_user.php');
}
else{
    $db->bind("email",$email);
    $db->bind("username",$username);
    $isEmailExist = $db->single("select * from user where email = :email or username = :username");

    if(!$isEmailExist)
    {
        $db->bind("firstname",$firstname);
        $db->bind("lastname",$lastname);
        $db->bind("username",$username);
        $db->bind("email",$email);
        $db->bind("password",$password);

        if($isadmin == "on")
        {
            $isadmin = 1;
        }
        $db->bind("isadmin",$isadmin);

        $db->query("Insert into user (first_name,last_name,email,username,password,enabled,admin)VALUES (
                                  :firstname,:lastname,:email,:username,:password,1,:isadmin)");
        $_SESSION['success'] ="User Added Successfully";
        header('location:'.BASE_URL.'add_user.php');
    }
    else
    {
        $_SESSION['error'] ="User Already Exists";
        header('location:'.BASE_URL.'add_user.php');
    }

}



?>