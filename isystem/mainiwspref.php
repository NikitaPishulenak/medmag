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
	case "admin":                    // �������� ����������
		if($mainadvar['sadm']){
			include('fnciws/admin/adminfunc.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>�������� ������ ������� ��� ����������!</b></center>");
		}
		break;
	case "users":                    // �������� �������������
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/users/usrfunc.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>�������� ������ ������� ��� ����������!</b></center>");
		}
		break;
	case "param":                    // �������� ���������� �����
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/prefsite/pref.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>�������� ������ ������� ��� ����������!</b></center>");
		}
		break;
	case "htmtampl":                    // �������� html - ��������
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/htmltempl/template.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>�������� ������ ������� ��� ����������!</b></center>");
		}
		break;
	case "stat":                    // ����������
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/stat/statistic.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>�������� ������ ������� ��� ����������!</b></center>");
		}
		break;
	case "banners":
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('fnciws/banners/banners.php');
			frmIWS($cont);
		}else{
			frmIWS("<center><b>�������� ������ ������� ��� ����������!</b></center>");
		}
		break;
	case "filemanager":                    // �������� ��������
		if($mainadvar['sadm'] || ereg($gopr,$mainadvar['prf'])){
			include('filemanager/browser.php');
//			frmIWS($cont);
		}else{
			frmIWS("<center><b>�������� ������ ������� ��� ����������!</b></center>");
		}
		break;
	case "quit":
		$mainadvar['ath']="unavtores";
		mysql_close($dblink);
		header("Location: unautor.html"); // ����� �� ����������� �������;
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