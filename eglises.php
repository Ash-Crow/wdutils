<?php
include_once("lib/pageInterface.lib.php");
$page= new pageInterface("Églises par département français");
include_once("inc/header.php");

require_once('lib/WDQCleanedResults.php');
?>
<div class="container theme-showcase" role="main">
<div class="jumbotron">
	<h1>Églises par département français</h1>
</div>
<?php


if (isset($_REQUEST['area'])) {	
	try {
			$area=$_REQUEST['area'];
			$communesQuery = new WDQCleanedResults("tree[$area][][17,131] and claim[31:16970]","fr");
			$communesQuery->run();
?>
	
	<table class=\"sortable\">
		<thead>
			<tr>
				<th>Item</th><th>Label <?php language ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($communesQuery->results as $key => $value) {
			echo '<tr><td><a href="https://www.wikidata.org/wiki/Q'. $key ."\">Q$key</a></td>";
			echo "<td>", $value, "</td>";
			echo '</tr>';
		}
		echo "</tbody></table>"; 
			
	} catch (Exception $ex) {
		echo "<p>", $ex->getMessage(), "</p>";
	}
}?>
<h3>Choisissez un département</h3>
<?php
	try {
			//$regionsQuery = new WDQCleanedResults("claim[31:36784]","fr");
			$areaQuery = new WDQCleanedResults("claim[31:6465] and noclaim[576]","fr");
			$areaQuery->run();
			
?>

<form role="form" method="GET">
	<div class="form-group">
		<label for="area">Département</label>
		<select id="area" name="area">
		<?php	foreach($areaQuery->results as $key => $value){
				echo '<option value="'.$key.'">'.$value;'</option>';
			} ?>
		</select>
	</div>
	<div class="form-group">
		<label for="language">Langue</label>
		<input id="language" name="language" value="fr" size="4" class="form-control" />
  </div>
	<div class="form-group">
		<button type="submit" class="btn btn-default">Valider</button>
	</div>
</form>
			
<?php
	} catch (Exception $ex) {
		echo "<p>", $ex->getMessage(), "</p>";
	}


?>

</div> <!-- End of container div -->
<?php
include_once("inc/footer.php");
?>
