<?php
/**
* Common header for webpages
* Released under BSD license.
*/
$language = isset($_REQUEST['language']) ? $_REQUEST['language'] : 'fr';

$timerStart = microtime(true);

$thisPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php if (isset($page)) {echo $page->title. ' | ';} ?> WDUtils | Tool Labs</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
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
			<a class="navbar-brand" href="/ash-dev/">WDUtils | Tool Labs</a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li <?php if ($thisPage == "communes.php") { echo 'class="active"';} ?> ><a href="/ash-dev/wdutils/communes.php">Communes</a></li>
				<?php /* <li <?php if ($thisPage == "artworks.php") { echo 'class="active"';} ?> ><a href="/ash-dev/wdutils/artworks.php">Artworks</a></li> //*/ ?>
				<li <?php if ($thisPage == "csv2quickstatements.php") { echo 'class="active"';} ?> ><a href="/ash-dev/wdutils/csv2quickstatements.php">CSV to Quick Statements</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="navbar-logo"><a href="https://tools.wmflabs.org"><img title="Powered by Wikimedia Labs" src="//upload.wikimedia.org/wikipedia/commons/thumb/6/60/Wikimedia_labs_logo.svg/32px-Wikimedia_labs_logo.svg.png" /></a></li>
				<li class="navbar-logo"><a href="http://ashtree.eu/"><img title="Developed by Sylvain Boissel" src="http://ashtree.eu/avatars/logo2-32.png" /></a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</nav>