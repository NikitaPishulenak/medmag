<?php

include('../../exit.php');

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

    if(isset($act) && $act == "prefOk"){
         $nWidth=substr(trim($nWidth),0,3);
         $nHeight=substr(trim($nHeight),0,3);
         $countInMain=substr(trim($countInMain),0,3);
         $countInPage=substr(trim($countInPage),0,3);

         if(empty($nWidth) || empty($nHeight) || empty($countInMain) || empty($countInPage) || !is_numeric($nWidth) || !is_numeric($nHeight) || !is_numeric($countInMain) || !is_numeric($countInPage)) { 
            header("location: pref.php?err=3");
            return;
         }
         $sql="UPDATE iws_photos_prefernce SET nWidth=$nWidth, nHeight=$nHeight, countInMain=$countInMain, countInPage=$countInPage WHERE id=1";
         if(!mysql_query($sql)){
            header("location: pref.php?err=1");
            return;
         } else {    
            echo "<HTML><HEAD>
               <title>Изменение настроек фотогалереи</title>
               </head><body bgcolor=buttonface>
               <h4>Настройки модуля фотогалереи успешно сохранены</h4>
               <script><!--
                  setTimeout(\"window.close()\",2000);
               //--></script>
               </body></html>";
         }

      } else {
         echo "<HTML><HEAD>
            <title>Изменение фотогалереи</title>
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

         list($nWidth,$nHeight,$countInMain,$countInPage)=mysql_fetch_row(mysql_query("SELECT nWidth,nHeight,countInMain,countInPage FROM iws_photos_prefernce WHERE id=1"));

         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>
            <tr><td align=center class=usr>Настройки фотогалереи</td></tr></table>
            <script><!--
            function submitr(fr)
            {
               if (fr.nWidth.value && fr.nHeight.value && fr.countInMain.value && fr.countInPage.value) { fr.submit(); } else { alert (\"Не введена вся информация!   \"); }
            }
            //--></script>
            <form method=\"post\" name=frm>
            <input type=hidden name=act value=prefOk>
            <table width=100% border=0 cellpadding=3 cellspacing=2 align=center>
            <tr><td align=right>Ширина превью изображения</td><td><input name=nWidth size=4 maxlength=3 value=\"".$nWidth."\"></td></tr>
            <tr><td align=right>Высота превью изображения</td><td><input name=nHeight size=4 maxlength=3 value=\"".$nHeight."\"></td></tr>
            <tr><td colspan=2><hr></td></tr>
            <tr><td align=right>Количество альбомов на главной</td><td><input name=countInMain size=4 maxlength=3 value=\"".$countInMain."\"></td></tr>
            <tr><td align=right>Количество альбомов в рубрике</td><td><input name=countInPage size=4 maxlength=3 value=\"".$countInPage."\"></td></tr>
            <tr><td colspan=2><hr></td></tr>
            <tr><td></td><td align=right><input class=but type=button name=btn value=Сохранить onClick=\"submitr(frm)\">
            &nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"window.close()\"></td></tr>
            </form></table>
            </body></html>";
      }
?>