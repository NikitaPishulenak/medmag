<?php

session_start();
session_register("mainadvar");
   include('../../inc/config.inc.php');
?>
<HTML>
<HEAD>
<STYLE TYPE="text/css">
BODY   {font-family:Arial; font-size:8px; BACKGROUND-COLOR:buttonface}
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
TABLE  {font-family:Arial; font-size:11px}
input {border: 1px #6C6C6C solid; font-size:8pt;}
input.chk {border: 0px #ffffff solid;}
select {border: 1px #6C6C6C solid; font-size:8pt;}
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>Вставка гиперссылки из пункта меню</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</HEAD>
<script>
function KeyPress()
{
   if(window.event.keyCode == 27)
      window.close();
}
</script>
<BODY onKeyPress="KeyPress()">
<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>
<!--
   if(urlt == "?go=guestbook"){
      opener.InsertLinkM("<?php echo $hostName; ?>/index.php?go=qa");
   } else {
      opener.InsertLinkM("<?php echo $hostName; ?>/index.php"+urlt);
   }
   window.close();
// -->
</SCRIPT>
<TABLE width=100% HEIGHT=100% CELLPADDING="5" border="0">
<?php

global $blok;
include('../menu/menu.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

if(!isset($blok) || !$blok){
   if(isset($mainadvar['menuhp']) && $mainadvar['menuhp']){
      $blok=$mainadvar['menuhp'];
   }else{
      list($blok)=mysql_fetch_row(mysql_query("select ".$fieldnmb['did']." from ".$batbl." where ".$fieldnmb['del']."<=0 and ".$fieldnmb['lng']."='".$mainadvar['lng']."'"));
   }
}else{
   $mainadvar['menuhp'] = $blok;
}

   $sql="select ".$fieldnmm['descr'].",".$fieldnmm['url2'].",".$fieldnmm['left'].",".$fieldnmm['right']
   .",".$fieldnmm['level'].",".$fieldnmm['did']." from ".$matbl." where ".$fieldnmm['block']."=".$blok." and ".$fieldnmm['type']."<>3 order by ".$fieldnmm['left'];

echo "<tr><td><select style=\"width:100%\" onchange=\"window.location.href='dialog.php?evtype=shMenu&blok='+ this.value\">\n";
$result=mysql_query("select ".$fieldnmb['did'].",".$fieldnmb['nme']." from ".$batbl." where ".$fieldnmb['lng']."='".$mainadvar['lng']."' ORDER BY ".$fieldnmb['did']);
if(mysql_numrows($result)>=1){
   while($arr=mysql_fetch_row($result)){
      echo "<option value=".$arr[0];
      if($arr[0]==$blok)   echo " selected";
      echo ">".$arr[1]."</option>\n";
   }
}
      echo "</select></td></tr>"
      ."\n<tr align=center><td height=100% width=100%>\n";
$res=mysql_query($sql);
if(mysql_numrows($res)>=1){
      echo "<object ID=\"menuContent\" CLASSID=\"clsid:C74190B6-8589-11D1-B16A-00C0F0283628\" VIEWASTEXT WIDTH=\"100%\" HEIGHT=\"100%\" CODEBASE=\"http://activex.microsoft.com/controls/vb6/mscomctl.cab#Version=6,1,97,82\">\n"
      ."<param name=Style value=6>\n"
      ."<param name=LineStyle value=1>\n"
      ."<param name=BorderStyle value=1>\n"
      ."<param name=Appearance value=0>\n"
      ."<param name=LabelEdit value=1>\n"
      ."<param name=HideSelection value=false>\n"
      ."</object>\n"
      ."<script LANGUAGE=\"JavaScript\" FOR=\"menuContent\" EVENT=\"NodeClick(Node)\"> menuContent_NodeClick(Node); </script>\n"
      ."<script><!--\n"
      ."var tvwChild = 4;\n"
      ."var urlt;\n"
      ."var mainAr = new Array();\n";
   $i=0;
   $lev = 1;
   while($arr=mysql_fetch_row($res)){
      if($arr[1]=="?go=guestbook"){
         $resGB=mysql_query("select id,name from iws_guestbk_category");
         if(mysql_numrows($resGB)>=1){
            $countGB = (mysql_numrows($resGB)*2);
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".($arr[3]+$countGB).",".$arr[4].",".$arr[5].");\n";
            $to = $arr[2]+1;
            while($arrGB=mysql_fetch_row($resGB)){
               echo "mainAr[".$i++."] = Array(\"".$arrGB[1]."\",\"?go=qa&category=".$arrGB[0]."\",".($to++).",".($to++).",2,".$arrGB[0].");\n";          
            }
         } else {
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].");\n";
         }
         mysql_free_result($resGB);
      } else {
         echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].");\n";
      }
      if($lev > $arr[4] ) $lev = $arr[4];
   }
   echo "
function SpecToSymbol(text)
{
   text = text.replace(/&laquo;/g, String.fromCharCode(171));
   text = text.replace(/&raquo;/g, String.fromCharCode(187));
   text = text.replace(/&quot;/g, String.fromCharCode(34));
   text = text.replace(/&#39;/g, String.fromCharCode(39));
   text = text.replace(/&#44;/g, String.fromCharCode(44));

   return text;
}
   var nme = new Array;\n for (i=0; i<mainAr.length; i++)
   {
      if (mainAr[i][4]<=".$lev."){
         menuContent.Nodes.Add(null,null,mainAr[i][0]+'xwz'+i+'xwz',SpecToSymbol(mainAr[i][0]));
         nme[mainAr[i][4]] = mainAr[i][0]+'xwz'+i+'xwz';
      } else if (mainAr[i][3]-mainAr[i-1][2]!=1) {
         menuContent.Nodes.Add(nme[mainAr[i][4]-1],tvwChild,mainAr[i][0]+'xwz'+i+'xwz',SpecToSymbol(mainAr[i][0]));                 
         nme[mainAr[i][4]] = mainAr[i][0]+'xwz'+i+'xwz';
      } else if (mainAr[i][3]-mainAr[i-1][2]==1) {
         menuContent.Nodes.Add(nme[mainAr[i][4]-1],tvwChild,mainAr[i][0]+'xwz'+i+'xwz',SpecToSymbol(mainAr[i][0]));                 
      }
   }\n";
echo "if(menuContent.Nodes.Item(1).Children>0) menuContent.Nodes.Item(1).Expanded = true;\n";
echo "


function menuContent_NodeClick(Node){
   var re = new RegExp(\"(go=qa&category+)\",\"gi\") ;
   urlt = mainAr[Node.index-1][1];
   if(mainAr[Node.index-1][1]!='?go=main' && mainAr[Node.index-1][1]!='?go=guestbook' && re.exec(mainAr[Node.index-1][1]) == null)  urlt+= \"&block=$blok&menu=\"+ mainAr[Node.index-1][5];
   Ok.disabled = false;
   if(mainAr[Node.index-1][1]=='?go=pst' || mainAr[Node.index-1][1]=='?go=gid') Ok.disabled = true;
}

window.focus();
//--></script>";
} else {
 echo "<center><b>В данном блоке нет ниодного пункта меню!</b></center>";
}
echo "</td></tr>";
?>
<tr><td align=right>
<hr>
<input ID=Ok TYPE=SUBMIT value="Выбрать" DISABLED>&nbsp;&nbsp;
<input TYPE=BUTTON ONCLICK="window.close();" value="Отмена">
</td></tr>
</TABLE>