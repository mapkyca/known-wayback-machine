<?php

namespace IdnoPlugins\WaybackMachine {

    class Client {

	/**
	 * Save a URL with the wayback machine.
	 * @param URL $url The url to save
	 */
	public static function saveURL($url) {

	    if (!filter_var($url, FILTER_VALIDATE_URL))
		throw new \RuntimeException("$url doesn't appear to be a valid URL");

	    \Idno\Core\Idno::site()->logging()->debug("Attempting to archive $url to the wayback machine.");

	    $result = \Idno\Core\Webservice::get("https://web.archive.org/save/" . $url);
	}

    }

}