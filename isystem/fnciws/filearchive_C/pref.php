<?php

include('../../exit.php');

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

    if(isset($act) && $act == "prefOk"){
         $countinmain=substr(trim($countinmain),0,2);
         $countinpage=substr(trim($countinpage),0,3);
         $space=substr(trim($space),0,2);
         $extention=trim($extention);

         if(empty($countinmain) || empty($countinpage) || !is_numeric($countinmain) || !is_numeric($countinpage)) { 
            header("location: pref.php?err=3");
            return;
         }
         $sql="UPDATE iws_arfiles_C_prefernce SET usertrue=".(($usertrue) ? '1' : '0').",countinmain=$countinmain,countinpage=$countinpage,space=$space,extention='$extention' WHERE id=1";
         if(!mysql_query($sql)){
            header("location: pref.php?err=1");
            return;
         } else {    
            echo "<HTML><HEAD>
               <title>Изменение настроек авторефератов</title>
               </head><body bgcolor=buttonface>
               <h4>Настройки модуля авторефератов успешно сохранены</h4>
               <script><!--
                  setTimeout(\"window.close()\",2000);
               //--></script>
               </body></html>";
         }

      } else {
         echo "<HTML><HEAD>
            <title>Изменение настроек авторефератов</title>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">
            </head>
            <script>
            function KeyPress()
            {
               if(window.event.keyCode == 27) window.close();
            }
            </script>
            <BODY onKeyPress=\"KeyPress()\" bgcolor=buttonface>";

         switch($err){
            case 1:
               echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
            break;
            case 3:
               echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>Не введена вся информация или же неверные числовые данные. Попробуйте еще раз.</td></tr></table><br>";
            break;
         }

         list($usertrue,$countinmain,$countinpage,$space,$extention)=mysql_fetch_row(mysql_query("SELECT usertrue,countinmain,countinpage,space,extention FROM iws_arfiles_C_prefernce WHERE id=1"));

         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>
            <tr><td align=center class=usr>Настройки авторефератов</td></tr></table>
            <script><!--
            function submitr(fr)
            {
               if (fr.countinmain.value && fr.countinpage.value) { fr.submit(); } else { alert (\"Не введена вся информация!   \"); }
            }
            //--></script>
            <form method=\"post\" name=frm>
            <input type=hidden name=act value=prefOk>
            <table width=100% border=0 cellpadding=3 cellspacing=2 align=center>
            <tr><td align=right>Количество файлов на первой странице сайта</td><td><input name=countinmain size=4 maxlength=2 value=\"".$countinmain."\"> Не менее одного</td></tr>
            <tr><td align=right>Количество файлов на одной странице в архиве</td><td><input name=countinpage size=4 maxlength=3 value=\"".$countinpage."\"> Не менее одного</td></tr>
            <tr><td colspan=2><hr></td></tr>
            <tr valign=top><td align=right>Разрешить добавлять файлы пользователям<br><br></td><td>
            <input class=chk type=checkbox name=\"usertrue\"";

            if($usertrue){ echo " checked"; }

            echo "></td></tr>
            <tr valign=top><td align=right>Максимальный объем файла на закачку</td><td><input name=space size=4 maxlength=2 value=\"".$space."\"> Mb<br>Если установите 0Mb, то ограничений не будет</td></tr>
            <tr valign=top><td align=right>Разрешенные типы файлов<br>(через запятую)</td><td><input name=extention size=60 value=\"".$extention."\"><br>Например: doc,docx,ppt,xls,zip,jpg<br>
            Если оставите поле пустым, то можно закачивать файлы любых типов</td></tr>
            <tr><td colspan=2><hr></td></tr>
            <tr><td></td><td align=right><input class=but type=button name=btn value=Сохранить onClick=\"submitr(frm)\">
            &nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"window.close()\"></td></tr>
            </form></table>
            </body></html>";
      }
?>