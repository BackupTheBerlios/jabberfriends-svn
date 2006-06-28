<?php
/*
-----------------------------------------------------
	Atom Feed Class
-----------------------------------------------------
	Author: Hannes John, jouhni@web.de
	Version: 1.1
-----------------------------------------------------
	Changelog:
		1.1 - Put htmlspecialchars() on title and subtitle for compliance with feedvalidator.org
		1.0 - Initial release
-----------------------------------------------------
*/
require_once("entry.class.inc");

// Constants for use in setEncoding()
define("ENCODING_UTF8", "utf-8");
define("ENCODING_ISO", "iso-8859-1");	// default

// Constants for use in setLanguage()
define("LANGUAGE_GERMAN", "de");		// default
define("LANGUAGE_ENGLISH", "en");

class AtomFeed {
	var $title, $subtitle, $url_home, $url_feed;
	var $entries;
	var $id;					// A unique value identifying this feed
	var $updated;				// The date the feed was updated. Value is set in generate() and depends on the date of the latest entry.
	var $rights = "";
	var $language = LANGUAGE_GERMAN;
	var $encoding = ENCODING_ISO;
	
	/**
	 * @param String title - Only pure text. "<" and ">" will be removed.
	 * @param String subtitle - Only pure text. "<" and ">" will be removed.
	 * @param String url_home - The URL of your homepage.
	 * @param String url_feed - The URL of your feed.
	 * @param String rights - Copyrights. Only pure text. "<" and ">" will be removed.
	 * */
	function AtomFeed($title, $url_feed, $url_home, $subtitle = "", $rights = "") {
		$this->title = htmlspecialchars(str_replace(">", "", str_replace("<", "", $title)));
		$this->subtitle = htmlspecialchars(str_replace(">", "", str_replace("<", "", $subtitle)));
		$this->url_home = $url_home;
		$this->url_feed = $url_feed;
		$this->rights = str_replace(">", "", str_replace("<", "", $rights));
		$this->entries = array();
		$this->id = $this->url_feed;
	}
	
	/**
	 * Sets the feed's title. Only pure text. "<" and ">" will be removed.
	 * @param String title
	 * */
	function setTitle($title) {
		$this->title = htmlspecialchars(str_replace(">", "", str_replace("<", "", $title)));
	}
	
	/**
	 * Sets the feed's subtitle. Only pure text. "<" and ">" will be removed.
	 * @param String subtitle
	 * */
	function setSubTitle($subtitle) {
		$this->subtitle = htmlspecialchars(str_replace(">", "", str_replace("<", "", $subtitle)));
	}
	
	/**
	 * Sets the URL of your homepage.
	 * @param String url_home
	 * */
	function setURLHome($url_home) {
		$this->url_home = $url_home;		
	}

	/**
	 * Sets the URL of your feed.
	 * @param String url_feed
	 * */
	function setURLFeed($url_feed) {
		$this->url_feed = $url_feed;		
	}
	
	/**
	 * Adds an entry to the feed.
	 * @param Entry entry
	 * */
	function addEntry($entry) {
		$this->entries[] = $entry;
	}	
	
	/**
	 * Sets the feed's language. E.g. "de" for german or "en" for english.
	 * The default value is "de".
	 * @param String language
	 * */
	function setLanguage($language) {
		$this->language = $language;		
	}
	
	/**
	 * Sets the feed's copyrights. Only pure text. "<" and ">" will be removed.
	 * The default value is "".
	 * @param String rights
	 * */
	function setRights($rights) {
		$this->rights = str_replace(">", "", str_replace("<", "", $rights));
	}
	
	/**
	 * Sets the feed's encoding.
	 * The default value is "iso-8859-1".
	 * @param String encoding
	 * */
	function setEncoding($encoding) {
		$this->encoding = $encoding;
	}
	
	/**
	 * Generates the feed.
	 * */
	function generate() {
		$generator = "PHP Atom Feed Class by Hannes John (jouhni@web.de)";
		$generatorUri = "http://www.jouhni.de.vu";
		$generatorVersion = "1.0";
		$this->updated = $this->getUpdated();
		if(strlen($this->subtitle) == 0)
			$subtitle = "";
		else 
			$subtitle = "<subtitle type=\"text\">".$this->subtitle."</subtitle>";
		if($this->updated == -1)
			$updated = "";
		else 
			$updated = "<updated>".date("Y-m-d", $this->updated)."T".date("H:i:s", $this->updated)."Z"."</updated>";
		if(strlen($this->url_home) == 0)
			$urlHome = "";
		else 
			$urlHome = "<link rel=\"alternate\" type=\"text/html\" href=\"".$this->url_home."\"/>";
		if(strlen($this->url_feed) == 0)
			$urlFeed = "";
		else 
			$urlFeed = "<link rel=\"self\" type=\"application/atom+xml\" href=\"".$this->url_feed."\"/>";
		if(strlen($this->rights) == 0)
			$rights = "";
		else 
			$rights = "<rights>".$this->rights."</rights>";
		header("content-type: application/atom+xml");
		echo "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>
		<feed xmlns=\"http://www.w3.org/2005/Atom\">
			<title type=\"text\">".$this->title."</title>
			".$subtitle."
			".$updated."
			<id>".$this->id."</id>
			".$urlHome."
			".$urlFeed."
			".$rights."
			<generator uri=\"".$generatorUri."\" version=\"".$generatorVersion."\">".$generator."</generator>
		";
		foreach($this->entries as $entry)
			$entry->generate();
		echo "</feed>";
	}
	
	/**
	 * Returns the date of the most actual entry or -1, if there are no entries.
	 * @return int updated - Unix timestamp
	 * */
	function getUpdated() {
		if(count($this->entries) == 0)
			return -1;
		else {
			$updated = 0;
			foreach($this->entries as $entry)
				if($entry->getUpdated() > $updated)
					$updated = $entry->getUpdated();
			return $updated;
		}
	}
}
?>