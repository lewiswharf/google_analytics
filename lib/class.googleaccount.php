<?php

	require_once(EXTENSIONS . '/google_analytics/lib/class.googleanalytics.php');
	
	class GoogleAccount extends GoogleAnalytics {
		
		protected $settings;
		protected $url;
		
		function __construct(&$token, $settings) {
			parent::__construct($token);
			$this->settings = $settings;			
		}
		
		public function getAccounts() {
			return $this->httpRequest($this->buildUrl());
		}
		
		private function buildUrl() {
			$this->url = 'https://www.google.com/analytics/feeds/accounts/default';
			$this->url .= '?' . http_build_query($this->settings);
		}
	}
		
?>
