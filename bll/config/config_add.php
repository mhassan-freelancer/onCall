<?php
require ("../../session_protect.php");
require ("../../role_check_administrator.php");
require("../../includes/Db.class.php");
require  '../../vendor/autoload.php';
require '../../includes/PasswordStorage.php';
require ('../../includes/config.php');

$db = new DB();
use Respect\Validation\Validator as v;

$value = "";
$moduleId = 0;
$parameterId = 0;
if(isset($_POST['value']))
    $value = trim($_POST['value']);
if(isset($_POST['module']))
    $moduleId= $_POST['module'];
if(isset($_POST['parameter']))
    $parameterId= $_POST['parameter'];

$error = false;
$errorArray = array();

if($moduleId == 0 || $moduleId == null)
{
    $error = true;
    array_push($errorArray, "Please Select Module.");
}
if($parameterId == 0 || $parameterId == null)
{
    $error = true;
    array_push($errorArray, "Please Select Parameter.");
}
if(empty($value) || $value == null)
{
    $error = true;
    array_push($errorArray, "Please Enter value.");
}

if($error)
{
    $_SESSION['error'] = $errorArray;
    header('location:'.BASE_URL.'config_add.php');
}
else{
    $db->bind("moduleId", $moduleId);
    $db->bind("value", $value);
    $db->bind("parameterId", $parameterId);
    $db->query("Insert into config (module_id, parameter_id, value) VALUES (:moduleId, :parameterId, :value)");

    /*$db->bind("parameterId", $parameterId);
    $parameterObj = $db->row("Select parameter, label FROM parameters WHERE parameter_id = :parameterId");

    $db->bind("moduleId", $moduleId);
    $db->bind("parameter", $parameterObj["parameter"]);
    $db->bind("value", $value);
    $db->bind("label", $parameterObj["label"]);
    $db->query("Insert into config (module_id, parameter, value, label) VALUES (:moduleId, UCASE(:parameter), :value, :label)");*/

    $_SESSION['success'] = "Config Added Successfully";
    header('location:'.BASE_URL.'config_add.php');

    /*if(ture)
    {
        $db->bind("parameter",$parameter);
        $db->bind("label",$label);

        $db->query("Insert into parameters (parameter, label) VALUES (UCASE(:parameter), :label)");
        $_SESSION['success'] = "Config Added Successfully";
        header('location:'.BASE_URL.'config_add.php');
    }
    else
    {
        $_SESSION['error'] = "Config Already Exists";
        header('location:'.BASE_URL.'config_add.php');
    }*/
}
?>