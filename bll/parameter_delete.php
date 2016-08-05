<?php session_start();
require("../includes/Db.class.php");
require  '../vendor/autoload.php';

require '../includes/PasswordStorage.php';
require ('../includes/config.php');

$db = new DB();
use Respect\Validation\Validator as v;

$parameterId = 0;
if(isset($_GET['id']))
    $parameterId = $_GET['id'];

$db->bind("parameterId", $parameterId);
$parameterObj = $db->row("select * from parameters where parameter_id = :parameterId");

if($parameterObj != null)
{
    $db->bind("parameterId", $parameterId);
    $db->query("DELETE FROM parameters WHERE parameter_id = :parameterId ");

    $db->bind("value", $parameterObj['parameter']);
    $configParameter = $db->row("Select id FROM config WHERE parameter = UCASE(:value)");
    if($configParameter != null) {
        $db->bind("configId", $configParameter['id']);
        $db->query("DELETE FROM config WHERE id = :configId ");
    }

    $_SESSION['success'] ="Parameter Deleted Successfully";
    header('location:'.BASE_URL.'parameters.php');
    exit();
}

?>