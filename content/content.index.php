<?php

	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(TOOLKIT . '/class.xsltprocess.php');
	
	Class contentExtensionGoogle_AnalyticsIndex extends AdministrationPage {
		
		const GA_ACCOUNT_DATA = 'https://www.google.com/analytics/feeds/accounts/default';
		const GA_REPORT_DATA = 'https://www.google.com/analytics/feeds/data';
		
		public $Proc;
		
		protected $_xml;
		protected $_xsl;
		protected $_uri = null;
		protected $_utilities = null;
		protected $_driver = null;

		function __construct(&$parent){
			parent::__construct($parent);
			
			$this->_uri = URL . '/symphony/extension/google_analytics';
			$this->_utilities = URL . '/extensions/google_analytics/utilities';
			$this->_driver = Symphony::ExtensionManager()->create('google_analytics');

			$this->Proc = new XsltProcess;
		}	
		
		public function setXML($xml, $isFile=false){
			$this->_xml = ($isFile ? file_get_contents($xml) : $xml);
		}

		public function setXSL($xsl, $isFile=false){
			$this->_xsl = ($isFile ? file_get_contents($xsl) : $xsl);
		}

		public function view() {
			$this->setXML($this->_driver->curlRequest(self::GA_ACCOUNT_DATA, $this->_driver->getSessionToken()));
			$this->setXSL($this->_utilities . '/test.xsl', true);
			
			$result = $this->Proc->process($this->_xml, $this->_xsl);
			
			$output = new XMLElement("div", $result);
			$output->setAttribute("id", "analytics");			
			
			$this->Form->appendChild($output);
		}		
	}

?>