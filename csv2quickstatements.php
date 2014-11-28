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
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

	} else {
		$csv = new parseCSV($_FILES["csv"]["tmp_name"]);

		echo "<pre>";
		print_r($_FILES["csv"]);
		print_r($csv->data);
		echo "</pre>";
		
		echo "http://tools.wmflabs.org/wikidata-todo/quick_statements.php";
	}
} else {
	//No file has been imported yet.
}
?>
			
</div> <!-- End of container div -->
<?php include_once("inc/footer.php"); ?>
