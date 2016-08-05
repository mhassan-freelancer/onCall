<?php

//require_once('../includes/connection.php');

require(__DIR__."/Db.class.php");

function getDBTotalRadioUnits($dbconfig){
	$sql = "SELECT count(*)
			FROM radio_units";
	
	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));
	
	return $result[0];
}

function updateConfigParameter($dbconfig, $module, $paramenter, $value){
	
	$id = getModuleID($dbconfig, $module);
	
	$sql = "UPDATE config
			SET VALUE = '$value'
			WHERE module_id = $id
			AND parameter = '$paramenter'; ";
			
	$result = mysqli_query($dbconfig, $sql) or trigger_error(mysql_error()." ".$sql);
}

function getModuleID($dbconfig, $module){
	$sql = "SELECT id 
			FROM modules
			WHERE name ='$module'";
				
	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));
	
	return $result[0];		
}

function getConfigParameter($dbconfig, $module, $param){
	
	$id = getModuleID($dbconfig, $module);
	
	$sql = "SELECT value
			FROM config
			WHERE module_id = $id
			and parameter ='$param'";
	
	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));
	
	return $result['value'];
	
}


function getEventId($dbconfig, $event){
	$sql = "SELECT id AS event_id
			FROM events
			WHERE text ='".$event."'; ";	
		
	$result = mysqli_fetch_assoc(mysqli_query($dbconfig, $sql));
	
	return $result['event_id'];

}

function getRadioUnitSerials($dbconfig){

	$sql = "SELECT serial
			FROM radio_units";

	return getMySQLResultArray($sql, $dbconfig);
}

function insertRadioUnit($dbconfig, $serial, $unit_name){
	
	$sql = "INSERT INTO radio_units (serial, unit_name)
			VALUES ('$serial', '$unit_name')
			ON DUPLICATE KEY UPDATE unit_name = '$unit_name'";
			
	$result = mysqli_query($dbconfig, $sql) or trigger_error(mysql_error()." ".$sql);	
}


function insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial){
	
	$sql = "INSERT INTO radio_events (notification_time, event_id, partner_log_id, radio_unit_serial)
			VALUES ('$notification_time', $event_id, $partner_log_id, $radio_unit_serial )
			ON DUPLICATE KEY UPDATE notification_time = '$notification_time'";
			
	$result = mysqli_query($dbconfig, $sql) or trigger_error(mysql_error()." ".$sql);	
}

function getMySQLResultArray($sql, $dbconfig){

	$array = array();

	$result = mysqli_query($dbconfig,$sql) or die();

	$num_rows = $result->num_rows;
		
	if($num_rows) {
		while($row =  mysqli_fetch_assoc($result)){
			$array[] = $row;
		}
	}
	
	return $array;
}
function getUsers()
{
	$db = new DB();
	$users = $db->query("select * from user");
	return $users;
}
function getModules()
{
	$db = new DB();
	$mod = $db->query("select * from modules");
	return $mod;
}
function getModuleName($moduleId)
{
	$db = new DB();
	$db->bind("id",$moduleId );
	$result = $db->row("select * from modules where id =:id ");
	return $result['name'];
}
function getUserInfo($id)
{

	$db = new DB();
	$db->bind("id",$id );
	$result = $db->row("select id,first_name,last_name,username,email,admin,alarm,enabled from user where id =:id ");
	return $result;
}
function getParameters()
{
    $db = new DB();
    $parameters = $db->query("select * from parameters ORDER BY parameter ASC");
    return $parameters;
}
function getParameterInfo($id)
{
    $db = new DB();
    $db->bind("id", $id);
    $result = $db->row("select * from parameters where parameter_id =:id ");
    return $result;
}
function getParameterIdByTitle($parameter)
{
    $db = new DB();
    $db->bind("parameter", $parameter);
    $result = $db->row("select parameter_id from parameters where parameter = UCASE(:parameter) ");
    return $result;
}
function getParameterInfoByTitle($parameter)
{
    $db = new DB();
    $db->bind("parameter", $parameter);
    $result = $db->row("select * from parameters where parameter = UCASE(:parameter) ");
    return $result;
}

function getConfig()
{
    $db = new DB();
    $config = $db->query("SELECT c.id, c.module_id, c.`value`, p.parameter, p.label FROM config c INNER JOIN parameters p ON p.parameter_id = c.parameter_id");
    return $config;
}
function getConfigInfo($id)
{
    $db = new DB();
    $db->bind("id", $id);
    $config = $db->row("select * from config where id = :id");
    return $config;
}

function getEvents()
{
    $db = new DB();
    $events = $db->query("select * from events");
    return $events;
}
function getEventInfo($id)
{
    $db = new DB();
    $db->bind("id", $id);
    $events = $db->row("select * from events Where id = :id");
    return $events;
}
?>