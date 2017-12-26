<?php

$cont=admin_home();

function admin_home() {
global $mainadvar;

$massc="<TABLE height=100% WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR=#ECECEC>"
."<tr height=45%><td width=50% valign=top>&nbsp;<b style=\"color:#9A9A9A; font-family:Verdana, Arial;\">";
if($mainadvar['lng']=="ru"){
	$massc.="русская версия сайта";
}else{
	$massc.="english version";
}
$massc.="</b></td><td></td><td width=50%></td></tr>"
."<tr><td></td><td><img src=\"images/logo_s.gif\" border=0></td><td></td></tr>"
."<tr height=55%><td></td><td></td><td></td></tr>"
."</TABLE>";
return $massc;
} // admin_home()

?>