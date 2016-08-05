<?php session_start();
require("../includes/Db.class.php");
require  '../vendor/autoload.php';

require '../includes/PasswordStorage.php';

$db = new DB();
use Respect\Validation\Validator as v;

$parameter = $label  = $dataType = "";
$from = $to = 0;
$allowDecimal = false;
if(isset($_POST['parameter']))
     $parameter = trim($_POST['parameter']);
if(isset($_POST['label']))
    $label = trim($_POST['label']);
if(isset($_POST['data_type']))
    $dataType = trim($_POST['data_type']);
if(isset($_POST['from']))
    $from = trim($_POST['from']);
if(isset($_POST['to']))
    $to = trim($_POST['to']);
if(isset($_POST['allow_decimal']))
    $allowDecimal = $_POST['allow_decimal'];

$error = false;
$errorArray = array();

if(empty($parameter) || $parameter == null)
{
    $error = true;
    array_push($errorArray, "Please Enter Parameter.");
}
if(empty($label) || $label == null)
{
    $error = true;
    array_push($errorArray, "Please Enter label.");
}
if(empty($dataType) || $dataType == null)
{
    $error = true;
    array_push($errorArray, "Please Select data type.");
}
if((!empty($dataType) && $dataType == "DROP_DOWN") && ($from == null))
{
    $error = true;
    array_push($errorArray, "Please Set From (Range).");
}
if((!empty($dataType) && $dataType == "DROP_DOWN") && ($to == 0 || $to == null))
{
    $error = true;
    array_push($errorArray, "Please Set To (Range).");
}
if((!empty($dataType) && $dataType == "DROP_DOWN") && ($from != null) && ($to != 0 || $to == null))
{
    if($to < $from) {
        $error = true;
        array_push($errorArray, "Please Set correct range.");
    }
}
if(!empty($dataType) && $dataType != "DROP_DOWN")
{
    $from = 0;
    $to = 0;
    $allowDecimal = false;
}

/*if(!$alphaInput->validate($parameter))
{
    $error = true;
    array_push($errorArray, "No numbers or special Characters allowed");
}
if(!$alphaInput->validate($label))
{
    $error = true;
    array_push($errorArray, "No numbers or special Characters allowed");
}*/

if($error)
{
    $_SESSION['error'] = $errorArray;
    header('location:/onCall/parameter_add.php');
}
else{
    $db->bind("parameter",$parameter);
    $isParameterExist = $db->single("select * from parameters where parameter = UCASE(:parameter)");

    if(!$isParameterExist)
    {
        $db->bind("parameter",$parameter);
        $db->bind("label",$label);
        $db->bind("dataType",$dataType);
        $db->bind("type_Val", $from.','.$to);
        $db->bind("allowDecimal", $allowDecimal);
        $db->query("Insert into parameters (parameter, label, data_type, data_type_values, allow_decimal) VALUES (UCASE(:parameter), :label, :dataType, :type_Val, :allowDecimal)");
        $_SESSION['success'] = "Parameter Added Successfully";
        header('location:/onCall/parameter_add.php');
    }
    else
    {
        $_SESSION['error'] = "Parameter Already Exists";
        header('location:/onCall/parameter_add.php');
    }
}
?>