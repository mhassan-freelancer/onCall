<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require (__DIR__."/../session_protect.php");
require (__DIR__."/../includes/functions.php");
$db = new DB();
$moduleid= 0;
if(isset($_POST['moduleid']))
{
    $moduleid = $_POST['moduleid'];
}
if($moduleid>0)
{
    $configurations = getModuleConfig($moduleid);
    foreach ($configurations as $config)
    {
        $id = "config-".$config['id'];
        if(!isset($_POST[$id]))
        {
            $_SESSION['error'] ="Some data missing";

            header("location:".BASE_URL."/update_page.php");
        }

    }
    foreach ($configurations as $config)
    {
        $id = "config-".$config['id'];
         $_POST[$id];
        if(isset($_POST[$id]))
        {
            $db->bind("id",$config['id'] );
            $db->bind("value",$_POST[$id] );
            $db->query("update config set value = :value  where id= :id");
        }

    }
    $_SESSION['success'] ="Data Updated Success Fully";
    header("location:".BASE_URL."/update_page.php?moduleid=".$moduleid);
}
?>