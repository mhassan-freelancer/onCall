<?php session_start();
require("../includes/Db.class.php");
require  '../vendor/autoload.php';

require '../includes/PasswordStorage.php';


$db = new DB();
use Respect\Validation\Validator as v;

$parameter =  $label = "";
$parameterId = 0;
if(isset($_POST['parameter']))
    $parameter = trim($_POST['parameter']);
if(isset($_POST['label']))
    $label = trim($_POST['label']);
if(isset($_POST['parameterId']))
    $parameterId = $_POST['parameterId'];
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

if($error)
{
    $_SESSION['error'] =$errorArray;
    header('location:/onCall/parameters.php');
}
else{
    $db->bind("parameterId", $parameterId);
    $parameterObj = $db->row("select * from parameters where parameter_id = :parameterId");

    /* $db->bind("value", $parameterObj['parameter']);
    $configParameter = $db->row("Select id FROM config WHERE parameter = :value");
    echo $configParameter; */

    if($parameterObj != null)
    {
        $db->bind("parameter",$parameter);
        $db->bind("label",$label);
        $db->bind("parameterId", $parameterId);
        $db->bind("dataType",$dataType);
        $db->bind("type_Val", $from.','.$to);
        $db->bind("allowDecimal", $allowDecimal);
        $db->query("UPDATE parameters SET parameter = UCASE(:parameter), label = :label, data_type = :dataType, data_type_values = :type_Val, allow_decimal = :allowDecimal WHERE parameter_id = :parameterId ");

        /*if($configParameter != null) {
            $db->bind("parameter",$parameter);
            $db->bind("label",$label);
            $db->bind("configId", $configParameter['id']);
            $db->query("UPDATE config SET parameter = UCASE(:parameter), label = :label WHERE id = :configId ");
        }*/

        $_SESSION['success'] = "Parameter Updated Successfully.";
        header('location:/onCall/parameters.php');
    }
    
}



?>