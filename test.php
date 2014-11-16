<?php

function foo($query) {
	$url= "Toto".$query;
	return $url;
}

echo foo("tutu");
