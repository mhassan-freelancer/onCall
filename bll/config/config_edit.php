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
$configId = 0;
if(isset($_POST['configId']))
    $configId= $_POST['configId'];
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
    array_push($errorArray, "Please Select Module");
}
if($parameterId == 0 || $parameterId == null)
{
    $error = true;
    array_push($errorArray, "Please Select Parameter");
}
if(empty($value) || $value == null)
{
    $error = true;
    array_push($errorArray, "Please Enter value.");
}

if($error)
{
    $_SESSION['error'] = $errorArray;
    header('location:'.BASE_URL.'config_edit.php');
}
else{

    $db->bind("configId", $configId);
    $configObj = $db->row("Select * FROM config WHERE id = :configId");

    if($configObj != null) {
        $db->bind("moduleId", $moduleId);
        $db->bind("parameterId", $parameterId);
        $db->bind("value", $value);
        $db->bind("configId", $configId);
        $db->query("UPDATE config SET module_id = :moduleId, parameter_id = :parameterId, value = :value WHERE id = :configId ");

        /* $db->bind("parameterId", $parameterId);
        $parameterObj = $db->row("Select parameter, label FROM parameters WHERE parameter_id = :parameterId");

        $db->bind("moduleId", $moduleId);
        $db->bind("parameter", $parameterObj["parameter"]);
        $db->bind("value", $value);
        $db->bind("label", $parameterObj["label"]);
        $db->bind("configId", $configId);
        $db->query("UPDATE config SET module_id = :moduleId, parameter = UCASE(:parameter), value = :value, label = :label WHERE id = :configId "); */

        $_SESSION['success'] = "Config Updated Successfully.";
        header('location:'.BASE_URL.'config.php');
    } else {
        $_SESSION['error'] = "Invalid Config Id.";
        header('location:'.BASE_URL.'config.php');
    }

}

?>