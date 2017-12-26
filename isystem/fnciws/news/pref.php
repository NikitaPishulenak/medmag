<?php

include('../../exit.php');

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

    if(isset($act) && $act == "prefOk"){

         $lmt=trim($lmt);
         $lmt=substr($lmt,0,2);
         $lmtv=trim($lmtv);
         $lmtv=substr($lmtv,0,2);
         if(empty($lmtv) || empty($lmt) || !is_numeric($lmtv) || !is_numeric($lmt)) { 
            header("location: pref.php?err=3");
            return;
         }
         $sql="update iws_newspref set limt=$lmt,lmtveiw=$lmtv where id=1";
         if(!mysql_query($sql)){
            header("location: pref.php?err=1");
            return;
         } else {    
            echo "<HTML><HEAD>"
            ."<title>Изменение настроек новостей</title>"
            ."</head><body bgcolor=buttonface>"
            ."<h4>Настройки модуля новостей успешно сохранены</h4>"
            ."<script><!--\n"
               ."setTimeout(\"window.close()\",2000);\n"
               ."//--></script>"
               ."</body></html>";
         }

   }else{
         echo "<HTML><HEAD>
         <title>Изменение настроек новостей</title>
         <link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">
         </HEAD>
         <script>
         function KeyPress()
         {
         if(window.event.keyCode == 27) window.close();
         }
         </script>
         <script><!--
            function submitr(fr){
            if (fr.lmt.value && fr.lmtv.value) {
               fr.submit();
            } else {
               alert (\"Не введена вся информация!   \");
            }
            }
         //--></script>
         <BODY onKeyPress=\"KeyPress()\" bgcolor=buttonface>
         ";
         switch($err){
            case 1:
            echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
            break;
            case 3:
            echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Не введена вся информация или же неверные числовые данные. Попробуйте еще раз.</td></tr></table><br>";
            break;
         }
         list($lmt,$lmtv)=mysql_fetch_row(mysql_query("select limt,lmtveiw from iws_newspref where id=1"));

         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>
         <tr><td align=center class=usr>Настройки вывода новостей</td></tr></table>
         <form method=\"post\" name=frm>
         <input type=hidden name=act value=prefOk>
         <table width=100% border=0 cellpadding=3 cellspacing=2 align=center>
         <tr><td align=right width=40%>Количество новостей первой странице сайта: </td><td><input name=\"lmtv\" size=4 maxlength=2 value=\"".$lmtv."\"> Не менее одной новости</td></tr>
         <tr><td align=right width=40%>Количество новостей в разделе всех новостей: </td><td><input name=\"lmt\" size=4 maxlength=2 value=\"".$lmt."\"> Не менее одной новости</td></tr>
         <tr><td colspan=2><hr></td></tr>
         <tr><td></td><td align=right><input class=but type=button name=btn value=Сохранить onClick=\"submitr(frm)\">
         &nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"window.close()\"></td></tr>
         </form></table>
         </body></html>";
   }
?>