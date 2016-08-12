<?php


require(__DIR__."/Db.class.php");
require (__DIR__."/config.php");


function checkDaemonProcess($process){

	$result = exec("ps -ef | grep $process| grep -v grep | wc -l");
	return $result;
}

function controlOncallDaemon($daemon, $command){


	if (($daemon != "uplinkmgrd") && ($daemon != "fieldsvcmgrd"))
		return "$daemon is not a valid daemon for this function";
	else{
		$response = exec("sudo /etc/init.d/$daemon $command");
		return $response;
	}
}

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

function getEventId($dbconfig, $event){
	$sql = "SELECT id AS event_id
			FROM events
			WHERE text ='".$event."'; ";

	$result = mysqli_fetch_assoc(mysqli_query($dbconfig, $sql));

	return $result['event_id'];

}

function getEventById($dbconfig, $event_id){
	$sql = "SELECT text AS event_text
			FROM events
			WHERE id = $event_id;";

	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));

	return $result['event_text'];

}

function getCustomerIdBySerial($dbconfig, $radio_unit_serial){
	$sql =	"SELECT repairshopr_customer_id AS rs_cid
			FROM radio_units
			WHERE serial = $radio_unit_serial";
	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));

	return $result['rs_cid'];
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


function getLastRadioEventData($dbconfig, $event_id, $radio_unit_serial){
	$sql = "SELECT *
			FROM radio_events
			WHERE event_id = $event_id
			AND radio_unit_serial = $radio_unit_serial
			ORDER BY notification_time DESC
			LIMIT 1";


	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));

	return $result;

}

function getTimeSinceLastRadioEvent($dbconfig, $radio_unit_serial, $event_id){
	$event_data = getLastRadioEventData($dbconfig, $event_id, $radio_unit_serial);

	$now = new DateTime();
	if (isset($event_data['notification_time'])){
		$then = new DateTime($event_data['notification_time']);


		$sinceThen = $then->diff($now);
		$days = $sinceThen->format('%d');
		$daysInMins = ($days * 24 * 60);
		$hours = $sinceThen->format('%h');
		$hoursInMins = ($hours * 60);
		$mins = $sinceThen->format('%i');

		$mins = ($daysInMins + $hoursInMins + $mins);

		return $mins;
	}
	else
		return null;
}


function setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $flag){
	$sql = "UPDATE radio_units
			SET critical = $flag
			WHERE serial = $radio_unit_serial";

	$result = mysqli_query($dbconfig, $sql) or trigger_error(mysql_error()." ".$sql);
}



/* 	inserts radio events received from the webservice */

function insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, $critical, $ticket_status){

	if (!isset($ticket_id))
		$ticket_id = "";


	$sql = "INSERT INTO radio_events (notification_time, event_id, partner_log_id, radio_unit_serial, repairshpr_ticket_id, critical, repairshpr_ticket_status, repairshpr_ticket_number )
			VALUES ('$notification_time', $event_id, '$partner_log_id', '$radio_unit_serial', $ticket_id, $critical, '$ticket_status', $ticket_num)
			ON DUPLICATE KEY UPDATE notification_time = '$notification_time'";


	$result = mysqli_query($dbconfig, $sql) or trigger_error(mysql_error()." ".$sql);
}


function getLastEventTicketData($dbconfig, $radio_unit_serial, $event_id){
	$sql = "SELECT repairshpr_ticket_id, repairshpr_ticket_number
			FROM radio_events
			WHERE event_id = $event_id
			ORDER BY notification_time DESC
			LIMIT 1";

	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));

	return $result;


}

function getAllUnitsSerial($dbconfig){

	$sql = "SELECT serial AS num
			FROM radio_units;";

	$result = getMySQLResultArray($sql, $dbconfig);

	return $result;


}

function getEventsByTicketStatus($dbconfig, $event_id, $ticket_status){

	$sql = "SELECT *
			FROM radio_events
			WHERE event_id = $event_id
			AND repairshopr_ticket_status = '$ticket_status')";

	$result = getMySQLResultArray($sql, $dbconfig);

	return $result;

}



function jsonCurlRequest($data_string, $url, $method){
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
	);


	$json_result = curl_exec($ch);

	if (isset($result->error))
	{
		echo 'error:' . $result->error;
	}


	return $json_result;
}




/*	Opens a ticket with RepairShopr
	Returns the ticket data	*/

function openRepairShoprAlarmTicket($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $status){
	$RS_KEY = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_KEY");
	$RS_TICKET_URL = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_TICKET_URL");

	$rsOpenTicketUrl = $RS_TICKET_URL."?api_key=".$RS_KEY;

	$event_text = getEventById($dbconfig, $event_id);
	$subject = $event_text;
	$comment = "$notification_time : system registered a $event_text";

	$customer_id = getCustomerIdBySerial($dbconfig, $radio_unit_serial);

	$data = array("customer_id" => $customer_id, "subject" => $subject, "status" => $status, "comment_subject" => "Event Details", "comment_body" =>$comment);
	$data_string = json_encode($data);

	$result = jsonCurlRequest($data_string, $rsOpenTicketUrl, "POST");

	$ticket_data = json_decode($result);

	return $ticket_data;
}

function addRepairShoprTicketComment($dbconfig, $ticket_id, $comment_subject, $comment_body){
	$RS_KEY = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_KEY");
	$RS_TICKET_URL = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_TICKET_URL");
	$rsAddCommentTicketUrl = $RS_TICKET_URL."/".$ticket_id."/comment?api_key=".$RS_KEY;

	$data = array( "subject" =>"Update", "body" =>$comment_body);
	$data_string = json_encode($data);
	$result = jsonCurlRequest($data_string, $rsAddCommentTicketUrl, "POST");

}

function updateRepairShoprAlarmTicket($dbconfig, $ticket_id, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $status){
	$RS_KEY = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_KEY");
	$RS_TICKET_URL = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_TICKET_URL");
	$rsUpdateTicketUrl = $RS_TICKET_URL."/".$ticket_id."?api_key=".$RS_KEY;


	$event_text = getEventById($dbconfig, $event_id);
	$subject = $event_text;
	$comment = "$notification_time :  system registered a $event_text";

	$customer_id = getCustomerIdBySerial($dbconfig, $radio_unit_serial);

	addRepairShoprTicketComment($dbconfig, $ticket_id, $subject, $comment);

	$data = array("customer_id" => $customer_id, "status" => $status);
	$data_string = json_encode($data);

	$result = jsonCurlRequest($data_string, $rsUpdateTicketUrl, "PUT");

	$ticket_data = json_decode($result);

	updateRadioEventsTable($dbconfig, $ticket_id, $status);

	return $ticket_data;

}

function getRepairShoprTicketData($dbconfig, $ticket_id){
	$RS_KEY = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_KEY");
	$RS_TICKET_URL = getConfigParameter($dbconfig, "UPLINK_DAEMON", "RS_TICKET_URL");
	$rsGetTicketDataUrl = $RS_TICKET_URL."/".$ticket_id."?api_key=".$RS_KEY;

	$result = jsonCurlRequest($data_string, $rsGetTicketDataUrl, "GET");

	return $result;
}

function updateSystemTicketStatus($dbconfig, $ticket_id, $repair_shpr_ticket_status){
	$sql = "UPDATE radio_events
			SET repairshpr_ticket_status = '$repair_shpr_ticket_status'
			WHERE repairshpr_ticket_id = $ticket_id";

	echo $sql;

	$result = mysqli_query($dbconfig, $sql) or trigger_error(mysql_error()." ".$sql);
}




function sendSMTPMail($dbconfig, $subject, $message, $recipient){
//	require_once 'Mail.php';
//	require_once 'Mail/mime.php';


	require_once('phpmailer/vendor/autoload.php');

	$host = getConfigParameter($dbconfig, "ONCALL", "SENDER_SMTP_HOST");
	$port = getConfigParameter($dbconfig, "ONCALL", "SENDER_SMTP_PORT");
	$username = getConfigParameter($dbconfig, "ONCALL", "SENDER_USER");
	$password = getConfigParameter($dbconfig, "ONCALL", "SENDER_PASS");



	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $host;		                       	  // Specify main and backup server
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $username;		                  // SMTP username
	$mail->Password = $password;		                  // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
	$mail->Port = $port;                                  //Set the SMTP port number - 587 for authenticated TLS
	$mail->setFrom($username);   						  //Set who the message is to be sent from
//	$mail->addReplyTo('labnol@gmail.com', 'First Last');  //Set an alternative reply-to address
	$mail->addAddress($recipient);  // Add a recipient
//	$mail->addAddress('ellen@example.com');               // Name is optional
//	$mail->addCC('cc@example.com');
//	$mail->addBCC('bcc@example.com');
	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//	$mail->addAttachment('/usr/labnol/file.doc');         // Add attachments
//	$mail->addAttachment('/images/image.jpg', 'new.jpg'); // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = $message;

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
//	$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		exit;
	}

	echo 'Message has been sent';


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
function getModuleConfig($moudleId)
{
	$db = new DB();
	$db->bind("id", $moudleId);
	return $data = $db->query("select * from config as con inner join parameters as par on con.parameter_id = par.parameter_id where con.module_id =:id");
	
}
function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';
	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}
	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];
	$password = str_shuffle($password);
	if(!$add_dashes)
		return $password;
	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}
function getTotalUnitsCount()
{
	$db = new DB();
	$totalUnits = $db->single("select count(*) as 'criticalUnits' from radio_units");
	return $totalUnits;
}
function getUnits()
{
	$db = new DB();
	return $db->query("select * from radio_units");
}
function getCriticalUnitsCount()
{
	$db = new DB();
	$criticalUnits = $db->single("select count(*) as 'criticalUnits' from radio_units Where critical = 1");
	return $criticalUnits;
}
function getOpenTicketsCount()
{
	$db = new DB();
	$criticalUnits = $db->single("SELECT COUNT(*) FROM radio_events WHERE repairshpr_ticket_status != 'Resolved'");
	return $criticalUnits;
}
function getEventLists()
{
	$db = new DB();
	return $db->query("select * from events");
}
function getSystemDetails()
{
	$db = new DB();
	return $db->query("select * from radio_events as re INNER  JOIN  events as ev on re.event_id = ev.id");
}
function getSystemDetailsIndex(){
	$db = new DB();
	return $db->query("select * from radio_events as re INNER  JOIN  radio_units as ru on re.radio_unit_serial = ru.serial inner join events as ev on ev.id = re.event_id where re.repairshpr_ticket_status  != 'Resolved' order by re.id desc  limit 50");
}
function getRadioEventBySearialNumber($query, $dateRange)
{
	$db = new DB();
	$sql = "";
	if($query != "" && $dateRange != "") {
		$dateRange = explode(" - ", $dateRange);
		$db->bind("query",$query);
		$db->bind("from", date_format(date_create($dateRange[0]),"Y-m-d"));
		$db->bind("to", date_format(date_create($dateRange[1]),"Y-m-d"));
		$sql = "SELECT * FROM radio_events as re INNER JOIN  events as ev on re.event_id = ev.id WHERE re.radio_unit_serial = :query   AND re.notification_time BETWEEN :from AND :to ";
	} else if ($query != "") {
		$db->bind("query",$query);
		$sql = "SELECT * FROM radio_events as re INNER JOIN  events as ev on re.event_id = ev.id WHERE re.radio_unit_serial = :query";
	} else if ($dateRange != "") {
		$dateRange = explode(" - ", $dateRange);
		$db->bind("from", date_format(date_create($dateRange[0]),"Y-m-d"));
		$db->bind("to", date_format(date_create($dateRange[1]),"Y-m-d"));
		$sql = "SELECT * FROM radio_events as re INNER JOIN  events as ev on re.event_id = ev.id WHERE re.notification_time BETWEEN :from AND :to";
	}
	$data = $db->query($sql);
	if(!$data)
	{
		return null;
	}
	else
	{
		return $data;
	}
}
function getUnitName($unitname){
	$db = new DB();
	$db->bind("unitname",$unitname);
	$data = $db->row("select * from radio_units where serial = :unitname");

	if($data)
	{
		return $data['unit_name'];
	}
	return false;
}

?>