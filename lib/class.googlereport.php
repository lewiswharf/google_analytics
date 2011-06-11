<?php

	require_once(EXTENSIONS . '/google_analytics/lib/class.googleanalytics.php');

	class GoogleReport extends GoogleAnalytics {
		
		protected $settings;
		protected $url;
		
		function __construct(&$token, $settings) {
			parent::__construct($token);
			$this->settings = $settings;
		}
		
		public function getReport() {
			$this->buildUrl();
			return $this->httpRequest();
		}
		
		private function buildUrl() {
			$this->url = 'https://www.google.com/analytics/feeds/data';
			$this->url .= '?' . http_build_query($this->settings);
		}
	}
	
?>
