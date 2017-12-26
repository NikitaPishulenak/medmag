<?php

include('../../exit.php');

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

    if(isset($act) && $act == "prefOk"){
         if(isset($vw) && $vw){ $vw = 1; }else{ $vw = 0; }

         $sql="update iws_opros_pref set view=$vw where id=1";
         if(!mysql_query($sql)){
            header("location: pref.php?err=1");
            return;
         } else {    
            echo "<HTML><HEAD>"
            ."<title>Изменение настроек голосования/опросов</title>"
            ."</head><body bgcolor=buttonface>"
            ."<h4>Настройки модуля голосования/опросов успешно сохранены</h4>"
           ."<script><!--\n"
               ."setTimeout(\"window.close()\",2000);\n"
               ."//--></script>"
               ."</body></html>";
         }

   }else{
         echo "<HTML><HEAD>
         <title>Изменение настроек голосования/опросов</title>
         <link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">
         </head>
         <script>
         function KeyPress()
         {
         if(window.event.keyCode == 27) window.close();
         }
         </script>
         <BODY onKeyPress=\"KeyPress()\" bgcolor=buttonface>
         ";
         if($err==1){
            echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
         }
         list($vw)=mysql_fetch_row(mysql_query("select view from iws_opros_pref where id=1"));

         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>
         <tr><td align=center class=usr>Настройки модуля голосования/опросов</td></tr></table>
         <script><!--
            function submitr(fr){
            if (fr.vw.value) {
               fr.submit();
            } else {
               alert (\"Не введена вся информация!   \");
            }
            }
         //--></script>
         <form method=\"post\" name=frm>
         <input type=hidden name=act value=prefOk>
         <table width=100% border=0 cellpadding=3 cellspacing=2 align=center>
         <tr><td align=right>Разрешить показ результатов пользователям: </td><td><input name=\"vw\" type=checkbox class=chk ";

         if($vw){ echo "checked"; }

         echo "></td></tr>
         <tr><td colspan=2><hr></td></tr>
         <tr><td></td><td align=right><input class=but type=button name=btn value=Сохранить onClick=\"submitr(frm)\">
         &nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"window.close()\"></td></tr>
         </form></table>
         </body></html>";
   }
?>