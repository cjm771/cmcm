<?php
	session_start();
	$root = "../";
	require_once($root."php/lib/EventSource.class.php");
	$server = new EventSource("regenerateThumbs", $root);
?>