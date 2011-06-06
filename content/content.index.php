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

			$this->addStylesheetToHead(URL . '/extensions/google_analytics/assets/google_analytics.index.css', 'screen', 20002);
			$this->addScriptToHead('https://www.google.com/jsapi', 1);
			$this->addScriptToHead(URL . '/extensions/google_analytics/assets/google_analytics.index.js', 20004);

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
				$xsl = file_get_contents(EXTENSIONS . '/google_analytics/utilities/report.chart.xsl');
				$output = new XMLElement("div", $this->_driver->transformDataFeedWithXSLT($xsl, $xml));
				$output->setAttribute("id", "ga-index");			
				
				$feed2 = self::GA_REPORT_DATA
				  . '?ids=' . $profile 
					. '&start-date=' . DateTimeObj::format('-1 month', 'Y-m-d') 
					. '&end-date=' . DateTimeObj::format('now', 'Y-m-d')
    			. '&dimensions=ga:pageTitle' 
    			. '&metrics=ga:pageviews' 
    			. '&sort=-ga:pageviews';
					
				$xml2 = $this->_driver->curlRequest($feed2, $this->_driver->getSessionToken());
//				print_r($xml2);
				$xsl2 = file_get_contents(EXTENSIONS . '/google_analytics/utilities/report.pages.xsl');
				$output2 = new XMLElement("div", $this->_driver->transformDataFeedWithXSLT($xsl2, $xml2));
				
				$output->appendChild($output2);
				
				$feed3 = self::GA_REPORT_DATA
				  . '?ids=' . $profile 
					. '&start-date=' . DateTimeObj::format('-1 month', 'Y-m-d') 
					. '&end-date=' . DateTimeObj::format('now', 'Y-m-d')
    			. '&dimensions=ga:keyword' 
    			. '&metrics=ga:visitors' 
    			. '&sort=-ga:visitors';
					
				$xml3 = $this->_driver->curlRequest($feed3, $this->_driver->getSessionToken());
//				die($xml3);
				$xsl3 = file_get_contents(EXTENSIONS . '/google_analytics/utilities/report.keywords.xsl');
				$output3 = new XMLElement("div", $this->_driver->transformDataFeedWithXSLT($xsl3, $xml3));
				
				$output->appendChild($output3);

				$this->Form->appendChild($output);
			} else {
				redirect(URL . 'symphony/extension/google_analytics/index/getprofile/');
			}
		}
		
		public function __viewGetprofile() {
			
			$this->addStylesheetToHead(URL . '/extensions/google_analytics/assets/google_analytics.index.css', 'screen', 20002);

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