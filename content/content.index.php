<?php

	require_once(TOOLKIT . '/class.administrationpage.php');
		
	Class contentExtensionGoogle_AnalyticsIndex extends AdministrationPage {
		
		const GA_ACCOUNT_DATA = 'https://www.google.com/analytics/feeds/accounts/default';
		const GA_REPORT_DATA = 'https://www.google.com/analytics/feeds/data';
				
		protected $_driver = null;

		function __construct(&$parent){
			parent::__construct($parent);
			
			$this->_driver = Symphony::ExtensionManager()->create('google_analytics');
		}	
		
		public function __viewIndex() {
			if($profile = $this->_driver->getProfile()) {
				
				$feed = self::GA_REPORT_DATA
				  . '?ids=' . $profile 
					. '&start-date=' . DateTimeObj::format('-1 month', 'Y-m-d') 
					. '&end-date=' . DateTimeObj::format('now', 'Y-m-d')
    			. '&dimensions=ga:date' 
    			. '&metrics=ga:visits,ga:pageviews' 
    			. '&sort=ga:date';
					
				$xml = $this->_driver->curlRequest($feed, $this->_driver->getSessionToken());
//				die($xml);
				$xsl = file_get_contents(EXTENSIONS . '/google_analytics/utilities/report.xsl');
				$output = new XMLElement("div", $this->_driver->transformDataFeedWithXSLT($xsl, $xml));
				$output->setAttribute("id", "ga-getprofiles");			
				
				$this->Form->appendChild($output);
			} else {
				redirect(URL . 'symphony/extension/google_analytics/index/getprofile/');
			}
		}
		
		public function __viewGetprofile() {
			
			$this->addStylesheetToHead(URL . '/extensions/google_analytics/assets/google_analytics.index.css', 'screen', 20002);
			$this->addScriptToHead(URL . '/extensions/google_analytics/assets/google_analytics.index.js', 20003);

			if(!$profile = $this->_driver->getProfile()) {
				
				$xml = $this->_driver->curlRequest(self::GA_ACCOUNT_DATA, $this->_driver->getSessionToken());
				$xsl = file_get_contents(EXTENSIONS . '/google_analytics/utilities/accounts.xsl');
				
				$output = new XMLElement("div", $this->_driver->transformDataFeedWithXSLT($xsl, $xml));
				$output->setAttribute("id", "ga-getprofiles");			
				
				$this->Form->appendChild($output);
			} else {
				redirect(URL . 'symphony/extension/google_analytics/');
			}
		}	
		
		public function __actionGetprofile() {
			$this->_driver->setProfile($_POST['google_analytics_profile']);
			redirect(URL . 'symphony/extension/google_analytics/');
		}
	}

?>