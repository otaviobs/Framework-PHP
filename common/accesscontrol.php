<?php
## Redirects to login.php for non authorised users
## creates $db var with db connection
## includes all needed functions
############################### Include section ###############################
require_once("CommonFunctions.php");
require_once("dbcon.php");
require_once(dirname(__FILE__)."/../vendor/autoload.php");
// require_once("authenticate.php");

// filter all parameters
if(isset($_POST))
    $_POST = filter($_POST, array('html','sql','others'),'post');

if(isset($_GET))
    $_GET = filter($_GET, array('html','sql','others'),'get');

require_once("authenticate.php");
############################ End of Include section ###########################
?>