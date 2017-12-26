<?php

unset($mainadvar);
session_start();
session_register("mainadvar");

if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
	header("Location: unautor.html");
	return;
}

include('inc/main.inc.php');
include('inc/templates.inc.php');

if(!$mainadvar['sadm']){
	if(!$mainadvar['msg']){
		return nmsg("");
	}
}
$ret="";
/*
$ret="<a href=\"#\"><img name=msgM src=\"images/msgM.gif\" border=0 alt=\"новое сообщение\"></a>"
		."<script><!--
			var trnn=1;
				function asd(){
				if(trnn){
					msgM.style.filter=\"alpha(opacity=10)\";			 	
					trnn =0;
				}else{
					msgM.style.filter=\"alpha(opacity=100)\";			 	
					trnn = 1;
				}
					setTimeout(\"asd()\",600);
				}
				setTimeout(\"asd()\",100);
			//--></script>";
*/
nmsg($ret);
?>
