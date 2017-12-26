<?php

include("languages/lang_".$language['lng'].".php");

function map_view_templ($menuB,$retMN)
{
global $hostName,$lang,$language,$uservar;

$retBMN="";
list($act,$grp)=mysql_fetch_row(mysql_query("select activ,usrgrp from iws_blockmenu where bid=".$menuB));
if(($grp==0 && $act) || (isset($uservar) && $uservar['tr']=="ars" && $uservar['grp']==$grp && $act)){
   $result=mysql_query("select idm,name,urlmenu,m_level from iws_menu where blk=".$menuB." and m_level>0 ORDER BY m_left");         
   if(mysql_numrows($result)>=1){
      while($arr = mysql_fetch_row($result)){
         $preMN = "<a href=\"".$hostName."/index.php".$arr[2]."&block=".$menuB."&menu=".$arr[0]."\">".$arr[1]."</A>";
         if(ereg("\[\/:menupunkt\]",$retMN)){
            $retBMN.="<div class=\"MapLinks\" style=\"padding-left:".(15*$arr[3])."px;\">".str_replace("[/:menupunkt]",$preMN,stripslashes($retMN))."</div>";
         }else{
            $retBMN.="<div class=\"MapLinks\" style=\"padding-left:".(20*$arr[3])."px;\">".stripslashes($retMN)."</div>";
         }
      }
   }
}
return $retBMN;
}

Class FNmap {
   var $retcon;


function replmap()
{
global $hostName,$lang,$language;


   list($this->tmpl)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=8"));
   list($this->tmplMAP)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=11"));

if($this->tmplMAP && ereg(":menu",$this->tmplMAP)){

/* $this->retcon="<table width=100% border=0 cellpadding=10 cellspacing=0>"
                  ."<tr><td colspan=2><h5>".$lang['map']."</h5></td></tr>"
                  ."<tr><td width=50></td><td>";

   if(ereg(":menupunkt",$this->tmpl)){
      $this->retcon.=str_replace("[/:menupunkt]","<a href=\"".$hostName."\">".$lang['main']."</a>",stripslashes($this->tmpl))."<br>";
   } else {
      $this->retcon.="|- <a href=\"".$hostName."\">".$lang['main']."</a><br>";
   }
*/ 
   $this->tmplMAP = stripslashes($this->tmplMAP);
   if(ereg(":categoryGuestbook",$this->tmplMAP)) $this->tmplMAP = str_replace("[/:categoryGuestbook]",$this->category_gb($this->tmpl),$this->tmplMAP);
   if(ereg(":menu",$this->tmplMAP)) $this->tmplMAP = preg_replace("/\[\/:menu\|(\d{1,})\]/e","map_view_templ('\\1','".$this->tmpl."')",$this->tmplMAP);

   $this->retcon.=$this->tmplMAP;

// $this->retcon.="</td></tr></table>";   

} else {
   $res = mysql_query("select bid from iws_blockmenu where notdel=0 and activ=1 and usrgrp=0 and lng='".$language['lng']."'");
   $this->retcon="<table width=100% border=0 cellpadding=10 cellspacing=0>"
                  ."<tr><td colspan=2><h5>".$lang['map']."</h5></td></tr>"
                  ."<tr><td width=50></td><td><table cellpadding=5>";
   if(ereg(":menupunkt",$this->tmpl)){

      $this->retcon.="<tr><td>".str_replace("[/:menupunkt]","<a href=\"".$hostName."\">".$lang['main']."</a>",stripslashes($this->tmpl))."</td></tr>";
      if(mysql_numrows($res)>=1){
         while(list($arb)=mysql_fetch_row($res)){
            $result=mysql_query("select idm,name,urlmenu,m_level from iws_menu where blk=".$arb." and m_level>0 ORDER BY m_left");        
            if(mysql_numrows($result)>=1){
               $this->retcon.="<tr><td>";
               while($arr = mysql_fetch_row($result)){
                  $this->retcon.=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$arr[3])
                                .str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php".$arr[2]."&block=".$arb."&menu=".$arr[0]."\">".$arr[1]."</a>",stripslashes($this->tmpl))."<br>";
               }
               $this->retcon.="</td></tr>";
            }
               $this->retcon.="<tr><td><br></td></tr>";
         }
      }

   } else {

      $this->retcon.="<tr><td>|- <a href=\"".$hostName."\">".$lang['main']."</a></td></tr>";
      if(mysql_numrows($res)>=1){
         while(list($arb)=mysql_fetch_row($res)){
            $result=mysql_query("select idm,name,urlmenu,m_level from iws_menu where blk=".$arb." and m_level>0 ORDER BY m_left");        
            if(mysql_numrows($result)>=1){
               $this->retcon.="<tr><td>";
               while($arr = mysql_fetch_row($result)){
                  $this->retcon.=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$arr[3])."|- <a href=\""
                                    .$hostName."/index.php".$arr[2]."&block=".$arb."&menu=".$arr[0]."\">".$arr[1]."</a><br>";
               }
               $this->retcon.="</td></tr>";
            }
               $this->retcon.="<tr><td><br></td></tr>";
         }
      }

   }
   $this->retcon.="</table></td></tr></table>";

}
return $this->retcon;
}

function category_gb($tmp) {
global $hostName,$language;
$this->retBMN="";

   $this->result=mysql_query("select id,name from iws_guestbk_category where activ=1");         
   if(mysql_numrows($this->result)>=1){
      while($this->arr = mysql_fetch_row($this->result)){
         $this->preMN = "<A href=\"".$hostName."/index.php?go=qa&category=".$this->arr[0]."\">".$this->arr[1]."</A><br>";
         if(ereg("\[\/:menupunkt\]",$tmp)){
            $this->retBMN.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".str_replace("[/:menupunkt]",$this->preMN,stripslashes($tmp));
         }else{
            $this->retBMN.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->preMN."<br>";
         }
      }
   }
return $this->retBMN;
}

}
?>
