<?php
include_once("lib/pageInterface.lib.php");
$page= new pageInterface("CSV to QuickStatements converter");
include_once("inc/header.php");
include_once("lib/parsecsv.lib.php");
include_once("lib/csv2QuickStatements.lib.php");
?>

<div class="container theme-showcase" role="main">
<div class="jumbotron">
	<h1>CSV to quick_statements </h1>
	<p>This tool converts a properly formated CSV file into a series of commands you can pass to
	 Magnus' <a href="http://tools.wmflabs.org/wikidata-todo/quick_statements.php">Quick Statements</a> tool for Wikidata.
	</p>

	<div id="helpToggle" style="float: right;">[Click to show/hide HOWTO]</div>
</div>

<div id="helpSection" <?php if ( isset($_FILES["csv"])) { echo 'style="display: none;"';}?> >
	<h3><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> HOWTO</h3>
	<!-- TODO : complete documentation -->
	<p>You can find a sample CSV file <a href="samples/csv_sample.csv">here</a>.</p>
	<p>The order of the columns must be as follow: </p>
	<ul>
		<li>qid</li>
		<li>Sources, prefixed with "S" instead of "P" (for example, the label of the column for "P143 (imported from)" must start with "S143")</li>
		<li>Labels, descriptions, aliases, prefixed with "L(lang), d(lang), A(lang)... You can put several aliases for one language, separated by "|".</li>
		<li>Properties</li>
		<li>Qualifiers, prefixed with "qal" instead of "P".</li>
		<li>Site links, prefixed with "S(lang)"</li>
	</ul>

	<p>For most columns titles and values, you can add human-readable comments after a "|", if it helps (see the sample file.)</p>
</div>

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
		$page->alert("Return Code " . $_FILES["file"]["error"],"danger");
	} else if (!in_array($_FILES["csv"]["type"],$csv_mimetypes)) {
		$page->alert("File type error — the file doesn’t seems to be a CSV.","danger");
	} else {
		$csv = new parseCSV($_FILES["csv"]["tmp_name"]);

		$csv2QS = new csv2QuickStatements($csv->data);
		$full_commands_list = $csv2QS->run();

		echo '<h3><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Result</h3>';
		#check for warnings

		if(!empty($csv2QS->warnings)) {
			foreach ($csv2QS->warnings as $warning) {
				$page->alert($warning,"warning");
			}
		}

		if (!empty($full_commands_list)) {
			echo "<pre>";
			print_r($full_commands_list);
	/*		print_r($_FILES["csv"]); // Just in case I need to check the full csv
			print_r($csv->data); //*/
			echo "</pre>";
			
			$page->alert('Just copy the lines above and paste them into 
			<a href="http://tools.wmflabs.org/wikidata-todo/quick_statements.php">Quick Statements</a> !',"success");
		} else {
			$page->alert('The CSV file seems to contain no data.','danger');
		}
	}
} else {
	//No file has been imported yet.
}
?>
			
</div> <!-- End of container div -->

<!-- Specific Javascript for this page -->
<script>
$(document).ready(function(){
	$("#helpToggle").click(function(){
		$("#helpSection").toggle("slow");
	});
});
</script>
<?php include_once("inc/footer.php"); ?>
