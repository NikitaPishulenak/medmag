<?php

unset($mainadvar);
session_start();
session_register("mainadvar");

if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
	header("Location: login.php");
	return;
}

if(isset($lang) && $lang)
	$mainadvar['lng']=$lang;
	unset($mainadvar['menuhp']);




echo "<html>"
	."<TITLE>iwSite - ”правление сайтом</TITLE>"
	."<frameset rows=\"45,*\" frameborder=no border=0 name=mainfrms>"
		."<frame src=\"header.php?mvr=$mvr\" name=A scrolling=no noresize>";
switch($mvr){
case "content":
	echo "<frameset cols=\"220, *\" name=frms>"
		."<frame src=\"left.php?typ=content\" name=B scrolling=no>"
		."<frame name=C src=\"mainiws.php\">"
		."</frameset>";
break;
case "pref":
	echo "<frameset cols=\"220, *\" name=frms>"
		."<frame src=\"left.php?typ=pref\" name=B scrolling=no>"
		."<frame name=C src=\"mainiwspref.php\">"
		."</frameset>";
break;

case "filemanager":
	echo "<frame name=C src=\"filemanager/browser.php\">";
break;

default:
	echo "<frameset cols=\"220, *\" name=frms>"
		."<frame src=\"left.php?typ=content\" name=B scrolling=no>"
		."<frame name=C src=\"mainiws.php\">"
		."</frameset>";
break;

}
echo "</frameset>"
	."</html>";

?>
