<?php
header("Content-Type : application/json ");

require("../includes/Db.class.php");
require  '../vendor/autoload.php';

require '../includes/PasswordStorage.php';


$db = new DB();
use Respect\Validation\Validator as v;
 $useremail = $_REQUEST['username'];
 $password= $_REQUEST['password'];

$error = false;
if(!v::email()->validate($useremail))
{
    $error = true;
}
$passwordValidator= v::noWhitespace();
if(!$passwordValidator->validate($password))
{
    $error = true;
}

if($error)
{
    $rep = array("message"=>"Wrong input" );
      print json_encode($rep);
}

$nullValidator = v::nullType();

$db->bind("email",$useremail);
$hash = PasswordStorage::create_hash($password);

$db->bind("password",$hash);
$person = $db->row("select * from user where email = :email and password = :password");

if(!$nullValidator->validate($person))
{
        session_start();
    $_SESSION['on_call_u_id'] = $person['id'];
    $_SESSION['on_call_u_username'] = $person['username'];
    $_SESSION['on_call_u_firstname']= $person['first_name'];
    $rep = array("message"=>"success" );
    return print json_encode($rep);
}
else
{
    $rep = array("message"=>"Invalid Email or Password" );
    return  print json_encode($rep);
}
?>