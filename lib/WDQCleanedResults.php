<?php
require_once('ReplicationDatabase.php');

class WDQCleanedResults {
	/// Makes a WDQ query and returns cleaned results as a PHP object
	
	///
	/// Properties
	///

	/**
	 * Query results
	 * @var array
	 */
	public $results;

	/**
	 * Query
	 * @var string
	 */
	public $query;
	
	/**
	 * Language
	 * @var string
	 */
	public $language;
	
	/**
	 * Initializes a new instance of the WikidataNoLabelsQuery class
	 *
	 * @param string $query The WDQ query to run
	 * @param string $language The language to select items without labels in
	 */
	function __construct ($query, $language) {
		$this->query = $query;
		$this->language = $language;
	}

	///
	/// Query logic
	///

	/**
	 * Runs the queries
	 *
	 * After this method has ben called, $this->results is populated.
	 */
	function run () {
		if (!self::isValidWDQ($this->query)) {
			throw new Exception("WDQ isn't valid.");
		}
		
		// Get the items list from WDQ
		$items = self::queryWDQ($this->query);
		
		$labels = $this->getItemsLabels($this->language, $items);
		foreach ($labels as $label) {
			$key = $label['id'];
			$this->results[$key] = $label['label'];
		}
		
		asort($this->results);
	}
	
	///
	/// Replication databases label information helper methods
	///

	/**
	 * Get the entities labels
	 *
	 * @param string $language the language to gets labels defined in
	 * @param string $itemsHaystack the items haystack
	 * @param bool $queryLabels queries also the labels
	 * @return array
	 */
	function getItemsLabels ($language, $itemsHaystack) {
		//TODO: sanitize the language, should be a valid language code
		//TODO: sanitize the haystack, should only contain numeric id
		$clauseIn = join(', ', $itemsHaystack);
		$sql = "SELECT term_entity_id, term_text
			FROM wb_terms
		        WHERE term_type = 'label' AND term_language = '$language' AND term_entity_type = 'item' AND term_entity_id IN ($clauseIn)";

		$db = ReplicationDatabaseFactory::get('wikidatawiki');
		$result = $db->query($sql);
		$items = array();
		while ($row = $result->fetch_assoc()) {
			$items[] = array(
				'id' => $row['term_entity_id'],
				'label' => $row['term_text']
			);
		}
		return $items;
	}

	/**
	 * Queries the Wikidata API
	 *
	 * @param string $item the Item number
	 * @return array the geolocation
	 */
	function getItemLocation ($item) {
		if (is_numeric($item)) {
			$url = 'https://www.wikidata.org/w/api.php?action=wbgetclaims&entity=Q'.$item.'&property=P625&format=json';

			$data = json_decode(file_get_contents($url), true);

			echo "<pre>";
			print_r($data);
			echo "</pre>";
			$latitude = $data['claims']['P625']['mainsnak']['datavalue']['value']['latitude'];
			$longitude = $data['claims']['P625']['mainsnak']['datavalue']['value']['longitude'];

			$return = array($latitude,$longitude);
		} else {
			throw new Exception('Incorrect item');
		}
		return $return;
	}

	///
	/// WDQ helper methods
	///

	/**
	 * Determines if the specified query is a valid one.
	 *
	 * @param string $query the WDQ
	 * @return bool true if the query is valid; otherwise, false.
	 */
	static function isValidWDQ ($query) {
		return true;
	}

	/**
	 * Queries the WDQ server
	 *
	 * @param string $query the WDQ
	 * @return array WDQ result
	 */
	static function queryWDQ ($query) {
		$url = 'http://wdq.wmflabs.org/api?q=' . urlencode($query);
		$data = json_decode(file_get_contents($url));
		if ($data->status->error != 'OK') {
			throw new Exception($data->status->error);
		}
		return $data->items;
	}
}
