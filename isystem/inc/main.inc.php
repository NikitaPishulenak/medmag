<?php //main.inc.php

//
// Библиотека основных функций проекта
// (автоматически подключает к базе MySQL
// необходимо закрывать $dblink при выходе)
//
//

include('inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

?>