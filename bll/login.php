<?php
header("Content-Type : application/json ");
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require(__DIR__."/../includes/Db.class.php");
require  __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../includes/PasswordStorage.php';



$db = new DB();
use Respect\Validation\Validator as v;
$username = $_POST['username'];
$password= $_POST['password'];

$error = false;
$alphanumeric = v::alnum();
if(!$alphanumeric->validate($username))
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
    return print json_encode($rep);
}

$nullValidator = v::nullType();

$db->bind("username",$username);

$person = $db->row("select * from user where username = :username and enabled=1 ");
if($person)
{

    $isvaliduser = PasswordStorage::verify_password($password, $person['password']);


    if(!$nullValidator->validate($person) && $isvaliduser)
    {
        session_start();
        $_SESSION['on_call_u_id'] = $person['id'];
        $_SESSION['on_call_u_username'] = $person['username'];
        $_SESSION['on_call_u_firstname']= $person['first_name'];
        $_SESSION['on_call_is_admin']= $person['admin'];
        $_SESSION['on_call_is_super_admin']= $person['administrator'];
        $_SESSION['on_call_is_oncall']= $person['alarm'];
        $rep = array("message"=>"success" );
        return print json_encode($rep);
    }
    else
    {
        $rep = array("message"=>"Invalid Email or Password" );
        return print json_encode($rep);
    }
}
else
{
    $rep = array("message"=>"Your Account is blocked" );
    return print json_encode($rep);
}
?>