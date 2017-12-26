<?php
header("Content-type: text/html; charset=windows-1251");
include("../../inc/config.inc.php");
$con=mysql_connect($dbhost,$dbuname,$dbpass);
mysql_select_db($dbname);
$con=mysql_query("SET NAMES cp1251");
$text=iconv('UTF-8', 'Windows-1251',$_POST['name']);
$res=mysql_query("SELECT COUNT(name) FROM `iws_arfiles_records` WHERE name LIKE '%".$text."%'");
$ajx=mysql_result($res,0);
if ($ajx >= 1){
	$res=mysql_query("SELECT name FROM `iws_arfiles_records` WHERE name LIKE '%".$text."%' LIMIT 5");
	$aj="\r";
		if(mysql_numrows($res)>=1){
			while($arr=mysql_fetch_row($res)){
				$aj.=$arr[0]."\r \r";
			}   
			$aj.=$ajx;
		}
		echo " ".$aj;
}else{echo " 0";}
	
?>