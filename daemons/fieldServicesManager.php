#!/usr/bin/php

<?php

require_once('System/Daemon.php');
date_default_timezone_set('America/New_York');

$options = array(
    'appName' => 'fieldsvcmgrd',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Updates Tickets Status as Defined by AA Power',
    'authorName' => 'Walter Bertot - Greywind',
    'authorEmail' => 'walter@greywind.tech',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '64M',
    'appRunAsGID' => 0,
    'appRunAsUID' => 0,
);

System_Daemon::setOptions($options);


System_Daemon::start();

System_Daemon::log(System_Daemon::LOG_INFO, "Starting the daemon...");
//echo $path = System_Daemon::writeAutoRun();


include __DIR__.'/../includes/connection.php';
include __DIR__.'/../includes/functions.php';


$GEN_RUN_CYCLE_THRESHOLD = getConfigParameter($dbconfig, "FS_AUTOMATION_DAEMON", "GEN_RUN_CYCLE_THRESHOLD");
$GEN_RUN_CYCLE_THRESHOLD_RESOLVED = getConfigParameter($dbconfig, "FS_AUTOMATION_DAEMON", "GEN_RUN_CYCLE_THRESHOLD_RESOLVED");
$CLOSE_TICKET_EXPIRATION_TIME = getConfigParameter($dbconfig, "FS_AUTOMATION_DAEMON", "CLOSE_TICKET_EXPIRATION_TIME");
$TICKET_NOTIFICATION_PACE = getConfigParameter($dbconfig, "FS_AUTOMATION_DAEMON", "TICKET_NOTIFICATION_PACE");


while(true){
	
	//Make sure to have latest status of all unresolved tickets
/*
	$events = getUnresolvedTicketsEvents($dbconfig);
	foreach ($events as $event){
		$system_ticket_status = $event['repairshpr_ticket_status'];
		$ticket_id = $event['repairshpr_ticket_id'];
		$ticket_data = json_decode(getRepairShoprTicketData($dbconfig, $ticket_id));
		$repair_shpr_ticket_status = $ticket_data->ticket->status;
		if ($repair_shpr_ticket_status == $system_ticket_status)
			System_Daemon::log(System_Daemon::LOG_INFO, "Event ".$event['id'] ." is in the correct status of $repair_shpr_ticket_status." );
		else{
			updateSystemTicketStatus($dbconfig, $ticket_id, $repair_shpr_ticket_status);
			System_Daemon::log(System_Daemon::LOG_INFO, "Event " . $event['id'] ." being updated from $system_ticket_status to  $repair_shpr_ticket_status." );
		}
		sleep (2); 	//Wait 1 second not to flood API.
	}
	

	
	// Make sure generators are running every week (Signal 3)
	// If not running in last 7.25 days open a ticket

	$serials = getAllUnitsSerial($dbconfig);
	$event_id = 3;
	
	foreach ($serials as $serial){
		$radio_unit_serial = $serial['num'];
		$timeInMins = getTimeSinceLastRadioEvent($dbconfig, $radio_unit_serial, $event_id);
		$timeInDays = (($timeInMins / 60) / 24);
		
		if ($timeInDays > $GEN_RUN_CYCLE_THRESHOLD){
			System_Daemon::log(System_Daemon::LOG_INFO, "Unit : $radio_unit_serial has not had a power cycle in $timeInDays days" );
			//Check if there is an open ticket for unit and event
			$open_ticket = isTicketOpenForEvent($dbconfig, $radio_unit_serial, 11);
			if (!$open_ticket){								//IF not ticket, open one
				$notification_time = date("Y-m-d H:i:s");
				$partner_log_id = "FS".date("YmdHis");
				$ticket_status = "New";
				$critical_flag = 1;
				$event_id = 11;
				
				$ticket_data = openRepairShoprAlarmTicket($dbconfig, $notification_time, $event_id,  $partner_log_id, $radio_unit_serial, $ticket_status);
				if (isset($ticket_data->error))
					System_Daemon::log(System_Daemon::LOG_ERR, "ERROR getting ticket data" . $ticket_data->error);
				else{
					$ticket_id = $ticket_data->ticket->id;
					$ticket_num = $ticket_data->ticket->number;

					logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);	
					insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, $critical_flag, $ticket_status);
					setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
					logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag);
				}
			}
			else 
				System_Daemon::log(System_Daemon::LOG_INFO, "Unit : $radio_unit_serial No power cycle ticket ". $open_ticket['repairshpr_ticket_number'] . "already opened." );
		}	

		sleep (2); 	//Wait 1 second not to flood API.

	}
*/
	
	//After 24 hours running need to know if condition still exists
/*	$event_id = 11;
	$events = getEventsByUnresolvedTickets($dbconfig, $event_id);
	
	foreach ($events as $event){
		$time_diff = 24 //$event['last_aapower_notif']
		if ($time_diff > $GEN_RUN_CYCLE_THRESHOLD_RESOLVED )
		
	}
	
	//	Signal 7 and 9 if condtions exists  Send every 12 hours
	$event_ids = array(7,9);
	foreach ($event_ids as $event_id){
		$events = getEventsByUnresolvedTickets($dbconfig, $event_id);
		foreach ($events as $event){
			$time = 12
			if ($event){
				sendSMTPMail($subject, $message, $username, $password, $host, $port, $recipient);
				updateAAPNotif($dbconfig);
		}
		
	}

	
*/
	
	
	//	Signal 8 and 10 Notify if tech hasn't closed after 36 hours
	$event_ids = array(8,10);
	foreach ($event_ids as $event_id){
		$events = getEventsByUnresolvedTickets($dbconfig, $event_id);
		if(sizeof($events)){
			System_Daemon::log(System_Daemon::LOG_INFO, "There are events to check for $event_id");
			foreach ($events as $event){
				if ($event['notification_time'] > $CLOSE_TICKET_EXPIRATION_TIME){
//					sendSMTPMail($subject, $message, $username, $password, $host, $port, $recipient);
//					updateAAPNotif($dbconfig);
					System_Daemon::log(System_Daemon::LOG_INFO,  "Event Id : " . $event['id'] . "has been longer than " . $CLOSE_TICKET_EXPIRATION_TIME);
				}
			}
		}
		sleep(1);
		
	}
	
	
	
	
	sleep(5);
	
	
} // end while




function getEventsByUnresolvedTickets($dbconfig, $event_id){

	$sql = "SELECT *
			FROM radio_events
			WHERE event_id = $event_id
			AND repairshpr_ticket_status != 'Resolved';";
		
		
echo $sql;			
	$result = mysqli_fetch_array(mysqli_query($dbconfig, $sql));
	
	print_r($result);
	return $result;		
}


function getUnresolvedTicketsEvents($dbconfig){
	
	$sql = "SELECT *
			FROM radio_events
			WHERE repairshpr_ticket_status not in ('Resolved')
			AND repairshpr_ticket_status IS NOT NULL";
	
	$result = getMySQLResultArray($sql, $dbconfig);
	
	return $result;
	
}
   
function isTicketOpenForEvent($dbconfig, $radio_unit_serial, $event_id){
	$sql = "SELECT *
			FROM radio_events
			WHERE radio_unit_serial = $radio_unit_serial
			AND event_id = $event_id
			AND repairshpr_ticket_status != 'Resolved'";

	$result = mysqli_query($dbconfig,$sql) or trigger_error(mysql_error()." ".$sql);	

	$num_rows = mysqli_num_rows($result);
	
	if ($num_rows)
		return $num_rows;	
	else 
		return 0;
}

function logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time){
	System_Daemon::log(System_Daemon::LOG_INFO, "Ticket created with status: $ticket_status ID: $ticket_id Ticket Num $ticket_num for System Serial $radio_unit_serial for event ID $event_id at $notification_time");	
}

function logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag){
	System_Daemon::log(System_Daemon::LOG_INFO, "System $radio_unit_serial critical flag set to $critical_flag");
}



System_Daemon::stop();


?>