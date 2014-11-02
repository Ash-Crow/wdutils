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
	<title>Communes françaises | Tool Labs</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="/wikidata-nolabels/js/sorttable.js"></script>
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
<?php
require_once('lib/WDQCleanedResults.php');

if (isset($_REQUEST['region'])) {	
	try {
			$region=$_REQUEST['region'];
			$communesQuery = new WDQCleanedResults("tree[$region][150][17,131] and claim[31:484170]","fr");
			$communesQuery->run();
			
			echo "<pre>";
			print_r($communesQuery);
			echo "</pre>";
	} catch (Exception $ex) {
		echo "<p>", $ex->getMessage(), "</p>";
	}
}?>
<h3>Choisissez une région</h3>
<?php
	try {
			$regionsQuery = new WDQCleanedResults("claim[31:36784]","fr");
			$regionsQuery->run();
			
			echo "<pre>";
			print_r($regionsQuery);
			echo "</pre>";
?>

<form role="form" method="GET">
	<div class="form-group">
		<select>
		<?php	foreach($regionsQuery->results as $key => $value){
				echo '<option value="'.$key.'">'.$value;'</option>';
			} ?>
		</select>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-default">Submit</button>
	</div>
</form>
			

	} catch (Exception $ex) {
		echo "<p>", $ex->getMessage(), "</p>";
	}


?>

