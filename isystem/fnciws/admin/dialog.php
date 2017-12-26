<?php
include('../../exit.php');

if($act=="addG"){

?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
input {border: 1px #6C6C6C solid; font-size:7pt;}
td.wrd {font-family:Arial; font-size:8pt; }
</STYLE>
<title>Новая группа редакторов</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</HEAD>
<script>
function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}
</script>
<BODY onKeyPress="KeyPress()" bgcolor="buttonface">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
if(nme.value){
  window.returnValue = nme.value;
  window.close();
}else{
 alert("Не введено имя группы!     ");
   nme.focus();
}
//-->
</SCRIPT>
<br>
<TABLE CELLPADDING="0" width=100% border="0">
  <TR>
    <TD align=right class=wrd><nobr>Имя группы:</nobr></td>
    <TD  width=230>
      <input name="nme" maxlength=30 style="width:100%">
      </TD>
  </TR>
</TABLE>
<CENTER><br>
<input ID=Ok TYPE=SUBMIT value="Добавить">&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</CENTER>
</body></html>
<?php

}elseif($act=="dtG"){

include('admc.inc.php');
include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

   if($rt == "dtOk"){
      $sql="update iws_admin_group set ";
      $i=0;
      $rt="";
      if($cn){
         while(list($key)=each($cn)){
            if(!$i){
               $rt=$key;
               $i++;
            }else{
              $rt.="-".$key;
            }
         }
      }
      $sql.=$gfld['cnt']."='$rt',";
      $i=0;
      $rt="";
      if($pr){
         while(list($key)=each($pr)){
            if(!$i){
               $rt=$key;
               $i++;
            }else{
              $rt.="-".$key;
            }
         }
      }
         $sql.=$gfld['prf']."='$rt',";
      if($bld){
         $sql.="bld=1";
      }else{
         $sql.="bld=0";
      }     
      $sql.=" where ".$gfld['did']."=$grp";
         if(!mysql_query($sql)){
            header("location: dialog.php?act=dtG&err=1&grp=$grp");
            return;
         } else {    
            echo "<HTML><HEAD>"
            ."<title>Установка прав доступа</title>"
            ."</head><body bgcolor=buttonface>"
            ."<h4>Права доступа успешно сохранены</h4>"
           ."<script><!--\n"
               ."setTimeout(\"window.close()\",2000);\n"
               ."//--></script>"
               ."</body></html>";
         }

      }else{
         echo "<HTML><HEAD>"
         ."<title>Установка прав доступа</title>"
         ."<link rel=\"stylesheet\" type=\"text/css\" href=\"../../style.css\">"
         ."</head>"
         ."<script>\n"
         ."function KeyPress()\n"
         ."{\n"
         ."if(window.event.keyCode == 27) window.close();\n"
         ."}\n"
         ."</script>\n"
         ."<BODY onKeyPress=\"KeyPress()\" bgcolor=buttonface>";
         if($err==1){
            echo "<table width=100% border=0 cellpadding=3 cellspacing=2><tr><td class=error align=center>"
            ."Произошла ошибка. Попробуйте еще раз.</td></tr></table><br>";
         }
         list($nme,$cnt,$prf,$msg,$bld)=mysql_fetch_row(mysql_query("select ".$gfld['nme'].",".$gfld['cnt'].",".$gfld['prf'].",".$gfld['msg'].",bld from iws_admin_group where ".$gfld['did']."=$grp"));
         echo "<table width=100% border=0 cellpadding=3 cellspacing=0>"
         ."<tr><td align=center class=usr>Права доступа группы $nme</td></tr></table>\n"
         ."<form method=\"post\" name=frm action=\"dialog.php\">\n"
         ."<input type=hidden name=act value=dtG>\n"
         ."<input type=hidden name=rt value=dtOk>\n"
         ."<input type=hidden name=grp value=$grp>\n"
         ."<table width=100% border=0 cellpadding=3 cellspacing=1 align=center>\n"
         ."<tr><td><input type=checkbox class=chk name=bld";
         if($bld) echo " checked";        
         echo "></td><td><b>управление блоками</b></td></tr>\n"
         ."<tr><td colspan=2><b>Управление содержанием:</b></td></tr>\n";
         $result=mysql_query("select bid, name from iws_blockmenu"); 
         if(mysql_numrows($result)>=1){
            $cnt=explode("-",$cnt);
            while($arr=mysql_fetch_row($result)){
               echo "<tr><td> <input type=checkbox class=chk name=cn[".$arr[0]."]";
                  for($i=0;$i<=count($cnt)-1;$i++){
                     if($arr[0]==$cnt[$i]) echo " checked";          
                  }
               echo "></td><td>".$arr[1]."</td></tr>\n";
            }
         }
         echo "<tr><td colspan=2><b>Сервис:</b></td></tr>\n";
         $result=mysql_query("select urlmenu,name from iws_menu_pref where urlmenu<>'admin'");  
         if(mysql_numrows($result)>=1){
            $prf=explode("-",$prf);
            while($arr=mysql_fetch_row($result)){
               echo "<tr><td> <input type=checkbox class=chk name=pr[".$arr[0]."]";
                  for($i=0;$i<=count($prf)-1;$i++){
                     if($arr[0]==$prf[$i]) echo " checked";          
                  }
               echo "></td><td>".$arr[1]."</td></tr>\n";
            }
         }
         echo "<tr><td colspan=2><hr></td></tr>\n"
         ."<tr><td align=center colspan=2><input class=but type=submit value=Сохранить>"
         ."&nbsp;&nbsp<input class=but type=button value=\"Отмена\" onclick=\"window.close()\"></td></tr>"
         ."</form></table>"
         ."</body></html>";
      }

}

?>