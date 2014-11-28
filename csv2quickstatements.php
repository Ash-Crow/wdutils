<?php
include_once("inc/header.php");
include_once("lib/parsecsv.lib.php");
?>

<div class="container theme-showcase" role="main">
<div class="jumbotron">
	<h1>CSV to quick_statements </h1>
	<p>This tool converts a properly formated CSV file into a series of commands you can pass to
	 Magnus' <a href="http://tools.wmflabs.org/wikidata-todo/quick_statements.php">Quick Statements</a> tool for Wikidata.
	</p>
</div>

<h3><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Help</h3>
<p>
TODO: Here be explanations on the expected syntax.

* First column must be labeled "qid"
* If there are sources, they must be located in the columns right after the qid, prefixed with "S" instead of "P" (for example, the label of the column for "P143 (imported from)" must start with "S143")

</p>

<!-- The import form -->
<h3><span class="glyphicon glyphicon-import" aria-hidden="true"></span> Upload CSV file</h3>

<form role="form" action="<?php $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label for="csv">Select a CSV file.</label>
		<input type="file" id="csv" name="csv" value="" />
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-default">Valider</button>
	</div>
</form>

<!-- If a file has been imported, treat it. -->
<?php
if ( isset($_FILES["csv"])) {
	$csv_mimetypes = array('text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext', 'application/octet-stream', 'application/txt');

		//if there was an error uploading the file
	if ($_FILES["csv"]["error"] > 0) {
		echo '<div class="alert alert-danger" role="alert"><strong>Error</strong>: Return Code " . $_FILES["file"]["error"] . "</div>';

	} else if (!in_array($_FILES["csv"]["type"],$csv_mimetypes)) {
		echo '<div class="alert alert-danger" role="alert"><strong>File type error</strong>: The file doesn’t seems to be a CSV.</div>';
	} else {
		$csv = new parseCSV($_FILES["csv"]["tmp_name"]);

		// TODO make a proper class for this.

		$full_commands_list= "";

		function stripComments($property) {
			$explode = explode("|", $property); // First, remove any human-friendly comment.
			return $explode[0];
		}

		foreach ($csv->data as $entry) {
			$commands_list= array();
			$source="";
			$qid="";

			//TEST remove this once fixed.
			echo "<pre>";
			print_r("Entry:");
			print_r($entry);
			echo "</pre>";

			foreach ($entry as $key => $value) {
				$key=stripComments($key);
				
				switch ($key) {
					case '':
						echo '<div class="alert alert-warning" role="alert"><strong>Warning</strong>: Unidentified property for value '.$value.'</div>';
						break;
					case 'qid': // Let's check the QID first
						if (empty($value)){
							$commands_list[] = "CREATE";
							$qid = "LAST";
						} else {
							$qid = stripComments($value);
							echo "toto";
						}
						break;
					case preg_match('/^(s|S)\d+$/', $key)? true : false: // Source
						if (!empty($value)){ $source .= "	" . strtoupper($key) . "	" . stripComments($value); }

						break;
					case preg_match('/^(p|P)\d+$/', $key)? true : false: // Property
						echo "source:".$source;
						$commands_list[]= $qid ."	" . strtoupper($key) . "	" . stripComments($value) . $source;
						break;
					case preg_match('/^(qal|QAL)(?P<number>\d+)$/', $key, $matches)? true : false: // Qualifier will be added to the last property.
						$last_prop = array_pop($commands_list);
						$commands_list[] = $last_prop . "	P" . $matches["number"] . "	" . stripComments($value);
					default:
						echo '<div class="alert alert-warning" role="alert"><strong>Warning</strong>: Unknown property '.$key.'</div>';
						break;
				}
			}

			//TEST remove this once fixed.
			echo "<pre>";
			print_r("Values:");
			print_r($commands_list);
			echo "</pre>";
		}

		echo '<h3><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Result</h3>';

		//TEST remove this once fixed.
		echo "<pre>";
		print_r($_FILES["csv"]);
		print_r($csv->data);
		echo "</pre>";
		
		echo '<div class="alert alert-success" role="alert">Just copy the lines above and paste them into <a href="http://tools.wmflabs.org/wikidata-todo/quick_statements.php">Quick Statements</a> !</div>';
	}
} else {
	//No file has been imported yet.
}
?>
			
</div> <!-- End of container div -->
<?php include_once("inc/footer.php"); ?>
