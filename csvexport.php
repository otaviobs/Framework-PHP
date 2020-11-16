<?php
ini_set('memory_limit', '812M');
require_once("common/accesscontrol.php");


## Check it from which page or function
if(!isset($_GET['type'])) {
    header("Location: manager.php");
    die();
}

$type = $_GET['type'];

if($type == 'accounts-report') {
	$accounts = Account::loadAll();
	if (count($accounts) == 0) {
		header("Location: manager.php");
		die();
	}
	
	## Set file name
	$filename = "PASIR Accounts";
	fileSetup($filename);
	
	## Set file content header
	echo "ID,NAME,SHORTCODE,CDIRID,BAC,CHIPID,CREATEDDATE,MODIFIEDDATE\n";
	
	foreach($accounts as &$item) {	

		## Set file content body
		echo $item['ID']
			.','.$item['NAME']
			.','.$item['SHORTCODE']
			.','.$item['CDIRID']
			.','.$item['BAC']
			.','.$item['CHIPID']
			.','.$item['CREATEDDATE']
			.','.$item['MODIFIEDDATE']
			."\n";
	}
	
}elseif($type == 'osclass-report'){
	
	$osclass = OSClass::loadAll();
	if (count($osclass) == 0) {
		header("Location: manager.php");
		die();
	}
	
	## Set file name
	$filename = "PASIR OSClass";
	fileSetup($filename);
	
	## Set file content header
	echo "ID,OSNAME,OSVERSION,OSPROVIDER,OOSDATE,CREATEDDATE,MODIFIEDDATE\n";
	
	foreach($osclass as &$item) {	

		## Set file content body
		echo $item['ID']
			.','.$item['OSNAME']
			.','.$item['OSVERSION']
			.','.$item['OSPROVIDER']
			.','.$item['OOSDATE']
			.','.$item['CREATEDDATE']
			.','.$item['MODIFIEDDATE']
			."\n";
	}
	
}elseif($type == 'mcproperties-report'){

	$mcproperties = MCProperties::loadAll();
	if (count($mcproperties) == 0) {
		header("Location: manager.php");
		die();
	}
	
	## Set file name
	$filename = "PASIR MCProperties";
	fileSetup($filename);
	
	## Set file content header
	echo "ID,MACHINECLASS,TAD,ARCHITECTURE,CREATEDDATE,MODIFIEDDATE\n";
	
	foreach($mcproperties as &$item) {

		## Set file content body
		echo $item['ID']
			.','.$item['MACHINECLASS']
			.','.$item['TAD']
			.','.$item['ARCHITECTURE']
			.','.$item['CREATEDDATE']
			.','.$item['MODIFIEDDATE']
			."\n";
	}
	
}elseif($type == 'quality-metrics-thresholds-report'){
	
	$quality_metrics = QualityMetrics::loadAll();
	if (count($quality_metrics) == 0) {
		header("Location: manager.php");
		die();
	}

	## Set file name
	$filename = "PASIR Quality Metrics Thresholds";
	fileSetup($filename);
	
	## Set file content header
	echo "ID,CREATEDDATE,UPDATEDDATE,MINCLEANSERVERRATIO,TARGETCLEANSERVERRATIO,MINCLEANSERVER,MINTICKETLINKAGERATIO,TARGETTICKETLINKAGERATIO,MINTICKETLINKAGE,MINLABELEDPROBLEMATICSERVERRATIO,MAXLABELEDPROBLEMATICSERVERRATIO,TARGETLABELEDPROBLEMATICSERVERRATIO,MINLABELEDPROBLEMATICSERVER,MINPREDICTEDPROBLEMATICSERVERRATIO,MAXPREDICTEDPROBLEMATICSERVERRATIO,TARGETPREDICTEDPROBLEMATICSERVERRATIO,MINPREDICTEDPROBLEMATICSERVER,MINSUTICKETSRATIO,TARGETSUTICKETS_RATIO,MINSUTICKETS,STARTDATE,ENDDATE,CREATED_BY\n";
	
	foreach($quality_metrics as &$item) {

		## Set file content body
		echo $item['ID']
		.','.$item['CREATED_DATE']
		.','.$item['UPDATED_DATE']
		.','.$item['MIN_CLEAN_SERVER_RATIO']
		.','.$item['TARGET_CLEAN_SERVER_RATIO']
		.','.$item['MIN_CLEAN_SERVER']
		.','.$item['MIN_TICKET_LINKAGE_RATIO']
		.','.$item['TARGET_TICKET_LINKAGE_RATIO']
		.','.$item['MIN_TICKET_LINKAGE']
		.','.$item['MIN_LABELED_PROBLEMATIC_SERVER_RATIO']
		.','.$item['MAX_LABELED_PROBLEMATIC_SERVER_RATIO']
		.','.$item['TARGET_LABELED_PROBLEMATIC_SERVER_RATIO']
		.','.$item['MIN_LABELED_PROBLEMATIC_SERVER']
		.','.$item['MIN_PREDICTED_PROBLEMATIC_SERVER_RATIO']
		.','.$item['MAX_PREDICTED_PROBLEMATIC_SERVER_RATIO']
		.','.$item['TARGET_PREDICTED_PROBLEMATIC_SERVER_RATIO']
		.','.$item['MIN_PREDICTED_PROBLEMATIC_SERVER']
		.','.$item['MIN_SU_TICKETS_RATIO']
		.','.$item['TARGET_SU_TICKETS_RATIO']
		.','.$item['MIN_SU_TICKETS']
		.','.$item['START_DATE']
		.','.$item['END_DATE']
		.','.$item['CREATED_BY']."\n";
	}
	
}else if($type == 'account-credits-report') {
	
	$account_credits = AccountCredits::loadAll();
	if (count($account_credits) == 0) {
		header("Location: manager.php");
		die();
	}
	
	## Set file name
	$filename = "PASIR Account Credits";
	fileSetup($filename);
	
	## Set file content header
	echo "ID,ANALYSIS_ID,CREATED_BY,CREATED_DATE,CANCEL_BY,CANCEL_DATE,STATUS,ACCOUNT_NAME,ANALYSIS_DATE,EXPIRATION_DATE,COMMENTS\n";
	
	foreach($account_credits as &$item) {

		## Set file content body
		echo $item['ID']
			.','.$item['ANALYSIS_ID']
			.','.$item['CREATED_BY']
			.','.$item['CREATED_DATE']
			.','.$item['CANCEL_BY']
			.','.$item['CANCEL_DATE']
			.','.$item['STATUS']
			.','.$item['ACCOUNT_NAME']
			.','.$item['ANALYSIS_DATE']
			.','.$item['EXPIRATION_DATE']
			.',"'.$item['COMMENTS'].'"'."\n";
			
	}
	
}else {
	redirect("common/message.php?type=Invalid request&title=Invalid request&body=Please, go back and try again.");
}

function fileSetup($filename = "download") {
	## Set up download file type
	header("Content-type:text/csv");
	## Set file name
	header("content-Disposition:filename=\"".$filename.".csv". "\"");
	header('Cache-Control:must-revalidate,post-check=0,pre-check=0'); 
	header('Expires:0'); 
	header('Pragma:public');
}
?>