<?php

session_start();
session_register("mainadvar");
include('../../../../../inc/config.inc.php');

?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
   <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
   <script type="text/javascript" src="../../utils/mctabs.js"></script>
   <script type="text/javascript" src="../../utils/form_utils.js"></script>
   <script type="text/javascript" src="../../utils/validate.js"></script>
   <script type="text/javascript" src="js/linkMenu.js"></script>
<STYLE TYPE="text/css">
BODY   {font-family:Arial; font-size:8px; BACKGROUND-COLOR:buttonface}
input.Ok {border: 1px #000000 solid; color: #ffffff;background-color: #6D6D6D; font-size:7pt;}
TABLE  {font-family:Arial; font-size:11px}
input {border: 1px #6C6C6C solid; font-size:8pt;}
input.chk {border: 0px #ffffff solid;}
select {border: 1px #6C6C6C solid; font-size:8pt;}
hr {color:#6C6C6C; height:1pt}
</STYLE>
<title>������� ����������� �� ������ ����</title>
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
   var mnname;
   var ck;
   switch(urlt){

   case "?go=guestbook":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=qa", 0);
   break;

   case "?go=guestbookN":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=guestbook", 0);
   break;

   case "?go=filearchive":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=filesarchive", 0);
   break;

   case "?go=filearchive_A":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=filesarchive_A", 0);
   break;

   case "?go=filearchive_B":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=filesarchive_B", 0);
   break;

   case "?go=filearchive_C":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=filesarchive_C", 0);
   break;

   case "?go=photoalbums":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=photosA", 0);
   break;


   case "?go=docsarchive":
      mnname=menuContent.SelectedItem.Text;
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php?go=docsarchive", 0);
   break;

   default:
      if(chd.checked){
         ck=1;
      } else {
         ck=0;
      }

      if(ck){
         mnname='[/:submenu|' + mainAr[menuContent.SelectedItem.index-1][5] + ']';
      }else{
         mnname=menuContent.SelectedItem.Text;
      }
      insertActionMenu(mnname,"<?php echo $hostName; ?>/index.php"+urlt, ck);
   break;
   }
   tinyMCEPopup.close();
// -->
</SCRIPT>
<TABLE width=100% HEIGHT=100% CELLPADDING="5" border="0">
<?php

include('../../../../menu/menu.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("�� ���� ������������ � ����");
@mysql_select_db($dbname) or die("�� ���� ������� ����");
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

echo "<tr><td><select style=\"width:100%\" onchange=\"window.location.href='dialog.php?blok='+ this.value\">\n";
$result=mysql_query("select ".$fieldnmb['did'].",".$fieldnmb['nme']." from ".$batbl." where ".$fieldnmb['lng']."='".$mainadvar['lng']."' ORDER BY ".$fieldnmb['did']);
if(mysql_numrows($result)>=1){
   while($arr=mysql_fetch_row($result)){
      echo "<option value=".$arr[0];
      if($arr[0]==$blok)   echo " selected";
      echo ">".$arr[1]."</option>\n";
   }
}
      echo "</select></td></tr><tr align=center><td height=100% width=100%>\n";

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
      ."<script LANGUAGE=\"JavaScript\" FOR=\"menuContent\" EVENT=\"Click()\"> menuContent_Click(); </script>\n"
      ."\n<script><!--\n"
      ."var tvwChild = 4;\n"
      ."var urlt;\n"
      ."var mnname;\n"
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

      } else if($arr[1]=="?go=articles"){
         $resGB=mysql_query("select id,name from iws_art_department");
         if(mysql_numrows($resGB)>=1){
            $countGB = (mysql_numrows($resGB)*2);
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".($arr[3]+$countGB).",".$arr[4].",".$arr[5].");\n";
            $to = $arr[2]+1;
            while($arrGB=mysql_fetch_row($resGB)){
               echo "mainAr[".$i++."] = Array(\"".$arrGB[1]."\",\"?go=articles&orderBy=".$arrGB[0]."\",".($to++).",".($to++).",2,".$arrGB[0].");\n";          
            }
         } else {
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].");\n";
         }
         mysql_free_result($resGB);

      } else if($arr[1]=="?go=filearchive"){
         $resGB=mysql_query("select id,name from iws_arfiles_department");
         if(mysql_numrows($resGB)>=1){
            $countGB = (mysql_numrows($resGB)*2);
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".($arr[3]+$countGB).",".$arr[4].",".$arr[5].");\n";
            $to = $arr[2]+1;
            while($arrGB=mysql_fetch_row($resGB)){
               echo "mainAr[".$i++."] = Array(\"".$arrGB[1]."\",\"?go=filesarchive&orderBy=".$arrGB[0]."\",".($to++).",".($to++).",2,".$arrGB[0].");\n";          
            }
         } else {
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].");\n";
         }
         mysql_free_result($resGB);

      } else if($arr[1]=="?go=filearchive_A"){
         $resGB=mysql_query("select id,name from iws_arfiles_A_department");
         if(mysql_numrows($resGB)>=1){
            $countGB = (mysql_numrows($resGB)*2);
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".($arr[3]+$countGB).",".$arr[4].",".$arr[5].");\n";
            $to = $arr[2]+1;
            while($arrGB=mysql_fetch_row($resGB)){
               echo "mainAr[".$i++."] = Array(\"".$arrGB[1]."\",\"?go=filesarchive_A&orderBy=".$arrGB[0]."\",".($to++).",".($to++).",2,".$arrGB[0].");\n";          
            }
         } else {
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].");\n";
         }
         mysql_free_result($resGB);

      } else if($arr[1]=="?go=filearchive_B"){
         $resGB=mysql_query("select id,name from iws_arfiles_B_department");
         if(mysql_numrows($resGB)>=1){
            $countGB = (mysql_numrows($resGB)*2);
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".($arr[3]+$countGB).",".$arr[4].",".$arr[5].");\n";
            $to = $arr[2]+1;
            while($arrGB=mysql_fetch_row($resGB)){
               echo "mainAr[".$i++."] = Array(\"".$arrGB[1]."\",\"?go=filesarchive_B&orderBy=".$arrGB[0]."\",".($to++).",".($to++).",2,".$arrGB[0].");\n";          
            }
         } else {
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].");\n";
         }
         mysql_free_result($resGB);

      } else if($arr[1]=="?go=filearchive_C"){
         $resGB=mysql_query("select id,name from iws_arfiles_C_department");
         if(mysql_numrows($resGB)>=1){
            $countGB = (mysql_numrows($resGB)*2);
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".($arr[3]+$countGB).",".$arr[4].",".$arr[5].");\n";
            $to = $arr[2]+1;
            while($arrGB=mysql_fetch_row($resGB)){
               echo "mainAr[".$i++."] = Array(\"".$arrGB[1]."\",\"?go=filesarchive_C&orderBy=".$arrGB[0]."\",".($to++).",".($to++).",2,".$arrGB[0].");\n";          
            }
         } else {
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].");\n";
         }
         mysql_free_result($resGB);

      } else if($arr[1]=="?go=photoalbums"){
         $resGB=mysql_query("select id,name from iws_photos_category");
         if(mysql_numrows($resGB)>=1){
            $countGB = (mysql_numrows($resGB)*2);
            echo "mainAr[".$i++."] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".($arr[3]+$countGB).",".$arr[4].",".$arr[5].");\n";
            $to = $arr[2]+1;
            while($arrGB=mysql_fetch_row($resGB)){
               echo "mainAr[".$i++."] = Array(\"".$arrGB[1]."\",\"?go=photosA&rubric=".$arrGB[0]."\",".($to++).",".($to++).",2,".$arrGB[0].");\n";          
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

function menuContent_Click(){
if(menuContent.SelectedItem){
   infoMenu(menuContent.SelectedItem);
}else{
   document.all[\"chd\"].checked = false; 
   document.all[\"chd\"].disabled = true; 
}
}

function menuContent_NodeClick(Node){
   infoMenu(Node);
   urlt = mainAr[Node.index-1][1];
   if(urlt=='?go=page') urlt+= \"&block=$blok&menu=\"+ mainAr[Node.index-1][5];
   Ok.disabled = false;
}

function infoMenu(Nde){\n
if(Nde.Children>0){
   document.all[\"chd\"].disabled = false;   
}else{
   document.all[\"chd\"].checked = false; 
   document.all[\"chd\"].disabled = true; 
}
}

window.focus();
//--></script>";
} else {
 echo "<center><b>� ������ ����� ��� �������� ������ ����!</b></center>";
}
echo "</td></tr>";
?>
<tr><td>
<input class=chk name=chd TYPE=checkbox disabled> ������������� ����������� ������ �� �������� ������ ����
<hr></td></tr>
<tr><td align=right>
<input ID=Ok TYPE=SUBMIT value="�������" DISABLED>&nbsp;&nbsp;
<input type="button" id="cancel" name="cancel" value="��������" onclick="tinyMCEPopup.close();" />
</td></tr>
</TABLE>
</BODY>
</HTML>