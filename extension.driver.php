<?php
	
	require_once(TOOLKIT . '/class.xsltprocess.php');

	class Extension_Google_Analytics extends Extension {
		
		private $requestError = null; 
				
		public function about() {
			return array(
				'name'			=> 'Google Analytics',
				'version'		=> '0.1',
				'release-date'	=> '2011-06-02',
				'author'		=> array(
					'name'			=> 'Mark Lewis',
					'website'		=> 'http://casadelewis.com',
					'email'			=> 'mark@casadelewis.com'
				)
			);
		}
		
		public function install() {
			return Symphony::Database()->query("
				CREATE TABLE `tbl_fields_google_analytics` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`field_id` int(11) unsigned NOT NULL,
				`url_expression` varchar(255) default NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `field_id` (`field_id`)
				) TYPE=MyISAM
			");
		}
		
		public function uninstall() {
			Symphony::Configuration()->remove('google_analytics');
			Administration::instance()->saveConfig();
			Symphony::Database()->query("DROP TABLE `tbl_fields_google_analytics`");
		}
		
		public function getSubscribedDelegates() {
			return array(
				array(
					'page' => '/backend/',
					'delegate' => 'ExtensionsAddToNavigation',
					'callback' => 'add_navigation'
				),
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'append_assets'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => '__addPreferences'
				),
				array(
					'page' => '/system/authors/',
					'delegate' => 'AddDefaultAuthorAreas',
					'callback' => 'author_default_section'
				)
			);
		}
		
	public function append_assets($context) {
		$page = $context['parent']->Page;
		$page->addStylesheetToHead(URL . '/extensions/google_analytics/assets/google_analytics.backend.css', 'screen', 993);
		$page->addScriptToHead(URL . '/extensions/google_analytics/assets/google_analytics.backend.js', 20004);
	}
	
		public function add_navigation($context) {
			$context['navigation'][] = array(
				'name' => __('Analytics'),
				'index' => '1',
				'children' => array(
					array(
						'link' => '/extension/google_analytics/',
						'name' => __('Analytics'),
						'visible' => 'yes'
					),
				),
			);
		}
		
		public function author_default_section($context) {
			$context['options'][] = array(
				'/extension/google_analytics/', //value
				($context['default_area'] == '/extension/google_analytics/'), //selected
				__('Analytics') // label
			);
		}
		
		public function __addPreferences($context) {
			$fieldset = new XMLElement('fieldset');
			$fieldset->setAttribute('class', 'settings');
			$fieldset->appendChild(new XMLElement('legend', 'Google Analytics'));
			
			if(Symphony::Configuration()->get('session_token', 'google_analytics') === null) {
				$anchor = Widget::Anchor('Link Google Analytics', 'https://www.google.com/accounts/AuthSubRequest?next=' . URL . '/symphony/extension/google_analytics/index/getprofiles/
																													&amp;scope=https://www.google.com/analytics/feeds/
																													&amp;secure=0&amp;session=1');
				$fieldset->appendChild($anchor);
				
			} elseif(Symphony::Configuration()->get('session_token', 'google_analytics') !== null) {
				$anchor = Widget::Anchor('Unlink Google Analytics', URL . '/symphony/extension/google_analytics/index/unlink/');
					$fieldset->appendChild($anchor);
			}

			$context['wrapper']->appendChild($fieldset);			
		}
		
		public function setSessionToken($session_token) {
				Symphony::Configuration()->set('session_token', $session_token, 'google_analytics');
				Administration::instance()->saveConfig();
		}
		
		public function setProfile($profile) {
				Symphony::Configuration()->set('profile', $profile, 'google_analytics');
				Administration::instance()->saveConfig();
		}
		
		public function getSessionToken() {
				return Symphony::Configuration()->get('session_token', 'google_analytics');
		}
		
		public function getProfile() {
				return Symphony::Configuration()->get('profile', 'google_analytics');
		}
		
		public function deleteSessionToken() {
			Symphony::Configuration()->remove('google_analytics');
			Administration::instance()->saveConfig();
		}

	}
	
?>