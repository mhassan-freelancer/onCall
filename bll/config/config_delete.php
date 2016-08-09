<?php
require ("../../session_protect.php");
require ("../../role_check_administrator.php");
require("../../includes/Db.class.php");
require  '../../vendor/autoload.php';
require '../../includes/PasswordStorage.php';
require ('../../includes/config.php');

$db = new DB();
use Respect\Validation\Validator as v;

$configId = 0;
if(isset($_GET['id']))
    $configId = $_GET['id'];

$db->bind("configId", $configId);
$configObj = $db->row("select parameter from config where id = :configId");

if($configObj != null)
{
    $db->bind("configId", $configId);
    $db->query("DELETE FROM config WHERE id = :configId ");

//      TODO: for ONE TO ONE relation
//    $db->bind("value", $configObj['parameter']);
//    $parameterObj = $db->row("Select parameter_id FROM parameters WHERE parameter = UCASE(:value)");
//    if($parameterObj != null) {
//        $db->bind("parameterId", $parameterObj['parameter_id']);
//        $db->query("DELETE FROM parameters WHERE parameter_id = :parameterId ");
//    }

    $_SESSION['success'] = "Config Deleted Successfully";
    header('location:'.BASE_URL.'config.php');
    exit();
}

?>