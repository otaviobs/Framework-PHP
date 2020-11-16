<?php
require __DIR__ . '/vendor/autoload.php';
require_once '../config/constants.php';

use Jumbojett\OpenIDConnectClient;
class Authentication {
	public function __construct() {
		/* $authendpoint = 'https://prepiam.toronto.ca.ibm.com/idaas/oidc/endpoint/default/authorize';
		$clientid = 'ODQ1MzU5YTYtMGU4OS00';
		$clientsecret = 'NWI5NDAzOWItYTA0ZC00';
		$tokenendpoint = 'https://prepiam.toronto.ca.ibm.com/idaas/oidc/endpoint/default/token';
		$jwksendpoint = "https://w3id.sso.ibm.com/isam/jwks.json"; */
		
		$authendpoint = AUTHENDPOINT;
		$clientid = CLIENTID;
		$clientsecret = CLIENTSECRET;
		$tokenendpoint = TOKENENDPOINT;
		$jwksendpoint = "https://w3id.sso.ibm.com/isam/jwks.json";
		
		$oidc = new OpenIDConnectClient ( $tokenendpoint, $clientid, $clientsecret );
		$oidc->addAuthParam ( 'authorization_endpoint' );
		$array = array (
				"token_endpoint" => $tokenendpoint 
		);
		$oidc->providerConfigParam ( $array );
		$array = array (
				"token_endpoint_auth_methods_supported" => "client_secret_basic" 
		);
		$oidc->providerConfigParam ( $array );
		$array = array (
				"jwks_uri" => $jwksendpoint 
		);
		$oidc->providerConfigParam ( $array );
		$oidc->addScope ( 'openid' );
		$oidc->addAuthParam ( 'token_endpoint' );
		$oidc->addAuthParam ( 'userinfo_endpoint' );	
		
		$oidc->setRedirectURL ( CALLBACK_URL );
		$status = $oidc->authenticate ();

		if($status){
			if(!isset($_SESSION)) {
				session_start();
			}
			$claim = $oidc->getVerifiedClaims();
			$_SESSION['authCall']= $claim;

    		header ( "location:".AUTHENTICATE_URL );
		}else header ( "location:".AUTHENTICATE_URL);
	}
}
$a = new Authentication ();

?>