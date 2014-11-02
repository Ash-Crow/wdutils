<?php

/**
 * Queries wikidata items without label in a specified language.
 * Released under BSD license.
 */
 
$language = isset($_REQUEST['language']) ? $_REQUEST['language'] : 'fr';
 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>WDUtils | Tool Labs</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="js/sorttable.js"></script>
	<style>
.navbar-logo img {
	width: 32px;
	padding-top: 9px !important;
	padding-bottom: 0 !important;
}

.navbar-logo a {
	padding-top: 0 !important;
	padding-bottom: 0 !important;
}

table {
	border-spacing: 10px;
	border-collapse: separate;
}

table.sortable th:not(.sorttable_sorted):not(.sorttable_sorted_reverse):not(.sorttable_nosort):after {
	content: " \25B4\25BE";
}
	</style>
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="/wikidata-nolabels/">Communes de France</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li class="active"><a href="/wikidata-nolabels/">Communes</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li class="navbar-logo"><a href="https://tools.wmflabs.org"><img title="Powered by Wikimedia Labs" src="//upload.wikimedia.org/wikipedia/commons/thumb/6/60/Wikimedia_labs_logo.svg/32px-Wikimedia_labs_logo.svg.png" /></a></li>
        <li class="navbar-logo"><a href="http://ashtree.eu/"><img title="Developed by Sylvain Boissel" src="http://ashtree.eu/avatars/logo2-32.png" /></a></li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
<div id="main_content" class="container"><div class="row">
	<h1>Communes françaises par département</h1>
<?php
require_once('lib/WDQCleanedResults.php');

if (isset($_REQUEST['area'])) {	
	try {
			$area=$_REQUEST['area'];
			$communesQuery = new WDQCleanedResults("tree[$area][150][17,131] and claim[31:484170]","fr");
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

