<?php
require_once("configuration.php");
$database = Configuration::getConfig('pasir.db.name');
$hostname = Configuration::getConfig('pasir.db.hostname');
$port = Configuration::getConfig('pasir.db.port');
$user = Configuration::getConfig('pasir.db.username');
$password = Configuration::getConfig('pasir.db.password');
$cs="host=$hostname port=$port dbname=$database user=$user password=$password";

if (!$db = pg_connect ($cs)) {
	$type = "We will be back soon";
	$title = "Not access.";
	$body = "PASIR is currently down for maintenance";
	redirect("/common/message.php?type=".$type."&title=".$title."&body=".$body);
	die();
}
?>