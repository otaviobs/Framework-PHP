<?php
require_once('common/accesscontrol.php');

if(!isset($_SESSION))
  session_start();

$_SESSION['user']->logOut();

?>