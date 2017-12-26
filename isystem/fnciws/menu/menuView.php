<?php

include('menu.inc.php');
include('../../inc/config.inc.php');

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "cp1251"');

$sql="select ".$fieldnmm['descr'].",".$fieldnmm['url2'].",".$fieldnmm['left'].",".$fieldnmm['right'].","
   .$fieldnmm['level'].",".$fieldnmm['type'].",".$fieldnmm['edt'].",".$fieldnmm['tm'].",idm"
   ." from ".$matbl." where ".$fieldnmm['block']."=$blok and ".$fieldnmm['level'].">0 order by ".$fieldnmm['left'];

$res=mysql_query($sql);

header('Content-type: text/xml');
header('Cache-Control: no-cache, max-age=0, must-revalidate'); 
header('Content-Type: text/plain; charset=Windows-1251');

echo "<table cellpadding=0 cellspacing=0 border=0 width=100% height=100%>"
   ."<tr id=toolbar><td width=100%>&nbsp;&nbsp;&nbsp;<img border=0 class=tb name=mnUp src=\"../../images/menuUp.gif\" alt=\"Переместить пункт меню вверх\" onclick=\"if(!this.gray) goUp(0);\">&nbsp"
   ."<img class=tb border=0 name=mnDn  src=\"../../images/menuDown.gif\" alt=\"Переместить пункт меню вниз\" onclick=\"if(!this.gray) goDown(0);\">&nbsp"
   ."<img class=tb name=mnLUp border=0 src=\"../../images/menuLUp.gif\" alt=\"Переместить пункт меню на уровень выше\" onclick=\"if(!this.gray) goUp(1);\">&nbsp"
   ."<img class=tb name=mnLDn border=0 src=\"../../images/menuLDown.gif\" alt=\"Сделать пункт меню дочерним нижеследующего\" onclick=\"if(!this.gray) goDown(1);\">&nbsp"
   ."<img src=\"\" class=\"spr\">&nbsp"
   ."<img class=tb border=0 name=\"delmn\" src=\"../../images/deleteMenu.gif\" alt=\"Удалить меню\" onclick=\"if(!this.gray) delMN();\">"
   ."</td><td><img class=tb border=0 name=\"reMenu\" src=\"../../images/reCon.gif\" alt=\"Проверить наличие несвязанных пунктов меню и страниц\""; 

if($adm) echo " onclick=\"reMN();\"";

echo "></td></tr><tr>"  
      ."<td height=100% width=100% colspan=2>"
      ."<object ID=\"menuContent\" CLASSID=\"clsid:C74190B6-8589-11D1-B16A-00C0F0283628\" VIEWASTEXT WIDTH=\"100%\" HEIGHT=\"100%\" CODEBASE=\"http://activex.microsoft.com/controls/vb6/mscomctl.cab#Version=6,1,97,82\">"
      ."<param name=Style value=6>"
      ."<param name=LineStyle value=1>"
      ."<param name=BorderStyle value=1>"
      ."<param name=Appearance value=0>"
      ."<param name=HideSelection value=false>"
      ."</object>"                 
      ."\n<script LANGUAGE=\"JavaScript\" FOR=\"menuContent\" EVENT=\"Click()\" defer=true> menuContent_Click(); </script>"
      ."\n<script LANGUAGE=\"JavaScript\" FOR=\"menuContent\" EVENT=\"AfterLabelEdit(Cancel,NewString)\" defer=true> menuContent_AfterLabelEdit(Cancel,NewString); </script>"
      ."<script LANGUAGE=\"JavaScript\" FOR=\"menuContent\" EVENT=\"NodeClick(Node)\" defer=true> menuContent_NodeClick(Node); </script>"
      ."\n<script defer=true><!--\n"
      ."var tvwChild = 4;\n"
      ."var tvwPrevious = 3;\n"
      ."var tvwNext = 2;\n"
      ."var goMN;\n"
      ."var idBlok = ".$blok.";\n"
      ."var btnSave = 1;\n"
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

if(mysql_numrows($res)>=1){
   $adi = 0;
   while($arr=mysql_fetch_row($res)){
      echo "mainAr['".strtolower($arr[0])."xwz".($adi++)."xwz'] = Array(\"".$arr[0]."\",\"".$arr[1]."\",".$arr[2].",".$arr[3].",".$arr[4].",".$arr[5].",".$arr[6].",".$arr[7].",0,".$arr[8].");\n";
   }

echo "
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

echo "menuContent.Nodes.Item(nme[1]).Expanded = true;\n";
}

echo "
var adi = ".($adi++)."

function menuContent_Click(){\n
if(menuContent.SelectedItem){
   SelDesel(menuContent.SelectedItem);
   infoMenu(menuContent.SelectedItem.Key);
}
}

function menuContent_NodeClick(Node){\n
   SelDesel(menuContent.SelectedItem);
   infoMenu(Node.Key);
}

function infoMenu(keyar){
if(mainAr[keyar][8]>0){
   document.all[\"delmn\"].alt = \"снять метку удаления\";  
   document.all[\"delmn\"].src = \"../../images/udeleteMenu.gif\";   
   if(menuContent.Nodes.Item(keyar).Parent){
      if(mainAr[menuContent.Nodes.Item(keyar).Parent.Key][8]>0){
         document.all[\"delmn\"].gray = true;   
         document.all[\"delmn\"].style.filter=\"alpha(opacity=25)\";
      }else{
         document.all[\"delmn\"].gray = false;  
         document.all[\"delmn\"].style.filter=\"alpha(opacity=100)\";
      }
   }else{
      document.all[\"delmn\"].gray = false;  
      document.all[\"delmn\"].style.filter=\"alpha(opacity=100)\";
   }
} else {
   document.all[\"delmn\"].alt = \"удалить меню\"; 
   document.all[\"delmn\"].src = \"../../images/deleteMenu.gif\"; 
   if(mainAr[keyar][6]){
      document.all[\"delmn\"].gray = true;   
      document.all[\"delmn\"].style.filter=\"alpha(opacity=25)\";
   }else{
      document.all[\"delmn\"].gray = false;  
      document.all[\"delmn\"].style.filter=\"alpha(opacity=100)\";
   }
}
}

function SelDesel(Nod){
if(mainAr[Nod.Key][8]>0){
   document.all[\"mnUp\"].gray=true;
   document.all[\"mnUp\"].style.filter=\"alpha(opacity=25)\";
   document.all[\"mnDn\"].gray=true;
   document.all[\"mnDn\"].style.filter=\"alpha(opacity=25)\";
   document.all[\"mnLDn\"].gray=true;
   document.all[\"mnLDn\"].style.filter=\"alpha(opacity=25)\";
   document.all[\"mnLUp\"].gray=true;
   document.all[\"mnLUp\"].style.filter=\"alpha(opacity=25)\";
} else {
   if(Nod.Previous){
      document.all[\"mnUp\"].gray=false;
      document.all[\"mnUp\"].style.filter=\"alpha(opacity=100)\";
   }else{
      document.all[\"mnUp\"].gray=true;
      document.all[\"mnUp\"].style.filter=\"alpha(opacity=25)\";
   }

   if(Nod.Next){
      document.all[\"mnDn\"].gray=false;
      document.all[\"mnDn\"].style.filter=\"alpha(opacity=100)\";
      if(mainAr[Nod.Next.Key][5] == 1){
         document.all[\"mnLDn\"].gray=false;
         document.all[\"mnLDn\"].style.filter=\"alpha(opacity=100)\";
      }else{
         document.all[\"mnLDn\"].gray=true;
         document.all[\"mnLDn\"].style.filter=\"alpha(opacity=25)\";    
      }
      if(mainAr[Nod.Next.Key][8]>0){
         document.all[\"mnLDn\"].gray=true;
         document.all[\"mnLDn\"].style.filter=\"alpha(opacity=25)\";
      }
   }else{
      document.all[\"mnDn\"].gray=true;
      document.all[\"mnDn\"].style.filter=\"alpha(opacity=25)\";
      document.all[\"mnLDn\"].gray=true;
      document.all[\"mnLDn\"].style.filter=\"alpha(opacity=25)\";
   }
   if(Nod.Parent){
      document.all[\"mnLUp\"].gray=false;
      document.all[\"mnLUp\"].style.filter=\"alpha(opacity=100)\";
   }else{
      document.all[\"mnLUp\"].gray=true;
      document.all[\"mnLUp\"].style.filter=\"alpha(opacity=25)\";
   }
}
}

function menuContent_AfterLabelEdit(Cancel,NewString){\n
if(NewString){
   document.all[\"savemenu\"].disabled=false;
}else{
   alert(\"Такие значения названия пункта меню нежелательны!     \");
   menuContent.StartLabelEdit();
}
}\n 

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

function goUp(mn){
   var nmup;
   var mnn;
   var mtext=menuContent.SelectedItem.Key;
   if(mn){
         nmup = menuContent.SelectedItem.Parent.Key;
         mnn = tvwNext;
   } else {
      nmup = menuContent.SelectedItem.Previous.Key;
      mnn = tvwPrevious;      
   }

   menuContent.Nodes.Add(nmup,mnn,mtext+'MN',menuContent.SelectedItem.Text);                 
   ScanNo(menuContent.Nodes.Item(mtext));
   menuContent.Nodes.Remove(menuContent.Nodes.Item(mtext).Index);
   menuContent.Nodes.Item(mtext+'MN').Key = mtext;
   ReplNo(menuContent.Nodes.Item(mtext));
   menuContent.Nodes.Item(mtext).Selected=true;
   SelDesel(menuContent.Nodes.Item(mtext));
   if(document.all[\"savemenu\"].disabled) document.all[\"savemenu\"].disabled=false;
}


function goDown(mn){
   var mnn;
   var mtext=menuContent.SelectedItem.Key;
   if(mn)
      mnn = tvwChild;
   else
      mnn = tvwNext;    


   menuContent.Nodes.Add(menuContent.SelectedItem.Next.Key,mnn,mtext+'MN',menuContent.SelectedItem.Text);                  
   ScanNo(menuContent.Nodes.Item(mtext));
   menuContent.Nodes.Remove(menuContent.Nodes.Item(mtext).Index);
   menuContent.Nodes.Item(mtext+'MN').Key = mtext;
   ReplNo(menuContent.Nodes.Item(mtext));
   menuContent.Nodes.Item(mtext).Selected=true;
   SelDesel(menuContent.Nodes.Item(mtext));
   if(document.all[\"savemenu\"].disabled) document.all[\"savemenu\"].disabled=false;
}

function delNext(aNode,rt){
var thNode
var i
if(aNode.Children>0){
   thNode = aNode.Child;
   for(i=1;i <= aNode.Children;i++){
      if(rt){
         if(mainAr[thNode.Key][8] < 1){
            thNode.ForeColor = \"&HC0C0C0\";
            mainAr[thNode.Key][8] = 1;
         }
      }else{
            thNode.ForeColor = \"&H0\";
            mainAr[thNode.Key][8] = 0;
      }
      delNext(thNode,rt);
      thNode = thNode.Next;
   }
}
}

function delMN(){
if(mainAr[menuContent.SelectedItem.Key][8]>0){
   menuContent.SelectedItem.ForeColor = \"&H0\";
   mainAr[menuContent.SelectedItem.Key][8] = 0;
   delNext(menuContent.SelectedItem,0);      
   document.all[\"delmn\"].value=\"удалить меню\";    
   document.all[\"savemenu\"].disabled=false;
}else{
   var mess = \"Внимание!\\n\\nПри установке метки на удаление пункта меню            \\nбудут удалены дочерние пунткы меню и весь\\nсопутствующий контент.\\n\\nВы действительно хотите удалить меню?\"
   if(confirm(mess)){
      menuContent.SelectedItem.ForeColor = \"&HC0C0C0\";
      mainAr[menuContent.SelectedItem.Key][8] = 1;
      delNext(menuContent.SelectedItem,1);      
      document.all[\"delmn\"].value=\"снять метку удаления\";     
      document.all[\"savemenu\"].disabled=false;      
   }
}
SelDesel(menuContent.SelectedItem);
infoMenu(menuContent.SelectedItem.Key);
}

function preSaveMN(aNode,lev){
var thNode
var i
var thNKey;

   thNKey = aNode.Key;
   if(mainAr[thNKey][8] < 1){
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
   }else{
      mainAr[thNKey][2] = null;
      mainAr[thNKey][3] = null;
      mainAr[thNKey][4] = null;
   }
}

";

if($adm){

echo "

function reMN()
{
   pt = showModalDialog(\"reMenu.php\",null,\"dialogWidth:400px; dialogHeight:110px; status:no;\");
   if (pt == true){  buildReport('menuView.php?blok=".$blok."');  }
}

";
}

echo "

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

window.returnValue = retStr;
window.close();
}

function MouseOver()
{
   if(this.gray!=true)
      this.className='tbovr';
}

function MouseOut()
{
   if(this.gray!=true)
      this.className='tb';
}

function MouseDown()
{
   if(this.gray!=true)
      this.className='tbdwn';
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

\n";

if($adm){
   echo "document.all[\"reMenu\"].gray=false;
         document.all[\"reMenu\"].style.filter=\"alpha(opacity=100)\";";
}

echo "\n //--></script></td></tr>"
   ."<tr><td align=right colspan=2><hr><input type=button name=\"savemenu\" value=\"Сохранить изменения\" onclick=\"btnSave = 0; saveMN();\" DISABLED>&nbsp;&nbsp;"
   ."<input TYPE=BUTTON ONCLICK=\"window.close();\" value=\"Отмена\"><br><br></td></tr></table>";

?>
