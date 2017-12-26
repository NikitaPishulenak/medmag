<?php

function showmn(){
global $mainadvar,$blok,$err;
include('fnciws/menu/menu.inc.php');

$adm=0;

if($mainadvar['sadm']){
   $adm=1;
   $result=mysql_query("select ".$fieldnmb['did'].",".$fieldnmb['nme'].",".$fieldnmb['act'].",".$fieldnmb['del'].",".$fieldnmb['ug'].",".$fieldnmb['nd']." from ".$batbl." WHERE ".$fieldnmb['lng']."='".$mainadvar['lng']."' ORDER BY ".$fieldnmb['did']);
   $dst = 1;
   $blokEDIT = 1;
   if(!isset($blok) || !$blok)
      list($blok)=mysql_fetch_row(mysql_query("select ".$fieldnmb['did']." from ".$batbl." where ".$fieldnmb['del']."<=0 and ".$fieldnmb['lng']."='".$mainadvar['lng']."'"));
} else {
   if($mainadvar['cnt']){  
      $cnt=explode("-",$mainadvar['cnt']);
      for($i=0;$i<=count($cnt)-1;$i++){
         if(!$i){
            //if(!isset($blok) || !$blok) $blok=$cnt[$i];
            $sql=$fieldnmb['did']."=".$cnt[$i];
         }else{
            $sql.=" or ".$fieldnmb['did']."=".$cnt[$i];
         }
      }
      $result=mysql_query("select ".$fieldnmb['did'].",".$fieldnmb['nme'].",".$fieldnmb['act'].",".$fieldnmb['del'].",".$fieldnmb['ug'].",".$fieldnmb['nd']." from ".$batbl." where ".$fieldnmb['lng']."='".$mainadvar['lng']."' and (".$sql.") ORDER BY ".$fieldnmb['did']);
      $dst = 1;
   }else{
     $dst = 0;
   }
   list($blokEDIT)=mysql_fetch_row(mysql_query("select bld from iws_admin_group where id=".$mainadvar['grop']));
}


if($dst && mysql_numrows($result)>=1){

   if(!$mainadvar['sadm'] && (!isset($blok) || !$blok)){
      list($blok)=mysql_fetch_row($result);
      mysql_data_seek($result,0);
   }

   list($countbl) = mysql_fetch_row(mysql_query("select count(".$fieldnmb['did'].") from ".$batbl." WHERE ".$fieldnmb['lng']."='".$mainadvar['lng']."'"));

   $ret.="<tr><td bgcolor=#808080>&nbsp;<font color=#ffffff> <b>блоки меню</b></font></td></tr><tr><td>";
   if(isset($blokEDIT) && $blokEDIT){
      $ret.="
      <img class=\"tb\" src=\"images/newblock.gif\" border=0 alt=\"Новый блок меню\" style=\"cursor:hand\" onclick=\"addBl();\" onmouseover=\"this.className='tbovr';\"  onmouseout=\"this.className='tb';\" onmousedown=\"this.className='tbdwn';\"> 
      <img class=\"tb\" name=\"abl\" src=\"images/activeblock.gif\" border=0 alt=\"Настройки блока меню\" style=\"cursor:hand\" onclick=\"if(!this.gray) actBl()\" onmouseover=\"if(!this.gray) this.className='tbovr';\"  onmouseout=\"if(!this.gray) this.className='tb';\" onmousedown=\"if(!this.gray) this.className='tbdwn';\"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"\" class=\"spr\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <img class=\"tb\" name=\"dbl\" src=\"images/deleteblock.gif\" border=0 alt=\"Удалить блок меню\" style=\"cursor:hand\" onclick=\"if(!this.gray) delBl()\" onmouseover=\"if(!this.gray) this.className='tbovr';\"  onmouseout=\"if(!this.gray) this.className='tb';\" onmousedown=\"if(!this.gray) this.className='tbdwn';\">";
   }
   $ret.="\n<select style=\"width:100%\" onchange=\"parent.C.location='mainiws.php'; document.location='left.php?typ=content&blok='+ this.value\">";

$resu = mysql_query("select ".$ugfld['did'].",".$ugfld['nme']." from ".$ugtbl);
while($arn=mysql_fetch_row($resu)) $ardst[$arn[0]] = $arn[1];
$ardst[0] = "для всех";
$edtTr = 0;
if(mysql_numrows($result)>=1){
   while($arr=mysql_fetch_row($result)){
      $ret.="<option value=".$arr[0];
      if($arr[0]==$blok){
         $edtTr = $arr[5];
         $ret.=" selected";
         if(isset($blokEDIT) && $blokEDIT){
/*       if($arr[2]){
            $kodactiv="<script>document.all['abl'].src='images/unactiveblock.gif';\n document.all['abl'].alt='Деактивировать блок меню';</script>";
         }else{
            $kodactiv="<script>document.all['abl'].src='images/activeblock.gif';\n document.all['abl'].alt='Активировать блок меню';</script>";
         }
*/          if(!$arr[3]) $kodactiv.="\n<script>document.all['abl'].gray=true;\n
                         document.all['abl'].style.filter='alpha(opacity=25)';\n 
                         document.all['abl'].style.cursor='default';\n 
                         document.all['dbl'].style.filter='alpha(opacity=25)';\n 
                         document.all['dbl'].gray=true;\n 
                         document.all['dbl'].style.cursor='default';\n 
                         </script>";
         }
      }                                                                                                   

      $ret.=">".$arr[1]." - ";
      if($arr[2]){
         $ret.="акт / ".$ardst[$arr[4]];
      }else{
         $ret.="неакт / ".$ardst[$arr[4]];
     }
      $ret.="</option>\n";
   }
}

      $ret.="</select><br><br></td></tr>".$kodactiv
      ."\n <tr><td bgcolor=#808080>&nbsp;<font color=#ffffff> <b>структура блока меню</b></font></td></tr><tr><td id=\"toolbar\">"

      ."<img class=\"tb\" name=\"addmn\" onclick=\"if(!this.gray) addnewmn();\" src=\"images/newpage.gif\" border=0 alt=\"Новая страница\">"
      ."<img class=\"tb\" name=\"edtmnp\" onclick=\"if(!this.gray) editPage();\" src=\"images/editpage.gif\" border=0 alt=\"Редактировать\">"
      ."<img src=\"\" class=\"spr\">"
      ."<img class=\"tb\" name=\"edtmn\" onclick=\"if(!this.gray) edtMN();\" src=\"images/editmenu.gif\" border=0 alt=\"Редактировать меню\">";
   if(isset($blokEDIT) && $blokEDIT){
      $ret.="<img class=\"tb\" name=\"prfmn\" onclick=\"if(!this.gray) prfMN();\" src=\"images/prefmenu.gif\" border=0 alt=\"Свойства страницы (модуля)\">"
      ."<img class=\"tb\" name=\"rplmn\" onclick=\"if(!this.gray) rplMN();\" src=\"images/replacepage.gif\" border=0 alt=\"Переместить меню в другой блок\">";
   }

      $ret.="</td></tr>\n<tr align=center><td height=100% width=100% bgcolor=#C0C0C0>"

      ."<object ID=\"menuContent\" CLASSID=\"clsid:C74190B6-8589-11D1-B16A-00C0F0283628\" VIEWASTEXT WIDTH=\"100%\" HEIGHT=\"100%\" CODEBASE=\"http://activex.microsoft.com/controls/vb6/mscomctl.cab#Version=6,1,97,82\">"
      ."<param name=Style value=6>"                    
      ."<param name=LineStyle value=1>"
      ."<param name=BorderStyle value=0>"
      ."<param name=Appearance value=0>"
      ."<param name=LabelEdit value=1>"
      ."<param name=HideSelection value=false>"
      ."</object>"
   ."<form name=svn method=post action=\"fnciws/menu/allwindow.php\">
   <input type=hidden name=func value=newMN>
   <input type=hidden name=blok>
   <input type=hidden name=mnu>
   <input type=hidden name=newpage>
   <input type=hidden name=ttle>
   <input type=hidden name=robots>
   <input type=hidden name=descr>
   <input type=hidden name=keyw>
   </form>"
      ."<script LANGUAGE=\"JavaScript\" FOR=\"menuContent\" EVENT=\"NodeClick(Node)\"> menuContent_NodeClick(Node); </script>"
      ."<script LANGUAGE=\"JavaScript\" FOR=\"menuContent\" EVENT=\"Click()\"> menuContent_Click(); </script>"
      ."\n<script><!--\n"
      ."var tvwChild = 4;\n"
      ."var tvwPrevious = 3;\n"
      ."var tvwNext = 2;\n"
      ."var goMN;\n"
      ."var allEdt = ".$edtTr.";\n"
      ."var idBlok = ".$blok.";\n"
      ."var cntBlok = ".$countbl.";\n"
      ."var mainAr = new Array();

function SymbolToSpec(text)
{

   var re = new RegExp(String.fromCharCode(34),\"gi\");
   text = text.replace(re, \"&quot;\");
   re = new RegExp(String.fromCharCode(147),\"gi\");
   text = text.replace(re, \"&quot;\");
   re = new RegExp(String.fromCharCode(148),\"gi\");
   text = text.replace(re, \"&quot;\");
   re = new RegExp(String.fromCharCode(132),\"gi\");
   text = text.replace(re, \"&quot;\");
   re = new RegExp(String.fromCharCode(8220),\"gi\");
   text = text.replace(re, \"&quot;\");
   re = new RegExp(String.fromCharCode(8221),\"gi\");
   text = text.replace(re, \"&quot;\");
   re = new RegExp(String.fromCharCode(8222),\"gi\");
   text = text.replace(re, \"&quot;\");


   re = new RegExp(String.fromCharCode(171),\"gi\");
   text = text.replace(re, \"&laquo;\");
   re = new RegExp(String.fromCharCode(187),\"gi\");
   text = text.replace(re, \"&raquo;\");


   re = new RegExp(String.fromCharCode(39),\"gi\");
   text = text.replace(re, \"&#39;\");
   re = new RegExp(String.fromCharCode(130),\"gi\");
   text = text.replace(re, \"&#39;\");
   re = new RegExp(String.fromCharCode(145),\"gi\");
   text = text.replace(re, \"&#39;\");
   re = new RegExp(String.fromCharCode(146),\"gi\");
   text = text.replace(re, \"&#39;\");
   re = new RegExp(String.fromCharCode(180),\"gi\");
   text = text.replace(re, \"&#39;\");
   re = new RegExp(String.fromCharCode(8216),\"gi\");
   text = text.replace(re, \"&#39;\");
   re = new RegExp(String.fromCharCode(8217),\"gi\");
   text = text.replace(re, \"&#39;\");
   re = new RegExp(String.fromCharCode(8218),\"gi\");
   text = text.replace(re, \"&#39;\");

   re = new RegExp(String.fromCharCode(44),\"gi\");
   text = text.replace(re, \"&#44;\");

   return text;
}

function SpecToSymbol(text)
{
   text = text.replace(/&laquo;/g, String.fromCharCode(171));
   text = text.replace(/&raquo;/g, String.fromCharCode(187));
   text = text.replace(/&quot;/g, String.fromCharCode(34));
   text = text.replace(/&#39;/g, String.fromCharCode(39));
   text = text.replace(/&#44;/g, String.fromCharCode(44));

   return text;
}
";

  //клиентские функ. для работы с блоками меню
$ret.="
function delBl(){
   var mess = \"Предупреждение!\\n\\nПри удалении блока меню будут удалены\\nвсе пунткы меню и весь сопутствующий\\nконтент.\\n\\nВы действительно хотите удалить блок меню?            \"
   if(confirm(mess)){
      document.location='?typ=content&act=delBL&blok='+ idBlok;      
   }
}

function actBl()
{
      var arr = null;
      arr = showModalDialog(\"fnciws/menu/allwindow.php?func=actBL&bId=\" + idBlok, null, \"dialogWidth:450px; dialogHeight:180px; status:no;\");
      if (arr != null) document.location='?typ=content&act=actBL&dst='+ arr[\"dst\"] + '&inTemplate='+ arr[\"inTemplate\"] + '&activeB='+ arr[\"activeB\"] + '&blok='+ idBlok;
}

function addBl()
{
   arr = showModalDialog(\"fnciws/menu/allwindow.php?func=addBL\",null,\"dialogWidth:450px; dialogHeight:200px; status:no;\");
   if (arr != null)
   {
      if(arr[\"name\"]){
         document.location='?typ=content&act=addBL&nme='+ arr[\"name\"] + '&dst='+ arr[\"dst\"] + '&inTemplate='+ arr[\"inTemplate\"] + '&activeB='+ arr[\"activeB\"];
      } else {
         alert(\"Недопустимое название блока меню! Действие отменено.      \");
      }
   }     

}\n";
  //конец клиентских функ. для работы с блоками меню


$sql="select ".$fieldnmm['descr'].",".$fieldnmm['url2'].",".$fieldnmm['left'].",".$fieldnmm['right'].","
   .$fieldnmm['level'].",".$fieldnmm['type'].",".$fieldnmm['edt'].",".$fieldnmm['tm'].",idm"
   .",prefpath from iws_menu where ".$fieldnmm['block']."=$blok and ".$fieldnmm['level'].">0 order by ".$fieldnmm['left'];

$res=mysql_query($sql);
if(mysql_numrows($res)>=1){

   $adi = 0;
   while($arr=mysql_fetch_row($res)){
      $ret.="mainAr['".strtolower($arr[0])."xwz".($adi++)."xwz'] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].",".$arr[6].",".$arr[7].",".$arr[8].",\"".$arr[9]."\");\n";
   }
$ret.="
   var nme = new Array;\n var prekey;\n for (i in mainAr) 
   {
      if (mainAr[i][4]<=1){
         menuContent.Nodes.Add(null,null,i,SpecToSymbol(mainAr[i][0]));
         nme[mainAr[i][4]] = i;
      } else if (mainAr[i][3]-mainAr[prekey][2]==1) {
         menuContent.Nodes.Add(nme[mainAr[i][4]-1],tvwChild,i,SpecToSymbol(mainAr[i][0]));                  
      } else if (mainAr[i][3]-mainAr[prekey][2]!=1) {
         menuContent.Nodes.Add(nme[mainAr[i][4]-1],tvwChild,i,SpecToSymbol(mainAr[i][0]));                  
         nme[mainAr[i][4]] = i;
      }
      prekey = i;
   }\n";
$ret.="menuContent.Nodes.Item(nme[1]).Expanded = true;\n";
}
$ret.="
var adi = ".($adi++)."


function menuContent_Click(){\n

if(menuContent.SelectedItem){
   infoMenu(menuContent.SelectedItem.Key);
}else{
   document.all[\"addmn\"].gray = false;  
   document.all[\"addmn\"].style.filter='alpha(opacity=100)';
   document.all[\"addmn\"].style.cursor='hand';
}

}

function menuContent_NodeClick(Node){\n

   infoMenu(Node.Key);

}

function infoMenu(keyar){

if(allEdt){
   document.all[\"edtmnp\"].gray = false;       
   document.all[\"edtmnp\"].style.filter='alpha(opacity=100)';
   document.all[\"edtmnp\"].style.cursor='hand';";

if(isset($blokEDIT) && $blokEDIT){

         $ret.="
               if(!mainAr[keyar][9]){
               document.all[\"prfmn\"].style.filter='alpha(opacity=25)';
               document.all[\"prfmn\"].style.cursor='default';
               document.all[\"prfmn\"].gray = true;         
            }else{
               document.all[\"prfmn\"].style.filter='alpha(opacity=100)';
               document.all[\"prfmn\"].style.cursor='hand';
               document.all[\"prfmn\"].gray = false;        
            }
         ";
}

$ret.=" }else{

var t = Array(toolbar);
for(j=0; j<t.length; j++)
{
   elements = t[j].all;
   for (i=0; i<elements.length; i++) 
   {
      if (elements[i].tagName != \"IMG\") 
            continue;

      if(elements[i].className==\"tb\") 
         {
            if(elements[i].name==\"prfmn\"  && mainAr[keyar][5]==1){

                  elements[i].gray = true;   
                  elements[i].style.filter='alpha(opacity=25)';
                  elements[i].style.cursor='default';

            }else if(elements[i].name==\"rplmn\"  && cntBlok <= 2){

                  elements[i].gray = true;   
                  elements[i].style.filter='alpha(opacity=25)';
                  elements[i].style.cursor='default';

            }else{
               elements[i].gray=false;
               elements[i].style.filter='alpha(opacity=100)';
               elements[i].style.cursor='hand';
            }
         }
   }
}
}

}

function ScanNo(aNode){
var thNode
var i
if(aNode.Children>0){
   thNode = aNode.Child;
   for(i=1;i <= aNode.Children;i++){
      menuContent.Nodes.Add(aNode.Key+'MN',tvwChild,thNode.Key+'MN',thNode.Text);                  
      ScanNo(thNode);
      thNode = thNode.Next;
   }
}
}

function ReplNo(aNode){
var thNode
var i

if(aNode.Children>0){
   thNode = aNode.Child;
   for(i=1;i <= aNode.Children;i++){
      thNode.Key = thNode.Key.substring(0,thNode.Key.length-2);
      ReplNo(thNode);
      thNode = thNode.Next;
   }
}
}

function addnewmn(){
var selkey;
var typeMN;
var nameMN;
var tos = 0;
var urltype;
   if(menuContent.SelectedItem){
      if(mainAr[menuContent.SelectedItem.Key][5] == 1) tos = 1;
   }
   arr = showModalDialog(\"fnciws/menu/allwindow.php?func=addMN&chld=\"+tos,null,\"dialogWidth:410px; dialogHeight:280px; status:no;\");
   if (arr != null)
   {
      nameMN = SymbolToSpec(arr[\"name\"])+'xwz'+(adi++)+'xwz';
      nameMN = nameMN.toLowerCase();
      if(menuContent.SelectedItem){
         selkey = menuContent.SelectedItem.Key;
         typeMN = arr[\"pos\"];
      } else {
         selkey = null;
         typeMN = null;
      }
      menuContent.Nodes.Add(selkey,typeMN,nameMN,arr[\"name\"]);                 
      if(arr[\"type\"] == 1){
         urltype  = \"?go=page\"
      }else{
         urltype  = \"?go=mpage\"
      }
      mainAr[nameMN] = Array(SymbolToSpec(arr[\"name\"]),urltype,null,null,null,arr[\"type\"],0,arr[\"tml\"],0,null);
      if(typeMN == tvwChild) menuContent.Nodes.Item(nameMN).Parent.Expanded = true;
      menuContent.Nodes.Item(nameMN).Selected = true;
      menuContent.Enabled = false;
      toolbar_Dis();
      if(urltype == \"?go=mpage\"){
         parent.C.location = \"mainiws.php\"+ urltype +\"&act=edtp&newpage=1&tmpl=\"+ arr[\"tml\"];   
      }else{
         parent.C.location = \"mainiws.php\"+ urltype +\"&newpage=1&tmpl=\"+ arr[\"tml\"];   
      }
   }     
}


function preSaveMN(aNode,lev){
var thNode
var i
var thNKey;

   thNKey = aNode.Key;
      mainAr[thNKey][0] = SymbolToSpec(aNode.Text);
      mainAr[thNKey][2] = goMN;
      goMN++;
      mainAr[thNKey][4] = lev;

      if(aNode.Children>0){
         lev++;
         thNode = aNode.Child;
         for(i=1;i <= aNode.Children;i++){
            preSaveMN(thNode,lev);
            thNode = thNode.Next;
         }
      }
      mainAr[thNKey][3] = goMN;
      goMN++;
}

function saveMN(){
var retStr = \"\";
var MN = menuContent.SelectedItem.Root;
goMN = 1;
var j = 0;

while(MN){
   preSaveMN(MN,1);
   MN = MN.Next;
}
for(i in mainAr){
   if(j){
      retStr += \"|%|\";
   }else{
   j++;
}
   retStr += mainAr[i].toString();
}
svn.blok.value = idBlok;
svn.mnu.value = retStr;
svn.submit();
}

function editPage(){
if(menuContent.SelectedItem)
      var nod = menuContent.SelectedItem.Key;
      var urlt = \"mainiws.php\"+ mainAr[nod][1] +\"&tmpl=\"+ mainAr[nod][7];
      if(mainAr[nod][5]==1 || mainAr[nod][5]==2) urlt +=\"&menu=\"+ mainAr[nod][8];
      parent.C.location = urlt;  
}

function prfMN(){
if(menuContent.SelectedItem)
      var ky = menuContent.SelectedItem.Key;
      if(mainAr[ky][5]==3){
         window.open(mainAr[ky][9],\"prefW\",\"width=550, height=300,status=no,toolbar=no,scrollbars=yes,resizable=yes\");
      }else if(mainAr[ky][5]==4){
         window.open(mainAr[ky][9],\"prefW\",\"width=650, height=500,status=no,toolbar=no,scrollbars=yes,resizable=yes\");
      } else {
         window.open(\"fnciws/menu/allwindow.php?func=prfMNp&menu=\"+mainAr[ky][8]+\"&nme=\"+mainAr[ky][0],\"prefM\",\"width=550,height=210,status=no,toolbar=no\");
      }
}

function edtMN(){
   arr = showModalDialog(\"fnciws/menu/menuedt.php?adm=".$adm."&blok=\" + idBlok ,null,\"dialogWidth:700px; dialogHeight:500px; status:no;\");
   if (arr != null)
   {
      parent.C.location='mainiws.php';
      svn.func.value = \"saveMN\";
      svn.blok.value = idBlok;
      svn.mnu.value = arr;
      svn.submit();
   }     

}

function MouseOver()
{
   if(this.gray!=true) this.className='tbovr';
}

function MouseOut()
{
   if(this.gray!=true) this.className='tb';
}

function MouseDown()
{
   if(this.gray!=true)  this.className='tbdwn';
}

var t = Array(toolbar);
for(j=0; j<t.length; j++)
{
   elements = t[j].all;
   for (i=0; i<elements.length; i++) 
   {
      if (elements[i].tagName != \"IMG\") 
            continue;

      if(elements[i].className==\"tb\") 
         {
            elements[i].onmouseover = MouseOver;
            elements[i].onmouseout = MouseOut;
            elements[i].onmousedown = MouseDown;
            elements[i].onmouseup = MouseOver;
            elements[i].gray=true;
            elements[i].style.filter='alpha(opacity=25)';
         }
   }
}


function toolbar_Dis(){
var t = Array(toolbar);
for(j=0; j<t.length; j++)
{
   elements = t[j].all;
   for (i=0; i<elements.length; i++) 
   {
      if (elements[i].tagName != \"IMG\") 
            continue;

      if(elements[i].className==\"tb\") 
         {
            elements[i].gray=true;
            elements[i].style.filter='alpha(opacity=25)';
            elements[i].style.cursor='default';
         }
   }
}
}

function rplMN(){
if(menuContent.SelectedItem)
      var ky = menuContent.SelectedItem.Key;
      arr = showModalDialog(\"fnciws/menu/allwindow.php?func=rplMN&blok=\" + idBlok,null,\"dialogWidth:450px; dialogHeight:130px; status:no;\");
      if (arr != null)
      {
         parent.C.location='mainiws.php';
         document.location='?typ=content&act=rplMenu&menu='+ mainAr[ky][8] + '&lft='+ mainAr[ky][2] + '&rght='+ mainAr[ky][3] + '&lvl='+ mainAr[ky][4] + '&blok='+ idBlok + '&newblok='+ arr;
      }     
}

\n";

switch($err){
   case 1:
      $ret.="alert('Недопустимое название блока! Попробуйте еще раз.     ')\n";
   break;
   case 2:
      $ret.="alert('Блок с таким названием уже существует! Попробуйте еще раз.   ')\n";
   break;
   case 3:
      $ret.="alert('Не удалось выполнить операцию! Попробуйте еще раз.   ')\n";
   break;
}

$ret.="//--></script></td></tr>";
}
return $ret;
}


//функция для переноса меню

function replMenu(){
global $blok,$newblok,$menu,$lft,$rght,$lvl;
include('fnciws/menu/menu.inc.php');
   
   $rgt = 0;
   $resmax=mysql_query("select max(".$fieldnmm["right"].") from iws_menu where ".$fieldnmm["block"]."=".$newblok);
   if(mysql_numrows($resmax)>=1) list($rgt)=mysql_fetch_row($resmax);
   $rgt += 1;

   $sql="update iws_menu set "
         .$fieldnmm["block"]."=$newblok,"
         .$fieldnmm["left"]."=(".$fieldnmm["left"]."-$lft+$rgt),"
         .$fieldnmm["right"]."=(".$fieldnmm["right"]."-$lft+$rgt),"
         .$fieldnmm["level"]."=(".$fieldnmm["level"]."-$lvl+1) "
         ."where "
         .$fieldnmm["block"]."=$blok and "
         .$fieldnmm["left"].">=$lft and "       
         .$fieldnmm["right"]."<=$rght";

   if(mysql_query($sql)){
      $resmax=mysql_query("select min(".$fieldnmm["left"].") from iws_menu where ".$fieldnmm["block"]."=$blok and $lft BETWEEN ".$fieldnmm["left"]." AND ".$fieldnmm["right"]);
      if(mysql_numrows($resmax)>=1){
         list($lftnw)=mysql_fetch_row($resmax);
         if(!$lftnw) $lftnw=$rght+1;
      }else{
         $lftnw=$rght+1;
      }
      $rgt=$rght-$lft+1;
      $sql="update iws_menu set "
            .$fieldnmm["left"]."=IF(".$fieldnmm["left"].">$lft,(".$fieldnmm["left"]."-$rgt),".$fieldnmm["left"]."), "
            .$fieldnmm["right"]."=IF(".$fieldnmm["right"].">$rght,(".$fieldnmm["right"]."-$rgt),".$fieldnmm["right"].") "
            ."where "
            .$fieldnmm["block"]."=$blok and "
            .$fieldnmm["left"].">=".$lftnw;
      if(mysql_query($sql)){
         header("location: ?typ=content&blok=$blok");
      }else{
         header("location: ?typ=content&blok=$blok&err=3");
         }
   }else{
      header("location: ?typ=content&blok=$blok&err=3"); 
   }

}

//функции для работы с блоками меню

function delBLOk(){
global $blok;
include('fnciws/menu/menu.inc.php');
$res = mysql_query("select idm,".$fieldnmm['type']." from iws_menu where ".$fieldnmm['block']."=".$blok);
if(mysql_numrows($res)>=1){
   while(list($did,$tp)=mysql_fetch_row($res)){
      mysql_query("DELETE from ".$pstbl." where ".$psfld["md"]."=".$did);
      if($tp == 2){
         mysql_query("DELETE from ".$pmtbl." where ".$pmfld["md"]."=".$did);
         mysql_query("DELETE from ".$pptbl." where ".$ppfld["md"]."=".$did);
      }
   }
   mysql_query("DELETE from iws_menu where ".$fieldnmm['block']."=".$blok);
}
mysql_query("delete from ".$vrtbl." where ".$vrfld['vr']."='[/:menu|".$blok."]'");
mysql_query("delete from ".$batbl." where ".$fieldnmb['did']."=".$blok);
header("location: ?typ=content");
return;
}

function actBLOk(){
global $blok,$dst,$inTemplate,$activeB;
include('fnciws/menu/menu.inc.php');
   mysql_query("UPDATE ".$batbl." SET ".$fieldnmb['act']."=$activeB, ".$fieldnmb['ug']."=$dst, inTemplate=$inTemplate WHERE notdel=0 AND ".$fieldnmb['did']."=".$blok);
   header("location: ?typ=content&blok=".$blok);
   return;
}

function addBLOk(){
global $nme,$dst,$mainadvar,$inTemplate,$activeB;
include('fnciws/menu/menu.inc.php');
$nme=trim($nme);
$nme=substr($nme,0,50);
   if(empty($nme)) { 
      header("location: ?typ=content&err=1");
      return;
   }
   list($cnt) = mysql_fetch_row(mysql_query("select COUNT(".$fieldnmb['did'].") from ".$batbl." where ".$fieldnmb['nme']."='".$nme."'"));
   if($cnt>=1){ 
      header("location: ?typ=content&err=2");
      return;
   }  
   $sql="INSERT into ".$batbl." (".$fieldnmb['nme'].",".$fieldnmb['act'].",".$fieldnmb['del'].",".$fieldnmb['ug'].",".$fieldnmb['lng'].",inTemplate) "
            ."values ('$nme',$activeB,1,$dst,'".$mainadvar['lng']."',$inTemplate)";

   if(!mysql_query($sql)){
      header("location: ?typ=content&err=3");
      return;
   } else {
      list($did) = mysql_fetch_row(mysql_query("select ".$fieldnmb['did']." from ".$batbl." where ".$fieldnmb['nme']."='".$nme."' and ".$fieldnmb['ug']."=".$dst." and ".$fieldnmb['del']."=1"));
      mysql_query("insert into ".$vrtbl." (".$vrfld['nme'].",".$vrfld['vr'].",".$vrfld['loc'].",".$vrfld['lng'].") values ('блок меню $nme','[/:menu|$did]',3,'".$mainadvar['lng']."')");
      if(!$mainadvar['sadm']){
         if($mainadvar['cnt']) $mainadvar['cnt'].="-";
         $mainadvar['cnt'].=$did;
         mysql_query("update ".$gtbl." set ".$gfld['cnt']."='".$mainadvar['cnt']."' where ".$gfld['did']."=".$mainadvar['grop']);
      }
      header("location: ?typ=content&blok=".$did);
      return;
   }     
}
//конец функций для работы с блоками меню

?>