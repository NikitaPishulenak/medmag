<?php 

include('../isystem/inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("�� ���� ������������ � ����");
@mysql_select_db($dbname) or die("�� ���� ������� ����");

$prevDT=" dt <= DATE_SUB(CURRENT_DATE, INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY) ";

switch($dumpAct){

// ������ ���������� �� �����
case "hour":   
   $result=mysql_query("SELECT IF(DATE_FORMAT(DATE_ADD(dt,INTERVAL 1 HOUR),'%k')=0,'24',DATE_FORMAT(DATE_ADD(dt,INTERVAL 1 HOUR),'%k')), count(ip_adr) FROM iws_statistics WHERE $prevDT GROUP BY DATE_FORMAT(dt,'%k'),ip_adr,coockie"); 
      
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]][0]+=$arr[1];
            $rdo[$arr[0]][1]++;
         }
         while (list($key,$val) = each($rdo)) {
            mysql_query("UPDATE iws_stat_hour SET user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]." WHERE id=$key");
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� ����                       
case "day":
   $result=mysql_query("SELECT DATE_FORMAT(dt,'%Y-%m-%d'), count(ip_adr) FROM iws_statistics WHERE $prevDT GROUP BY DATE_FORMAT(dt,'%e'),ip_adr,coockie ORDER BY dt");                         
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]][0]+=$arr[1];
            $rdo[$arr[0]][1]++;
         }
         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_day (day, user, page) VALUES ('$key', ".$rdo[$key][1].", ".$rdo[$key][0].")");
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� �������� �����          
case "menu":
   $result=mysql_query("SELECT url, menu, count(ip_adr) FROM iws_statistics WHERE $prevDT AND url<>'arts' AND url<>'files' AND url<>'files_A' AND url<>'files_B' AND url<>'files_C' AND url<>'photo' GROUP BY menu,url,ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0].$arr[1]][0]+=$arr[2];
            $rdo[$arr[0].$arr[1]][1]++;
            $rdo[$arr[0].$arr[1]][2]=$arr[0];
            $rdo[$arr[0].$arr[1]][3]=$arr[1];               
         }

         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_menu (url, menu, user, page) VALUES ('".$rdo[$key][2]."', ".$rdo[$key][3].", ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]);  
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� ������������� ������������
case "photo":
   $result=mysql_query("SELECT url, menu, count(ip_adr) FROM iws_statistics WHERE $prevDT and url='photo' GROUP BY menu,url,ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0].$arr[1]][0]+=$arr[2];
            $rdo[$arr[0].$arr[1]][1]++;
            $rdo[$arr[0].$arr[1]][2]=$arr[0];
            $rdo[$arr[0].$arr[1]][3]=$arr[1];               
         }

         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_photo (url, menu, user, page) VALUES ('".$rdo[$key][2]."', ".$rdo[$key][3].", ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]); 
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� ���������� ������ ����������
case "files":
   $result=mysql_query("SELECT url, menu, count(ip_adr) FROM iws_statistics WHERE $prevDT and url='files' GROUP BY menu,url,ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0].$arr[1]][0]+=$arr[2];
            $rdo[$arr[0].$arr[1]][1]++;
            $rdo[$arr[0].$arr[1]][2]=$arr[0];
            $rdo[$arr[0].$arr[1]][3]=$arr[1];               
         }

         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_files (url, menu, user, page) VALUES ('".$rdo[$key][2]."', ".$rdo[$key][3].", ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]); 
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� ���������� ������ �������������
case "files_C":
   $result=mysql_query("SELECT url, menu, count(ip_adr) FROM iws_statistics WHERE $prevDT and url='files_C' GROUP BY menu,url,ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0].$arr[1]][0]+=$arr[2];
            $rdo[$arr[0].$arr[1]][1]++;
            $rdo[$arr[0].$arr[1]][2]=$arr[0];
            $rdo[$arr[0].$arr[1]][3]=$arr[1];               
         }

         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_files_C (url, menu, user, page) VALUES ('".$rdo[$key][2]."', ".$rdo[$key][3].", ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]); 
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� ���������� ������ ������� ������� ��������
case "files_B":
   $result=mysql_query("SELECT url, menu, count(ip_adr) FROM iws_statistics WHERE $prevDT and url='files_B' GROUP BY menu,url,ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0].$arr[1]][0]+=$arr[2];
            $rdo[$arr[0].$arr[1]][1]++;
            $rdo[$arr[0].$arr[1]][2]=$arr[0];
            $rdo[$arr[0].$arr[1]][3]=$arr[1];               
         }

         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_files_B (url, menu, user, page) VALUES ('".$rdo[$key][2]."', ".$rdo[$key][3].", ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]); 
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� ���������� ������ ������������ �������
case "files_A":
   $result=mysql_query("SELECT url, menu, count(ip_adr) FROM iws_statistics WHERE $prevDT and url='files_A' GROUP BY menu,url,ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0].$arr[1]][0]+=$arr[2];
            $rdo[$arr[0].$arr[1]][1]++;
            $rdo[$arr[0].$arr[1]][2]=$arr[0];
            $rdo[$arr[0].$arr[1]][3]=$arr[1];               
         }

         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_files_A (url, menu, user, page) VALUES ('".$rdo[$key][2]."', ".$rdo[$key][3].", ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]); 
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

// ������ ���������� �� ��������-�������          
case "arts":
   $result=mysql_query("SELECT url, menu, count(ip_adr) FROM iws_statistics WHERE $prevDT and url='arts' GROUP BY menu,url,ip_adr,coockie");
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0].$arr[1]][0]+=$arr[2];
            $rdo[$arr[0].$arr[1]][1]++;
            $rdo[$arr[0].$arr[1]][2]=$arr[0];
            $rdo[$arr[0].$arr[1]][3]=$arr[1];               
         }

         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_arts (url, menu, user, page) VALUES ('".$rdo[$key][2]."', ".$rdo[$key][3].", ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]); 
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

//������ ���������� �� �������
case "month":
   $result=mysql_query("SELECT DATE_FORMAT(dt,'%Y-%m'),count(ip_adr) FROM iws_statistics WHERE $prevDT GROUP BY DATE_FORMAT(dt,'%Y-%m'),ip_adr,coockie");
      
      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            $rdo[$arr[0]][0]+=$arr[1];
            $rdo[$arr[0]][1]++;
         }  
         while (list($key,$val) = each($rdo)) { 
            mysql_query("INSERT INTO iws_stat_month (month, user, page) VALUES ('$key-00', ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]);       
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";
break;

//������ ���������� �� ��������� � ������
case "frm":
   $result=mysql_query("SELECT LCASE(SUBSTRING_INDEX(REPLACE (REPLACE(frm,'http://',''),'www.',''),'/',1)),count(ip_adr) FROM iws_statistics WHERE $prevDT GROUP BY LCASE(SUBSTRING_INDEX(REPLACE(REPLACE(frm,'http://',''),'www.',''),'/',1)),ip_adr,coockie");

      if(mysql_numrows($result)>=1){
         while($arr=mysql_fetch_row($result)){
            if(!$arr[0]) $arr[0] = "unknown";
            $rdo[$arr[0]][0]+=$arr[1];
            $rdo[$arr[0]][1]++;
         }
         while (list($key,$val) = each($rdo)) {
            mysql_query("INSERT INTO iws_stat_from (url, user, page) VALUES ('$key', ".$rdo[$key][1].", ".$rdo[$key][0].") ON DUPLICATE KEY UPDATE user=user+".$rdo[$key][1].", page=page+".$rdo[$key][0]); 
         }
         unset($rdo);
         mysql_free_result($result);
      }
      echo "Ok";     
break;

//�������� ������ ���������� � ����������� �������
case "delANDopt":
      mysql_query("DELETE FROM iws_statistics WHERE $prevDT");
      mysql_query("OPTIMIZE TABLE iws_statistics");
      echo "Ok";
break;
}

?>
