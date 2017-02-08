<?php

namespace IdnoPlugins\WaybackMachine {

    class Main extends \Idno\Common\Plugin {

	function registerPages() {
	    // Register admin settings
	    \Idno\Core\site()->addPageHandler('admin/waybackmachine', '\IdnoPlugins\WaybackMachine\Pages\Admin');

	    // Add menu items to account & administration screens
	    \Idno\Core\site()->template()->extendTemplate('admin/menu/items', 'admin/WaybackMachine/menu');
	}
	
	/**
	 * Return an array of urls in text, or an empty string
	 * @param type $text
	 */
	private function getUrlsFromText($text) {
	    $return = [];

            if (preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $text, $match)) { // regex from wordpress 
		
                $return = $match[0];
            }

            return $return;
	}

	function registerEventHooks() {

	    $function = function(\Idno\Core\Event $event) {

		$enabled = true;
		$savebookmarks = true;
		if (!empty(\Idno\Core\site()->config()->waybackmachine)) {
			$enabled = \Idno\Core\site()->config()->waybackmachine['enabled'];
			$savebookmarks = \Idno\Core\site()->config()->waybackmachine['savebookmarks'];
		}

		$object = $event->data()['object'];

		if (!empty($object)) {

		    if (in_array(get_class($object), \Idno\Common\ContentType::getRegisteredClasses())) {

			$urls = [];

			// See if we need to save the new item
			if ($enabled) {
			    \Idno\Core\Idno::site()->logging()->debug("Archiving object URL");
			    $urls[] = $object->getUrl();
			}

			// See if this is a saved link
			if ($savebookmarks) {
			    
			    \Idno\Core\Idno::site()->logging()->debug("Looking for URLS in body and description");
			    
			    // Now see if the body or description contains urls
			    if (!empty($object->body))
				$urls = array_merge($urls, $this->getUrlsFromText($object->body));
				    
			    if (!empty($object->description))
				$urls = array_merge($urls, $this->getUrlsFromText($object->description));
			}

			if (!empty($urls)) {
			    
			    $urls = array_unique($urls);
			    
			    foreach ($urls as $url) {
				try {
				    Client::saveURL($url);
				} catch (\Exception $e) {
				    \Idno\Core\site()->logging()->error($e->getMessage());
				}
			    }
			}
		    }
		}
	    };

	    \Idno\Core\Idno::site()->addEventHook('saved', $function);
	    \Idno\Core\Idno::site()->addEventHook('updated', $function);
	}

    }

}
