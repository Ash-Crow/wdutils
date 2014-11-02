<?php

/**
 * Queries wikidata items without label in a specified language.
 * Released under BSD license.
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Communes françaises | Tool Labs</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
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
      <li class="active"><a href="/wikidata-nolabels/">Query</a></li>
      <li><a href="/wikidata-nolabels/help.html">Help</a></li>
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
	
	try {
			$communesQuery = new WDQCleanedResults("claim[31:484170]","fr");
			$communesQuery->run();
			
			echo "<pre>";
			print_r($communesQuery);
			echo "</pre>";
	} catch (Exception $ex) {
		echo "<p>", $ex->getMessage(), "</p>";
	}
?>

