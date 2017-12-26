<?php

include('../../exit.php');

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

    if(isset($act) && $act == "prefOk"){
         $lmt=trim($lmt);
         $lmt=substr($lmt,0,2);
         $cont=trim($cont);
         $maxlen=trim($maxlen);
         $maxlen=substr($maxlen,0,4);
         if(empty($lmt) || !is_numeric($lmt) || $lmt<=0 || empty($maxlen) || !is_numeric($maxlen) || $maxlen<=0 || ($rd3 && empty($cont))) { 
            header("location: pref.php?err=3");
            return;
         }
         $sql="update iws_guestpref set limt=$lmt,maxlen=$maxlen,";

         if($rd1){ $sql.="coment=1,";  } else { $sql.="coment=0,";  }
         if($rd2){ $sql.="vivod=1,";  } else { $sql.="vivod=0,";  }
         if($rd3){ $sql.="rtr=1,";  } else { $sql.="rtr=0,";  }
         if($rd4){ $sql.="capcha=1,"; } else { $sql.="capcha=0,"; }
         if($rd5){ $sql.="moder=1,";  } else { $sql.="moder=0,"; }             

         $sql.="rulesru='$cont' where id=1";

         if(!mysql_query($sql)){
            header("location: pref.php?err=1");
            return;
         } else {    
            echo "<HTML><HEAD>
            <title>Изменение настроек Вопрос/Ответ</title>
            </head><body bgcolor=buttonface>
            <h4>Настройки модуля Вопрос/Ответ успешно сохранены</h4>
            <script><!--
            setTimeout(\"window.close()\",2000);
            //--></script>
            </body></html>";
         }

      }else{
         echo "<HTML><HEAD>
         <title>Изменение настроек Вопрос/Ответ</title>
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
       
         list($lmt,$vv,$cot,$rt,$capcha,$rls,$moder,$maxlen)=mysql_fetch_row(mysql_query("select limt,vivod,coment,rtr,capcha,rulesru,moder,maxlen from iws_guestpref where id=1"));
         
         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>
         <tr><td align=center class=usr>Настройки вывода записей Вопрос/Ответ</td></tr></table>
         <script><!--
            function submitr(fr){
            if (fr.lmt.value) {
               if((fr.rd3.checked && fr.cont.value) || !fr.rd3.checked){
                  fr.submit();
               } else {
                  alert (\"Не описаны правила пользования Вопрос/Ответ!   \");
                  fr.cont.focus();
               }
            } else {
               alert (\"Не указано максимальное число выводимых записей на одной странице!   \");
               fr.lmt.focus();
            }
            }
            //--></script>
         <form method=\"post\" name=frm>
         <input type=hidden name=act value=prefOk>
         <table width=100% border=0 cellpadding=3 cellspacing=2 align=center>
         <tr><td width=40% align=right>Max. число записей на одной странице: </td><td><input name=\"lmt\" size=1 maxlength=2 value=\"".$lmt."\"> (Не менее одной)&nbsp;&nbsp;&nbsp;Max. размер сообщения: <input name=\"maxlen\" size=1 maxlength=5 value=\"".$maxlen."\"> </td></tr>
         <tr><td align=right>Разрешить добавление комментариев: </td><td>
         <input class=chk type=checkbox name=\"rd1\"";

         if($cot){ echo " checked"; }

         echo "></td></tr><tr><td align=right>Отображать число страниц целым массивом: </td><td><input class=chk type=checkbox name=\"rd2\"";

         if($vv){ echo " checked"; }

         echo "></td></tr><tr><td align=right>Включить капчу: </td><td><input class=chk type=checkbox name=\"rd4\"";

         if($capcha){ echo " checked"; }

         echo ">&nbsp;&nbsp;&nbsp;Включить модерирование:&nbsp;&nbsp;&nbsp;<input class=chk type=checkbox name=\"rd5\"";

         if($moder){ echo " checked"; }
                
         echo "></td></tr>
                 
         <tr><td align=center class=usr colspan=2>Правила пользования Вопрос/Ответ</td></tr>
         <tr><td align=right>Разрешить показ правил пользования: </td><td>
         <input class=chk type=checkbox name=\"rd3\"";

         if($rt){ echo " checked"; }

         echo "></td></tr>
         <tr valign=top><td align=right>Правила пользования Вопрос/Ответ:</td><td><textarea name=cont rows=7 cols=50>".stripslashes($rls)."</textarea></td></tr>
         <tr><td colspan=2><hr></td></tr>
         <tr><td></td><td align=right><input class=but type=button name=btn value=Сохранить onClick=\"submitr(frm)\">
         &nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"window.close()\"></td></tr>
         </form></table>
         </body></html>";
                
      }

?>