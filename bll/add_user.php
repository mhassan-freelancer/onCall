<?php 
require ("../session_protect.php");
require ("../role_check_admin.php");
require ('../includes/functions.php');
// require("../includes/Db.class.php");
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
    header('location:'.BASE_URL.'add_user.php');
}
else{
    $db->bind("email",$email);
    $db->bind("username",$username);
    $isEmailExist = $db->single("select * from user where email = :email or username = :username");

    if(!$isEmailExist)
    {
        $password = generateStrongPassword(8);
        $hash = PasswordStorage::create_hash($password);

        $db->bind("firstname",$firstname);
        $db->bind("lastname",$lastname);
        $db->bind("username",$username);
        $db->bind("email",$email);
        $db->bind("password", $hash);

        if($isadmin == "on")
        {
            $isadmin = 1;
        }
        $db->bind("isadmin",$isadmin);

        $db->query("Insert into user (first_name,last_name,email,username,password,enabled,admin)VALUES (
                                  :firstname,:lastname,:email,:username,:password,1,:isadmin)");

        require PHP_MAILER;
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "asimoncall@gmail.com";
        $mail->Password = "asim12345";
        $mail->setFrom('asimizb@gmail.com', 'OnCall Support');
        $mail->addAddress($email, $firstname." ".$lastname);
        $mail->Subject = 'OnCall User Account';
        $mail->msgHTML("Your account has been created. Please login with this Password ".$password);
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            ?>
            <script>alert("Something went wrong Contact us support@oncall.com");</script>
            <?php
        } else {
            ?>
            <script>alert("Check your email");</script>
            <?php
        }


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