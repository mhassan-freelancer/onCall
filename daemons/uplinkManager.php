#!/usr/bin/php

<?php

require_once('System/Daemon.php');
date_default_timezone_set('America/New_York');

$options = array(
    'appName' => 'uplinkmgrd',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Polls the numerex SOAP server for Uplink Units / Alerts',
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

$SOAP_URL = getConfigParameter($dbconfig, "UPLINK_DAEMON", "UPLINK_URL");
$SOAP_USER = getConfigParameter($dbconfig, "UPLINK_DAEMON", "UPLINK_USER");
$SOAP_PW = getConfigParameter($dbconfig, "UPLINK_DAEMON", "UPLINK_PW");
$POLL_PERIOD = getConfigParameter($dbconfig, "UPLINK_DAEMON", "POLL_PERIOD");
$LAST_POLL = getConfigParameter($dbconfig, "UPLINK_DAEMON", "LAST_POLL");
$TIMEZONE = getConfigParameter($dbconfig, "UPLINK_DAEMON", "TIMEZONE");





$client = new SoapClient($SOAP_URL);

//Load any new radios units on start
$response = getActiveUnitsSoapCall($dbconfig, $client, $SOAP_URL, $SOAP_USER, $SOAP_PW);
processActiveUnits($dbconfig, $response);

//range to use on startup initial pass
$starttime = str_replace(" ", "T",trim($LAST_POLL));
$stoptime = date('Y-m-d\TH:i:s', strtotime("+1 sec"));

while(true){
	
	//Check If there are different counts between DB and WS
	//If different load them
	$wsRadioUnitCount = sizeof(getActiveUnitsSoapCall($dbconfig, $client, $SOAP_URL, $SOAP_USER, $SOAP_PW));	
	$dbRadioUnitCount = getDBTotalRadioUnits($dbconfig);
	updateConfigParameter($dbconfig, "UPLINK_DAEMON", "LAST_TOTAL_UNITS", $dbRadioUnitCount);
	
	if ( $dbRadioUnitCount != $wsRadioUnitCount){
		$response = getActiveUnitsSoapCall($dbconfig, $client, $SOAP_URL, $SOAP_USER, $SOAP_PW);
		processActiveUnits($dbconfig, $response);

	}	
	
	//Get all radio unit serial numbers from DB
	$serials = getRadioUnitSerials($dbconfig);
	
	//Check each unit for notifications and process them
	foreach($serials as $serial){
		$serial = $serial['serial'];
		$response = getUnitNotificationsSoapCall($dbconfig, $client, $SOAP_URL, $SOAP_USER, $SOAP_PW, $starttime, $stoptime, $serial);	
		processUnitNotifications($dbconfig, $response, $serial);
	
	}
	
	$starttime = date('Y-m-d\TH:i:s', strtotime("-$POLL_PERIOD sec"));
	$stoptime = date('Y-m-d\TH:i:s', strtotime("+1 sec"));
	sleep($POLL_PERIOD);
}

function handleRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial){
	System_Daemon::log(System_Daemon::LOG_INFO, "Event ID: $event_id for Unit $radio_unit_serial at $notification_time\n");		 
	switch($event_id){
		case 1:				//UTILITY POWER FAIL DETECTED - Open ticket, System Critical
			$ticket_status = "New";
			$critical_flag = 1;
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
			break;
			
		case 2:				//UTILITY POWER RESTORED
			$ticket_status = "Resolved";
			$critical_flag = 0;
			//Get the last ticket id of event 1 for this unit
			$ticket_data = getLastEventTicketData($dbconfig, $radio_unit_serial, 1);			
			if (!isset($ticket_data['repairshpr_ticket_id']))
				logNoTicketFound($radio_unit_serial, $event_id, $notification_time);
			else {
				$ticket_id = $ticket_data['repairshpr_ticket_id'];
				$ticket_num = $ticket_data['repairshpr_ticket_number'];
				
				updateRepairShoprAlarmTicket($dbconfig, $ticket_id, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_status);		
				setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
				logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);
				logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag);
			}	
			insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, $critical_flag, $ticket_status);
			break;
			
		case 3:				//GENERATOR RUNNING
		case 5:				//ATS SIGNAL SENT
		case 6:				//ATS SIGNAL TO UTILITY
			$ticket_status = "Resolved";
			$ticket_data = openRepairShoprAlarmTicket($dbconfig, $notification_time, $event_id,  $partner_log_id, $radio_unit_serial, $ticket_status);
			if (isset($ticket_data->error))
				System_Daemon::log(System_Daemon::LOG_ERR, "ERROR getting ticket data " . $ticket_data->error);
			else{
				$ticket_id = $ticket_data->ticket->id;
				$ticket_num = $ticket_data->ticket->number;
			
				logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);	
			
				insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, 1, $ticket_status);
			}
			break;	
		case 4:				//GENERATOR STOPPED
			$powerFailedThreshold = getConfigParameter($dbconfig, "UPLINK_DAEMON", "POWER_FAIL_THRESHOLD");
			$timeSinceLastPowerFailed=getTimeSinceLastRadioEvent($dbconfig, $radio_unit_serial, 1);			
			if(isset($timeSinceLastPowerFailed) && $timeSinceLastPowerFailed > $powerFailedThreshold){	//Assumed going through testing cycle
				System_Daemon::log(System_Daemon::LOG_INFO, "Unit $radio_unit_serial : Assumed going through test cycle.  Last Power Fail was $timeSinceLastPowerFailed minutes ago, more than $powerFailedThreshold minutes ago");
				$ticket_status = "Resolved";
				$ticket_data = openRepairShoprAlarmTicket($dbconfig, $notification_time, $event_id,  $partner_log_id, $radio_unit_serial, $ticket_status);
				if (isset($ticket_data->error))
					System_Daemon::log(System_Daemon::LOG_ERR, "ERROR getting ticket data" . $ticket_data->error);
				else{
					$ticket_id = $ticket_data->ticket->id;
					$ticket_num = $ticket_data->ticket->number;
			
					logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);	
			
					insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, 0, $ticket_status);
				}
			}
			else { //Not going through test cycle
				System_Daemon::log(System_Daemon::LOG_INFO, "Unit $radio_unit_serial : Not going through test cycle. Last Power Fail was $timeSinceLastPowerFailed minutes ago");
				$powerRestoredThreshold = getConfigParameter($dbconfig, "UPLINK_DAEMON", "POWER_RESTORE_THRESHOLD");
				$timeSinceLastPowerRestored=getTimeSinceLastRadioEvent($dbconfig, $radio_unit_serial, 2);
				if (isset($timeSinceLastPowerRestored) && $timeSinceLastPowerRestored <= $powerRestoredThreshold ){ //Power restored
					System_Daemon::log(System_Daemon::LOG_INFO, "Unit $radio_unit_serial : Generator Stopped correctly. Power was restored $timeSinceLastPowerRestored minutes ago.  Logging event for customer.");
					$ticket_status = "Resolved";
					$ticket_data = openRepairShoprAlarmTicket($dbconfig, $notification_time, $event_id,  $partner_log_id, $radio_unit_serial, $ticket_status);
					if (isset($ticket_data->error))
						System_Daemon::log(System_Daemon::LOG_ERR, "ERROR getting ticket data" . $ticket_data->error);
					else{
						$ticket_id = $ticket_data->ticket->id;
						$ticket_num = $ticket_data->ticket->number;
			
						logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);	
			
						insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, 1, $ticket_status);
						setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, 1);

					}
				}
				else { //Power did not come back up and the generator stopped
					System_Daemon::log(System_Daemon::LOG_INFO, "Unit $radio_unit_serial : Power was not recently restored and generator stopped.");
					$ticket_status = "New";
					$critical_flag = 1;
					$ticket_data = openRepairShoprAlarmTicket($dbconfig, $notification_time, $event_id,  $partner_log_id, $radio_unit_serial, $ticket_status);
					if (isset($ticket_data->error))
						System_Daemon::log(System_Daemon::LOG_ERR, "ERROR getting ticket data" . $ticket_data->error);
					else{
						$ticket_id = $ticket_data->ticket->id;
						$ticket_num = $ticket_data->ticket->number;
					
						logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);	
					
						insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, $critical_flag, $ticket_status);
						setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
					}
					setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
					logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag);
					
				
					$event_text = getEventById($dbconfig, $event_id);
					$message = "Unit $radio_unit_serial registered a $event_text at $notification_time.";
					if (isset($ticket_num))
						$message .= "Ticket # $ticket_num was opened in RepairShopr";
					
					//sendSMTPMail($subject, $message, $username, $password, $host, $port, $recipient);
				}
							
			}
			break;
		case 7:				//GENERAL TROUBLE / ALARM
		case 9:				//LOW VOLTAGE DETECTED
			$ticket_status = "New";
			$critical_flag = 1;
			$ticket_data = openRepairShoprAlarmTicket($dbconfig, $notification_time, $event_id,  $partner_log_id, $radio_unit_serial, $ticket_status);
			if (isset($ticket_data->error))
				System_Daemon::log(System_Daemon::LOG_ERR, "ERROR getting ticket data" . $ticket_data->error);
			else{
				$ticket_id = $ticket_data->ticket->id;
				$ticket_num = $ticket_data->ticket->number;
			
				logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);	
			
				insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, $critical_flag, $ticket_status);
				setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
			}
			setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
			logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag);
			
			$event_text = getEventById($dbconfig, $event_id);
			$message = "Unit $radio_unit_serial registered a $event_text at $notification_time.";
			if (isset($ticket_num))
				$message .= "Ticket # $ticket_num was opened in RepairShopr";
			
			$subject = "$event_text for $radio_unit_serial";
				
			//sendSMTPMail($subject, $message, $username, $password, $host, $port, $recipient);

			
			break;
		case 8:				//GENERAL TROUBLE / ALARM CLEARED
			$ticket_status = "In Progress";
			$critical_flag = 0;
			//Get the last ticket id of event 7 for this unit
			$ticket_data = getLastEventTicketData($dbconfig, $radio_unit_serial, 7);			
			if (!isset($ticket_data['repairshpr_ticket_id']))
				logNoTicketFound($radio_unit_serial, $event_id, $notification_time);
			else {
				$ticket_id = $ticket_data['repairshpr_ticket_id'];
				$ticket_num = $ticket_data['repairshpr_ticket_number'];
				
				updateRepairShoprAlarmTicket($dbconfig, $ticket_id, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_status);		
				setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
				logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);
				logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag);
			}	
			insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, $critical_flag, $ticket_status);
			
			break;		
		case 10:			//ATS SIGNAL SENT
			$ticket_status = "In Progress";
			$critical_flag = 0;
			//Get the last ticket id of event 7 for this unit
			$ticket_data = getLastEventTicketData($dbconfig, $radio_unit_serial, 9);			
			if (!isset($ticket_data['repairshpr_ticket_id']))
				logNoTicketFound($radio_unit_serial, $event_id, $notification_time);
			else {
				$ticket_id = $ticket_data['repairshpr_ticket_id'];
				$ticket_num = $ticket_data['repairshpr_ticket_number'];
				
				updateRepairShoprAlarmTicket($dbconfig, $ticket_id, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_status);		
				setRadioUnitCriticalFlag($dbconfig, $radio_unit_serial, $critical_flag);
				logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time);
				logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag);
			}	
			insertRadioEvent($dbconfig, $notification_time, $event_id, $partner_log_id, $radio_unit_serial, $ticket_id, $ticket_num, $critical_flag, $ticket_status);
			break;
		default:			//code to be executed if n is different from all labels;
			System_Daemon::log(System_Daemon::LOG_INFO, "Event ID: $event_id is not handled");
	}	
	
}


function processUnitNotifications($dbconfig, $response, $serial){
	if (isset($response->GetUnitEmailNotificationsResult->EmailNotificationData2) 
	  && sizeof($response->GetUnitEmailNotificationsResult->EmailNotificationData2) > 1){
	
		foreach ($response->GetUnitEmailNotificationsResult->EmailNotificationData2 as $event){

			$partner_log_id = $event->dfLogID;
			$notif_time = str_replace("T", " ", $event->dtStart);
			$event_id = getEventId($dbconfig, trim($event->sBody));
			$radio_unit_serial = $event->sSerialNo;
			
			handleRadioEvent($dbconfig, $notif_time, $event_id, $partner_log_id, $radio_unit_serial);		
		}
	}
	else if (isset($response->GetUnitEmailNotificationsResult->EmailNotificationData2) 
	  && sizeof($response->GetUnitEmailNotificationsResult->EmailNotificationData2) == 1){

		foreach ($response->GetUnitEmailNotificationsResult as $event){	
			$partner_log_id = $event->dfLogID;
			$notif_time = str_replace("T", " ", $event->dtStart);
			$event_id = getEventId($dbconfig, trim($event->sBody));
			$radio_unit_serial = $event->sSerialNo;
			
			handleRadioEvent($dbconfig, $notif_time, $event_id, $partner_log_id, $radio_unit_serial);
		}
	}

	
}


function getUnitNotificationsSoapCall($dbconfig, $client, $SOAP_URL, $SOAP_USER, $SOAP_PW, $starttime, $stoptime, $serial){
	
	$soapParameters = Array(
							'DealerUserName' => $SOAP_USER, 
							'DealerPassword' => $SOAP_PW, 
							'ErrorCode' => 0, 
							'StartDateTime' => $starttime, 
							'StopDateTime' => $stoptime, 
							'SerialNo' => $serial);
	
	try {		
		$response = $client->GetUnitEmailNotifications($soapParameters);
		updateConfigParameter($dbconfig, "UPLINK_DAEMON", "LAST_POLL", date('Y-m-d H:i:s'));
	}
	catch (Exception $e){
	   echo "Error!";
	   echo $e -> getMessage ();
	   echo 'Last response: '. $client->__getLastResponse();
	}
	
	return $response;
		
}

function processActiveUnits($dbconfig, $response){
	
	foreach ($response->GetActiveUnitsResult as $unit){
		$serial = $unit->sSerialNo;
		$unit_name = $unit->sUnitName;
		insertRadioUnit($dbconfig, $serial, $unit_name);
	}

}

function logTicketCreateInfo($ticket_status, $ticket_id, $ticket_num, $radio_unit_serial, $event_id, $notification_time){
	System_Daemon::log(System_Daemon::LOG_INFO, "Ticket created with status: $ticket_status ID: $ticket_id Ticket Num $ticket_num for System Serial $radio_unit_serial for event ID $event_id at $notification_time \n");	
}

function logSetRadioUnitCriticalFlag($radio_unit_serial, $critical_flag){
	System_Daemon::log(System_Daemon::LOG_INFO, "System $radio_unit_serial critical flag set to $critical_flag");
}

function logNoTicketFound($radio_unit_serial, $event_id, $notification_time){
	System_Daemon::log(System_Daemon::LOG_ALERT, "No ticket found to update for unit serial : $radio_unit_serial with event $event_id occurring at $notification_time \n");
}

function getActiveUnitsSoapCall($dbconfig, $client, $SOAP_URL, $SOAP_USER, $SOAP_PW){

	$soapParameters = Array(
		'DealerUserName' => $SOAP_USER, 
		'DealerPassword' => $SOAP_PW, 
		'ErrorCode' => 0) ;

	try {	
		$response = $client->GetActiveUnits($soapParameters);
	}
	catch (Exception $e){
	   echo "Error!";
	   echo $e -> getMessage ();
	   echo 'Last response: '. $client->__getLastResponse();
	}
	
	return $response;
	
}

System_Daemon::stop();
?>