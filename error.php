<?php
session_start();
session_register("language");

if (!isset($language) || ($language['lng']!="ru" && $language['lng']!="en")) $language['lng']="ru";

include('isystem/inc/config.inc.php');
$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "utf8"');

?>
<!DOCTYPE html>
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>404! Page not found</TITLE>
<META NAME="DESCRIPTION" CONTENT="Page not found.">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; CHARSET=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=9">
<META NAME="viewport" CONTENT="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<META NAME="KEYWORDS" CONTENT="404">
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700&subset=cyrillic-ext' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="/style_main_<?php echo $language['lng']; ?>.css">
<link rel="stylesheet" type="text/css" href="/style_<?php echo $language['lng']; ?>.css">

<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="/style_<?php echo $language['lng']; ?>_IE.css" media="all"></link>
<![endif]-->

</HEAD>
<BODY>
<?php

list($ctt)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=4"));
echo stripslashes($ctt);

?>
</body>
</html>
<?php

//header ("HTTP/1.0 200 Ok");

?>