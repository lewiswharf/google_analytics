<?php

  class GoogleAnalytics {		
	
		protected $token;
		protected $url;
		
		function __construct($token) {
			$this->token = $token;
		}
	
		public function httpRequest() {
			$ch = curl_init();
						
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //CURL doesn't like google's cert
			curl_setopt($ch, CURLOPT_HTTPHEADER,$this->getHeaders());
			
			$response = curl_exec($ch);

			curl_close($ch);
			
			return $response;
		}
		
		public function authSubSessionToken() {
			$this->url = 'https://www.google.com/accounts/AuthSubSessionToken';
			$response =	$this->httpRequest();
			if (preg_match("/Token=(.*)/", $response, $matches)) {
				$session_token = $matches[1];
			} else {
				return false;
			}

			return $session_token;
		}
		
		private function getHeaders() {
			return $headers = array(
			  sprintf("Authorization: AuthSub token=\"%s\"/n", $this->token)
			);
		}
	}
		
?>
