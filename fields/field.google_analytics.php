<?php
	
	Class fieldgoogle_analytics extends Field{
		
		public function __construct(&$parent){
			parent::__construct($parent);
			$this->_name = __('HTML Panel');
			$this->_driver = $this->_engine->ExtensionManager->create('html_panel');
		}

		function displaySettingsPanel(&$wrapper, $errors=NULL){
			parent::displaySettingsPanel($wrapper, $errors);

			$label = Widget::Label(__('URL Expression'));
			$label->appendChild(Widget::Input('fields['.$this->get('sortorder').'][url_expression]', $this->get('url_expression')));
			$wrapper->appendChild($label);
									
		}
		
		public function processRawFieldData($data, &$status, $simulate=false, $entry_id=null) {
			$status = self::__OK__;
			return array(
				'handle' => Lang::createHandle($data),
				'value' => $data
			);
		}
		
		public function commit() {
			if (!parent::commit()) return false;
			
			$id = $this->get('id');
			$handle = $this->handle();
			
			if ($id === false) return false;
			
			$fields = array(
				'field_id'			=> $id,
				'url_expression'	=> $this->get('url_expression')
			);
			
			Symphony::Database()->query("
				DELETE FROM
					`tbl_fields_{$handle}`
				WHERE
					`field_id` = '{$id}'
				LIMIT 1
			");
			
			return Symphony::Database()->insert($fields, "tbl_fields_{$handle}");
		}
		
		function displayPublishPanel(&$wrapper, $data=NULL, $flagWithError=NULL, $fieldnamePrefix=NULL, $fieldnamePostfix=NULL){
			
			// work out what page we are on, get portions of the URL
			$callback = Administration::instance()->getPageCallback();
			$entry_id = $callback['context']['entry_id'];
			
			// get an Entry object for this entry
			$entryManager = new EntryManager(Administration::instance());
			$entries = $entryManager->fetch($entry_id);
			
			if (is_array($entries)) $entry = reset($entries);
			
			// parse dynamic portions of the HTML Panel URL
			$url = $this->parseExpression($entry, $this->get('url_expression'));
			if (!preg_match('/^http/', $url)) $url = URL . $url;

		}
		
		//  from HTML Panel Field
		private function parseExpression($entry, $expression) {
		
			$xpath = $this->getXPath($entry);			
			$replacements = array();			
			preg_match_all('/\{[^\}]+\}/', $expression, $matches);
			
			foreach ($matches[0] as $match) {
				$results = @$xpath->query(trim($match, '{}'));				
				if ($results->length) {
					$replacements[$match] = $results->item(0)->nodeValue;
				} else {
					$replacements[$match] = '';
				}
			}
			
			$value = str_replace(
				array_keys($replacements),
				array_values($replacements),
				$expression
			);
			
			return $value;
		}
		
		//  from HTML Panel Field
		private function getXPath($entry) {
			
			if (!$entry instanceOf Entry) return new DOMXPath(new DOMDocument());
			
			$entry_xml = new XMLElement('entry');
			$section_id = $entry->get('section_id');
			$data = $entry->getData();			
			$fields = array();

			$entry_xml->setAttribute('id', $entry->get('id'));

			$associated = $entry->fetchAllAssociatedEntryCounts();

			if (is_array($associated) and !empty($associated)) {
				foreach ($associated as $section => $count) {
					$handle = Symphony::Database()->fetchVar('handle', 0, "
						SELECT
							s.handle
						FROM
							`tbl_sections` AS s
						WHERE
							s.id = '{$section}'
						LIMIT 1
					");

					$entry_xml->setAttribute($handle, (string)$count);
				}
			}
			
			$fm = new FieldManager(Symphony::Engine());

			foreach ($data as $field_id => $values) {
				if (empty($field_id)) continue;

				$field =& $fm->fetch($field_id);
				$field->appendFormattedElement($entry_xml, $values, false, null);
			}

			$xml = new XMLElement('data');
			$xml->appendChild($entry_xml);

			$dom = new DOMDocument();
			$dom->loadXML($xml->generate(true));

			return new DOMXPath($dom);
		}
						
	}