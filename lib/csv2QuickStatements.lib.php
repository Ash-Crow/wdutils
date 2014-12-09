<?php
class csv2QuickStatements {
	///
	/// Properties
	///

	/**
	 * Query results
	 * @var string
	 */
	public $results;

	/**
	 * Warnings issued by the run() function
	 * @var array
	 */
	public $warnings;	

	/**
	 * CSV data
	 * @var array
	 */
	private $csv_data;

	///
	/// Functions
	///

	/**
	 * Initializes a new instance of the csv2QuickStatements class
	 *
	 * @param array $csv_data the data from the CSV
	 */
	public function __construct ($csv_data) {
		$this->csv_data	= $csv_data;
		$this->results	= "";
		$this->warnings = array();
	}

	/**
	 * Removes comments that could appear after a | in a property or value. By the way, checks if a value is a property or Qid and sets it to upper case if needed.
	 *
	 * @param string $string the data from the CSV
	 */
	protected function stripComments($string, $addCommas=0) {
		$explode = explode("|", $string); // First, remove any human-friendly comment.
		$string_part = $explode[0];

		$time_pattern = "/^\+\d{11}-\d{2}-\d{2}/";

		if (preg_match('/^(s|p|q)\d+$/', strtolower($string_part))) { $string_part = strtoupper($string_part); }
		elseif (($addCommas) && (!preg_match($time_pattern, $string_part))) {
			$string_part = '"'. $string_part .'"';
		}
		return $string_part;
	}

	public function run() {
		foreach ($this->csv_data as $entry) {
			$commands_array= array();
			$source="";
			$qid="";

			foreach ($entry as $key => $value) {
				$key=$this->stripComments($key);
				
				switch ($key) {
					case '':
						$this->warnings[] = 'Unidentified property for value '.$value.'.';
						break;
					case 'qid': // Let's check the QID first
						if (empty($value)){
							$commands_array[] = "CREATE";
							$qid = "LAST";
						} else {
							$qid = $this->stripComments($value);
						}
						break;
					case preg_match('/^(s|S)\d+$/', $key)? true : false: // Source
						if (!empty($value)){ $source .= "	" . $key . "	" . $this->stripComments($value,1); }

						break;
					case preg_match('/^(p|P)\d+$/', $key)? true : false: // Property
						if (!empty($value)){ $commands_array[]= $qid ."	" . $key . "	" . $this->stripComments($value,1) . $source; }
						break;
					case preg_match('/^(qal|QAL)(?P<number>\d+)$/', $key, $matches)? true : false: // Qualifier will be added to the last property.
						if (!empty($value)){
							$last_prop = array_pop($commands_array);
							$commands_array[] = $last_prop . "	P" . $matches["number"] . "	" . $this->stripComments($value,1);
							break;
						}
					case preg_match('/^(l|L|d|D)(?P<lang>[a-z]+)$/', $key, $matches)? true : false: // Labels and descriptions
						// TODO : check if language code is valid
						if (!empty($value)){ $commands_array[]= $qid ."	" . $key . '	"' . $value. '"' ; }
						break;
					case preg_match('/^(a|A)(?P<lang>[a-z]+)$/', $key, $matches)? true : false: // Aliases
						// TODO : check if language code is valid
						if (!empty($value)){
							$aliases=explode("|", $value);
							foreach ($aliases as $alias) {
								$commands_array[]= $qid ."	" . $key . '	"' . $alias. '"' ;
							}
						}
						break;
						// TODO : check if language code is valid
					case preg_match('/^(s|S)(?P<lang>[a-z]+)$/', $key, $matches)? true : false: // Sitelinks
						// TODO : check if site code is valid
						if (!empty($value)){ $commands_array[]= $qid ."	" . $key . '	"' . $value. '"' ; }
						break;
					default:
						$this->warnings[] = 'Unknown property '.$key.'.';
						break;
				}
			}

			if (!empty($commands_array)) {
				$commands_list = implode("\n", $commands_array);
				$this->results .= $commands_list ."\n\n";
			}
		}

		return $this->results;
	}
}