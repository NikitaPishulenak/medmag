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

if($gopr) $mainadvar['urlp']=$gopr;

switch ($gopr){
	case "admin":                    // страница редакторов
		if($mainadvar['sadm']){
			include('fnciws/admin/adminfunc.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>Извините данная функция Вам недоступна!</b></center>");
		}
		break;
	case "users":                    // страница пользователей
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/users/usrfunc.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>Извините данная функция Вам недоступна!</b></center>");
		}
		break;
	case "param":                    // страница параметров сайта
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/prefsite/pref.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>Извините данная функция Вам недоступна!</b></center>");
		}
		break;
	case "htmtampl":                    // страница html - шаблонов
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/htmltempl/template.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>Извините данная функция Вам недоступна!</b></center>");
		}
		break;
	case "stat":                    // статистика
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/stat/statistic.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>Извините данная функция Вам недоступна!</b></center>");
		}
		break;
	case "banners":
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/banners/banners.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>Извините данная функция Вам недоступна!</b></center>");
		}
		break;
	case "filemanager":                    // файловый менеджер
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('filemanager/browser.php');
//			frmIWS($cont);
		}else{
			frmIWS("<center><b>Извините данная функция Вам недоступна!</b></center>");
		}
		break;
	case "quit":
		$mainadvar['ath']="unavtores";
		mysql_close($dblink);
		header("Location: unautor.html"); // выход из админовских модулей;
		break;
	default:
		if($mainadvar['urlp']){
			header("location: mainiwspref.php?gopr=".$mainadvar['urlp']);
			return;
		}else{
			include('fnciws/default/deflt.php');
			frmIWS($cont);
		}
		break;

}
?>