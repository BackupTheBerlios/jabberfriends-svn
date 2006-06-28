<?
// Constants for use in setLanguage()
define("LANGUAGE_GERMAN", "de");		// default
define("LANGUAGE_ENGLISH", "en");

class Entry {
	var $title;
	var $url;
	var $summary, $content;
	var $published;		// The date the entry was published.
	var $updated;		// The date the entry was updated.
	var $authorName, $authorURI = "", $authorEMail = "";
	var $id;			// A unique value identifying this entry
	var $language = LANGUAGE_GERMAN;

	/**
	 * @param String title - Only pure text. "<" and ">" will be removed.
	 * @param String url - The URL of this entry.
	 * @param int published - Unix timestamp
	 * @param int updated - Unix timestamp
	 * @param String authorName
	 * @param String summary - HTML is allowed.
	 * @param String content - HTML is allowed.
	 * @param String authorURI - The author's homepage
	 * @param String authorEMail - The author's email
	 * */
	function Entry($title, $url, $published, $updated, $authorName, $summary = "", $content = "", $authorURI = "", $authorEMail = "") {
		$this->title = htmlspecialchars(str_replace(">", "", str_replace("<", "", $title)));
		$this->url = $url;
		$this->authorName = $authorName;
		$this->published = $published;
		$this->updated = $updated;
		$this->summary = htmlspecialchars($summary);
		$this->content = htmlspecialchars($content);
		$this->authorURI = $authorURI;
		$this->authorEMail = $authorEMail;
		$this->id = $this->url;
	}
	
	/**
	 * Sets the entry's title. Only pure text. "<" and ">" will be removed.
	 * @param String title
	 * */
	function setTitle($title) {
		$this->title = htmlspecialchars(str_replace(">", "", str_replace("<", "", $title)));
	}
	
	/**
	 * Sets the entry's URL.
	 * */
	function setURL($url) {
		$this->url = $url;
	}
	
	/**
	 * Sets the entry's publishing date.
	 * The default value is the date the entry object was created.
	 * @param int published - Unix timestamp
	 * */
	function setPublished($published) {
		$this->published = $published;
	}
	
	/**
	 * Sets the date the entry was updated.
	 * @param int updated - Unix timestamp
	 * */
	function setUpdated($updated) {
		$this->updated = $updated;
	}
	
	/**
	 * Sets the entry's summary. HTML is allowed.
	 * @param String summary
	 * */
	function setSummary($summary) {
		$this->summary = htmlspecialchars($summary);
	}
	
	/**
	 * Sets the entry's content. HTML is allowed.
	 * @param String content
	 * */
	function setContent($content) {
		$this->content = htmlspecialchars($content);
	}
	
	/**
	 * Sets the author's name.
	 * @param String authorName
	 * */
	function setAuthorName($authorName) {
		$this->authorName = $authorName;
	}
	
	/**
	 * Sets the author's homepage URI.
	 * @param String authorURI
	 * */
	function setAuthorURI($authorURI) {
		$this->authorURI = $authorURI;		
	}
	
	/**
	 * Sets the author's email adress.
	 * @param String authorEMail
	 * */
	function setAuthorEMail($authorEMail) {
		$this->authorEMail = $authorEMail;
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
	 * Generates the entry.
	 * */
	function generate() {
		if(strlen($this->authorEMail) == 0)
			$authorEMail = "";
		else 
			$authorEMail = "<email>".$this->authorEMail."</email>";
		if(strlen($this->authorURI) == 0)
			$authorURI = "";
		else 
			$authorURI = "<uri>".$this->authorURI."</uri>";
		if(strlen($this->summary) == 0)
			$summary = "";
		else 
			$summary = "<summary type=\"html\">".$this->summary."</summary>";
		if(strlen($this->content) == 0)
			$content = "";
		else 
			$content = "<content type=\"html\" xml:lang=\"".$this->language."\">".$this->content."</content>";
		?>
		<entry>
			<title type="text"><? echo $this->title; ?></title>
			<link type="text/html" href="<? echo $this->url; ?>"/>
			<id><? echo $this->id; ?></id>
			<published><? echo date("Y-m-d", $this->published)."T".date("H:i:s", $this->published)."Z"; ?></published>
			<updated><? echo date("Y-m-d", $this->updated)."T".date("H:i:s", $this->updated)."Z"; ?></updated>
			<author>
				<name><? echo $this->authorName; ?></name>
				<? echo $authorURI; ?>
				<? echo $authorEMail; ?>
			</author>
			<? echo $summary; ?>
			<? echo $content; ?>
		</entry>
		<?
	}
	
	/**
	 * Returns the entry's publishing date.
	 * @return int published - Unix timestamp
	 * */
	function getPublished() {
		return $this->published;
	}
	
	/**
	 * Returns the date the entry was updated.
	 * @return int updated - Unix timestamp
	 * */
	function getUpdated() {
		return $this->updated;
	}
}
?>