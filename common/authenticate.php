<?php
require_once(dirname(__FILE__).('/../config/constants.php'));

if(!isset($_SESSION)) {
	session_start();
}

//Will enter this function only when user first login(callback page)
if(isset($_SESSION['authCall'])){
	
	$userClaim = $_SESSION['authCall'];
	unset($_SESSION['authCall']);

	if($clientId!=CLIENTID){
			$type = "warning";
			$title = "Not access.";
			$body = "Invalid request.";
			redirect("message.php?type=".$type."&title=".$title."&body=".$body);
			die();
	}
	else{
		try {
			$user = new User($emailId); // throws exception if fails
			$_SESSION["user"] = $user;
			redirect(urldecode(SOURCE_URL));
		}
		catch(Exception $e) {
				if ($e->getMessage() == -1){
						$type = "warning";
						$title = "Not authorized.";
						$body = $userClaim->name." is not yet a registered user. Please contact your PASIR representative for further support.";
				}else{
						if ($e->getMessage() == -2){
								$type = "error";
								$title = "Authorization failed.";
								$body = "Invalid user role.";
						}
						else{
								$type = "error";
								$title = "Authorization failed.";
								$body = "Please contact PASIR support.";
						}
				}
				
				redirect("message.php?type=".$type."&title=".$title."&body=".$body);
		}
	}	
}

//If user is not logged in, will redirect to w3ID login
if(!isset($_SESSION["user"])){
	die(redirect('index.php'));
}else{
	//Check if the user has passed 30 min
	$_SESSION["user"]->isLoggedIn();
}

?>