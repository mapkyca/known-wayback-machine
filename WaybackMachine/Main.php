<?php

namespace IdnoPlugins\WaybackMachine {

    class Main extends \Idno\Common\Plugin {

	function registerPages() {
	    // Register admin settings
	    \Idno\Core\site()->addPageHandler('admin/waybackmachine', '\IdnoPlugins\WaybackMachine\Pages\Admin');

	    // Add menu items to account & administration screens
	    \Idno\Core\site()->template()->extendTemplate('admin/menu/items', 'admin/WaybackMachine/menu');
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
			try {

			    if ($enabled) {
				\Idno\Core\Idno::site()->logging()->debug("Archiving object URL");
				Client::saveURL($object->getUrl());

				// See if this is a saved link
				if ($savebookmarks) {
				    if (filter_var($object->body, FILTER_VALIDATE_URL)) {
					\Idno\Core\Idno::site()->logging()->debug("Contact body appears to be a URL");
					Client::saveURL($object->body);
				    }
				}
			    }
			} catch (\Exception $e) {
			    \Idno\Core\site()->logging()->error($e->getMessage());
			}
		    }
		}
	    };

	    \Idno\Core\Idno::site()->addEventHook('saved', $function);
	    \Idno\Core\Idno::site()->addEventHook('updated', $function);
	}

    }

}
