<?php

include('../../exit.php');

include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("�� ���� ������������ � ����");
@mysql_select_db($dbname) or die("�� ���� ������� ����");
mysql_query('SET NAMES "cp1251"');

      if(isset($act) && $act == "prefOk"){
         $countinmain=substr(trim($countinmain),0,2);
         $countinpage=substr(trim($countinpage),0,3);
         $nWidthS=substr(trim($nWidthS),0,3);
         $nWidthM=substr(trim($nWidthM),0,3);

         if(empty($countinmain) || empty($countinpage) || empty($nWidthS) || empty($nWidthM) || !is_numeric($countinmain) || !is_numeric($countinpage) || !is_numeric($nWidthS) || !is_numeric($nWidthM)) { 
            header("location: pref.php?err=3");
            return;
         }
      
        
         $sql="UPDATE iws_art_prefernce SET countinmain=$countinmain,countinpage=$countinpage,nWidthS=$nWidthS,nWidthM=$nWidthM,";
       
        if($rss){ $sql.="rss=1"; } else { $sql.="rss=0"; }
     
        $sql.=" WHERE id=1";
         if(!mysql_query($sql)){

            header("location: pref.php?err=1");
            return;
         } else {    
            echo "<HTML><HEAD>
               <title>��������� �������� ���������� ��������</title>
               </head><body bgcolor=buttonface>
               <h4>��������� ������ ���������� �������� ������� ���������</h4>
               <script><!--
                  setTimeout(\"window.close()\",2000);
               //--></script>
               </body></html>";
         }

      } else {
         echo "<HTML><HEAD>
            <title>��������� �������� ���������� ��������</title>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">
            </head>
            <script>
            function KeyPress()
            {
               if(window.event.keyCode == 27) window.close();
            }
            function kdd_keypress(){
               var kd = window.event.keyCode;
               if(kd!=8 && (kd<48 || kd>57)) window.event.keyCode=0;
            }

            </script>
            <BODY onKeyPress=\"KeyPress()\" bgcolor=buttonface>";

         switch($err){
            case 1:
               echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>��������� ������. ���������� ��� ���.</td></tr></table><br>";
            break;
            case 3:
               echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>�� ������� ��� ���������� ��� �� �������� �������� ������. ���������� ��� ���.</td></tr></table><br>";
            break;
         }

         list($countinmain,$countinpage,$nWidthS,$nWidthM,$rss)=mysql_fetch_row(mysql_query("SELECT countinmain,countinpage,nWidthS,nWidthM,rss FROM iws_art_prefernce WHERE id=1"));
         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>
            <tr><td align=center class=usr>��������� ���������� ��������</td></tr></table>
            <script><!--
            function submitr(fr)
            {
               if (fr.countinmain.value && fr.countinpage.value) { fr.submit(); } else { alert (\"�� ������� ��� ����������!   \"); }
            }
            //--></script>
            <form method=\"post\" name=frm>
            <input type=hidden name=act value=prefOk>
            <table width=100% border=0 cellpadding=3 cellspacing=2 align=center>
            <tr><td align=right>���������� �������� �� ������� ��������</td><td><input name=countinmain size=4 maxlength=2 value=\"".$countinmain."\"> �� ����� �����
            <script LANGUAGE=\"JavaScript\" FOR=\"countinmain\" EVENT=\"onkeypress\"> kdd_keypress(); </script>
            </td></tr>
            <tr><td align=right>���������� �������� � �������</td><td><input name=countinpage size=4 maxlength=3 value=\"".$countinpage."\"> �� ����� �����
            <script LANGUAGE=\"JavaScript\" FOR=\"countinpage\" EVENT=\"onkeypress\"> kdd_keypress(); </script>
            </td></tr>
            <tr><td colspan=2><hr></td></tr>
            <tr><td align=right>������ �������� �� ������� ��������: </td><td><input name=\"nWidthM\" size=4 maxlength=3 value=\"$nWidthM\"> ��������
            <script LANGUAGE=\"JavaScript\" FOR=\"nWidthM\" EVENT=\"onkeypress\"> kdd_keypress(); </script>
            </td></tr>
            <tr><td align=right>������ �������� � �������: </td><td><input name=\"nWidthS\" size=4 maxlength=3 value=\"$nWidthS\"> ��������
            <script LANGUAGE=\"JavaScript\" FOR=\"nWidthS\" EVENT=\"onkeypress\"> kdd_keypress(); </script>
            </td></tr>
         <tr valign=top><td align=right> �������� RSS<br><br></td><td>
            <input class=chk type=checkbox name=\"rss\"";

            if($rss){ echo " checked"; }

            

            echo "></td></tr>
            <tr><td colspan=2><hr></td></tr>
            <tr><td></td><td align=right><input class=but type=button name=btn value=��������� onClick=\"submitr(frm)\">
            &nbsp;&nbsp<input class=but type=button value=\"������\" onclick=\"window.close()\"></td></tr>
            </form></table>
            </body></html>";
      }

?>