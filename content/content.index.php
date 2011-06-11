<?php

	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(EXTENSIONS . '/google_analytics/lib/class.googleaccount.php');
	require_once(EXTENSIONS . '/google_analytics/lib/class.googlereport.php');
		
	Class contentExtensionGoogle_AnalyticsIndex extends AdministrationPage {
				
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
				
				$result = new XMLElement("div");
				
				$visits_graph_report = new GoogleReport(
					$this->_driver->getSessionToken(),
					array(
						'ids' => $profile,
						'start-date' => DateTimeObj::format('-1 month', 'Y-m-d'),
						'end-date' =>DateTimeObj::format('now', 'Y-m-d'),
						'dimensions' => 'ga:date',
						'metrics' => 'ga:visits,ga:pageviews',
						'sort' => 'ga:date'
					));
					
				$xml = $visits_graph_report->getReport();

				$xsl = file_get_contents(EXTENSIONS . '/google_analytics/utilities/report.chart.xsl');
				$output = new XMLElement("div", $this->transformDataFeedWithXSLT($xsl, $xml));
				
				$result->appendChild($output);

				$top_pages_report = new GoogleReport(
					$this->_driver->getSessionToken(),
					array(
						'ids' => $profile,
						'start-date' => DateTimeObj::format('-1 month', 'Y-m-d'),
						'end-date' =>DateTimeObj::format('now', 'Y-m-d'),
						'dimensions' => 'ga:pageTitle',
						'metrics' => 'ga:pageviews',
						'sort' => 'ga:pageviews'
					));
					
				$xml = $top_pages_report->getReport();

				$xsl = file_get_contents(EXTENSIONS . '/google_analytics/utilities/report.pages.xsl');
				$output = new XMLElement("div", $this->transformDataFeedWithXSLT($xsl, $xml));
				
				$result->appendChild($output);
				
				$top_pages_report = new GoogleReport(
					$this->_driver->getSessionToken(),
					array(
						'ids' => $profile,
						'start-date' => DateTimeObj::format('-1 month', 'Y-m-d'),
						'end-date' =>DateTimeObj::format('now', 'Y-m-d'),
						'dimensions' => 'ga:keyword',
						'metrics' => 'ga:visitors',
						'sort' => 'ga:visitors'
					));
					
				$xml = $top_pages_report->getReport();

				$xsl = file_get_contents(EXTENSIONS . '/google_analytics/utilities/report.keywords.xsl');
				$output = new XMLElement("div", $this->transformDataFeedWithXSLT($xsl, $xml));
				
				$result->appendChild($output);

				$this->Form->appendChild($result);
			} else {
				redirect(URL . '/symphony/extension/google_analytics/index/noprofile/');
			}
		}
		
		public function __viewNoprofile() {
			$result = new XMLElement("p");
			$result->appendChild(Widget::Anchor('You must link your Google Analytics account in preferences.', URL . '/symphony/system/preferences/'));
			$this->Form->appendChild($result);
		}
		
		public function __viewUnlink() {
			$this->_driver->deleteSessionToken();
			redirect('https://www.google.com/accounts');
		}
		
		public function __viewGetprofiles() {
			
			$this->addStylesheetToHead(URL . '/extensions/google_analytics/assets/google_analytics.index.css', 'screen', 20002);

			if(isset($_GET['token'])) {
				
				$auth = new GoogleAccount($_GET['token'], null);

				$this->_driver->setSessionToken($auth->authSubSessionToken());
				
				$profiles = new GoogleAccount(
					$this->_driver->getSessionToken(),
					array(
						'start-index' => 1,
						'max-results' => 500,
						'v' => 2
					));
					
				$xml = $profiles->getAccounts();
				
				$xsl = file_get_contents(EXTENSIONS . '/google_analytics/utilities/accounts.xsl');
				
				$result = new XMLElement("div", $this->transformDataFeedWithXSLT($xsl, $xml));
				$result->setAttribute("id", "ga-getprofiles");			
				
				$this->Form->appendChild($result);
			} else {
				redirect(URL . '/symphony/extension/google_analytics/iondex/noprofile/');
			}
		}	
		
		public function __actionGetprofiles() {
			$this->_driver->setProfile($_POST['google_analytics_profile']);
			redirect(URL . '/symphony/extension/google_analytics/');
		}

		public function transformDataFeedWithXSLT($xsl, $xml) {

			$Proc = new XsltProcess;
			$data = $Proc->process($xml, $xsl);

			return $data;
		}
	}

?>