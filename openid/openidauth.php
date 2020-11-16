<?php
require __DIR__ . '/vendor/autoload.php';
require_once '../config/constants.php';

use Jumbojett\OpenIDConnectClient;

$o = new OpenIDAuth ();
class OpenIDAuth {
	static $oidc = null;
	public static function getAttributes() {
		return $oidc->getIdToken ();
	}
	public function __construct() {		
		/**
		 * Copyright MITRE 2012
		 *
		 * OpenIDConnectClient for PHP5
		 * Author: Michael Jett <mjett@mitre.org>
		 *
		 * Licensed under the Apache License, Version 2.0 (the "License"); you may
		 * not use this file except in compliance with the License. You may obtain
		 * a copy of the License at
		 *
		 * http://www.apache.org/licenses/LICENSE-2.0
		 *
		 * Unless required by applicable law or agreed to in writing, software
		 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
		 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
		 * License for the specific language governing permissions and limitations
		 * under the License.
		 */

		/* $authendpoint = 'https://prepiam.toronto.ca.ibm.com/idaas/oidc/endpoint/default/authorize';
		$clientid = 'ODQ1MzU5YTYtMGU4OS00';
		$clientsecret = 'NWI5NDAzOWItYTA0ZC00';
		$tokenendpoint = 'https://prepiam.toronto.ca.ibm.com/idaas/oidc/endpoint/default/token';
		$jwksendpoint = "https://w3id.sso.ibm.com/isam/jwks.json"; */
		
		$authendpoint = AUTHENDPOINT;
		$clientid = CLIENTID;
		$clientsecret = CLIENTSECRET;
		$tokenendpoint = TOKENENDPOINT;
		$jwksendpoint = JWKSENDPOINT;

		$oidc = new OpenIDConnectClient ( $authendpoint, $clientid, $clientsecret );
		$oidc->addAuthParam ( 'authorization_endpoint' );
		$oidc->addScope ( 'openid' );
		$oidc->addAuthParam ( 'userinfo_endpoint' );
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
		$array = array (
				"authorization_endpoint" => $authendpoint 
		);
		$oidc->providerConfigParam ( $array );


		/*try {
		    $token = $provider->getAccessToken('authorization_code', [
		        'code' => $_GET['code']
		    ]);
		} catch (Exception $e) {
		    $errors = $provider->getValidatorChain()->getMessages();
		    echo $e->getMessage();
		    var_dump($errors);
		    return;
		}
		$response = [
		    "Token: " . $token->getToken(),
		    "Refresh Token: ". $token->getRefreshToken(),
		    "Expires: ". $token->getExpires(),
		    "Has Expired: ". $token->hasExpired(),
		    "All Claims: ". print_r($token->getIdToken()->getClaims(), true)
		];
*/
		
		$oidc->setRedirectURL ( CALLBACK_URL );
		call_user_func ( $oidc->authenticate () );

	}
}
?>
