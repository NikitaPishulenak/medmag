<?php //main.inc.php

//
// ���������� �������� ������� �������
// (������������� ���������� � ���� MySQL
// ���������� ��������� $dblink ��� ������)
//
//

include('inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("�� ���� ������������ � ����");
@mysql_select_db($dbname) or die("�� ���� ������� ����");
mysql_query('SET NAMES "cp1251"');

?>