<?php

unset($mainadvar);
session_start();
session_register("mainadvar");


if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
	header("Location: unautor.html");
	return;
}
include('inc/templates.inc.php');
hdr();

?>