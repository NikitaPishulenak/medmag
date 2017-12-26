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

if(isset($typ) && $typ){
	switch($typ){
		case "content":
			include('fnciws/menu/menufnc.php');
			if($act=="addBL"){
				addBLOk();	
			}elseif($act=="actBL"){
				actBLOk();	
			}elseif($act=="delBL"){
				delBLOk();	
			}elseif($act=="rplMenu"){
				replMenu();
			} else {
				menu(showmn(),$typ);
			}
			break;
		case "pref":
			include('fnciws/menu/menupref.php');
			menu(prefmn(),$typ);
			break;
	}
}
?>