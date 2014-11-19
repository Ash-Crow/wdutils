<?php

class ListArtworks {	
	/// Makes a CommonsAPI query about pages using a template and returns cleaned results as an array.

	///
	/// Properties
	///

	/**
	 * Query results
	 * @var array
	 */
	public $results;

	/**
	 * Template
	 * @var string
	 */
	public $template;
	
	/**
	 * Limit
	 * @var int
	 */
	public $limit;

	/**
	 * ContinueArray
	 * @var array
	 */
	public $ContinueArray;

		/**
	 * ContinueString
	 * @var string
	 */
	public $continueString;


	///
	/// Functions
	///

	public function __construct ($template, $limit=10, $ContinueArray= Array() ) {
		$this->template = $template;
		$this->limit = $limit;


		$this->continueString = "&continue";
		if (!empty($ContinueArray)) {
			$this->continueString.= "=" . $ContinueArray["continue"] ."&geicontinue=" . $ContinueArray["geicontinue"];
		}
	}


	private function queryArtworksList($query) {
		$url = 'https://commons.wikimedia.org/w/api.php?'. $query;

		$data = json_decode(file_get_contents($url));

		// Error checking
		if (isset($data->warning->main)) {
			throw new Exception($data->warning->main);
		} elseif (isset($data->error)) {
			throw new Exception($data->error);
		}

		$this->results = $data;

		return $this->results;
	}

	public function run ( $ContinueArray= Array() ) {
		if (!empty($ContinueArray)) {
			$this->continueString = "&continue=". $ContinueArray["continue"] ."&geicontinue=" . $ContinueArray["geicontinue"];
		}

		$query = "action=query&prop=imageinfo&format=json";// the query basics
		$query.= "&iiprop=comment%7Cparsedcomment%7Ccanonicaltitle%7Curl%7Cdimensions%7Cmime%7Cthumbmime%7Cmediatype%7Cmetadata%7Ccommonmetadata%7Cextmetadata%7Carchivename"; // the props
		$query.= "&generator=embeddedin&geititle=Template%3A".$this->template."&geinamespace=6&geilimit=".$this->limit.$this->continueString; // the generator

		$queryResults= $this->queryArtworksList($query);
	}

}