<?php
include_once("inc/header.php");
include_once("lib/ListArtworks.php");

if (isset($_REQUEST['template'])) {	
	try {
			$template=str_replace(" ", "_", $_REQUEST['template']);
			$artworksQuery = new ListArtworks($template);
			$artworksQuery->run();

			echo "<pre>";
			print_r($artworksQuery->results);
			echo "</pre>";

			/*
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

		//*/
			
	} catch (Exception $ex) {
		echo "<p>", $ex->getMessage(), "</p>";
	}
}?>

<h3>Template selection</h3>

<form role="form" method="GET">
	<div class="form-group">
		<label for="template">Template name:</label>
		<select id="template" name="template">
		<option value="Artwork">Artwork</option>
		<option value="MBA Rennes">MBA Rennes</option>
		</select>
	</div>
  </div>
	<div class="form-group">
		<button type="submit" class="btn btn-default">Valider</button>
	</div>
</form>
			

<?php include_once("inc/footer.php"); ?>