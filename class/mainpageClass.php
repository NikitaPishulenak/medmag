<?php

include("languages/lang_".$language['lng'].".php");

class FNMainpage{
   var $retcon;
   var $arr_mvrs;

   function replCM(){
   global $language;
      $result=mysql_query("select content".$language['lng']." from iws_page_main");
      if(mysql_numrows($result)>=1){
         list($this->retcon)=mysql_fetch_row($result);
         $this->retcon = stripslashes($this->retcon);
         $this->get_vrs(0);
         $cnt=count($this->arr_mvrs);
         for($i=0;$i<=$cnt-1;$i++){
            switch($this->arr_mvrs[$i]){
               case "[/:gls]":
                  $this->retcon = str_replace("[/:gls]",$this->activ_opros(),$this->retcon);
               break;
               case "[/:guestbook]":
                  $this->retcon = str_replace("[/:guestbook]",$this->gst(),$this->retcon);
               break;
            }
         }
         return true;
      }else{
         return false;
      }
   }

   function get_vrs($lc = 0){
      unset($this->arr_mvrs);
      $result = mysql_query("select vr from iws_vars where lc=$lc");
      if(mysql_numrows($result)>=1){
         $i = 0;
         while($arr=mysql_fetch_row($result)) $this->arr_mvrs[$i++] = $arr[0];
      }
   }



   function activ_opros(){
   global $hostName,$language,$lang;
      $result=mysql_query("SELECT id,name FROM iws_opros WHERE activ=1 and lng='".$language['lng']."' ORDER by id DESC");

      if(mysql_numrows($result)>=1){
         list($vw)=mysql_fetch_row(mysql_query("select IF(view=1,1,0) from iws_opros_pref where id=1"));
         while($arr=mysql_fetch_row($result)){
            $res=mysql_query("select id,otvet from iws_oprot where oid=".$arr[0]." ORDER BY id");

            if(mysql_numrows($res)>=2){
               $this->opros.="<form name=\"frm".$arr[0]."\" action=\"".$hostName."/opros/voice/".$arr[0]."/\">"
//                               ."<input type=hidden name=go value=opros>"
//                               ."<input type=hidden name=act value=voice>"
//                               ."<input type=hidden name=id value=".$arr[0].">"
                                 ."<table cellpadding=5 cellspacing=0 border=0>"
                                 ."<tr><td><i>".stripslashes($arr[1])."</i></td></tr><tr><td align=center><table border=0 cellpadding=3 cellspacing=0>";
               $i = 0;
               while($aro=mysql_fetch_row($res)){
                  $this->opros.="<tr><td><input type=radio class=radio name=rd value=".$aro[0];
                  if(!$i){
                     $this->opros.=" checked";
                     $i++;
                  }
                  $this->opros.="></td><td>".$aro[1]."</td></tr>";
               }
               $this->opros.="</table><tr><td align=center><input class=btn type=\"image\" src=\"".$hostName."/design/votey_".$language['lng'].".gif\" title=\"".$lang['vote']."\" alt=\"".$lang['vote']."\" onclick=\"frm".$arr[0].".submit();return false;\">";
               if($vw) $this->opros.=" <input class=btn type=\"image\" src=\"".$hostName."/design/voter_".$language['lng'].".gif\" title=\"".$lang['resv']."\" alt=\"".$lang['resv']."\" onclick=\"document.location='".$hostName."/index.php?go=opros&act=viewresult&id=".$arr[0]."';return false;\">";
               $this->opros.="</td></tr></table></form>";
            }
         }  
         return $this->opros;
      }
   }

   function gst(){
   global $hostName,$language,$lang;
   list($lmt)=mysql_fetch_row(mysql_query("select IF(limt>=1,limt,10) from iws_guestpref"));
   $res=mysql_query("select id,DATE_FORMAT(datu,'%W, %e %M %Y â %T'),name,city,cntr,SUBSTRING(coment FROM 1 FOR 200) from "
                  ."iws_guestbk where lng='".$language['lng']."' order by datu DESC limit $lmt");
      if(mysql_numrows($res)>=1){
         list($this->gbTM)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=2"));
         $this->gbz="<table width=100% border=0 cellpadding=10 cellspacing=0>";
         while($arr=mysql_fetch_row($res))   $this->gbz.="<tr><td>".$this->zapgb($arr,stripslashes($this->gbTM))."</td></tr>";
         $this->gbz.="</table><a href=\"".$hostName."/index.php?go=qa\">".$lang['gblook']."</a>";
      }
      return $this->gbz;
   }

   function zapgb($art,$tmpl){
      $this->ret=$tmpl;
      if(ereg(":gbname",$this->ret)) $this->ret=str_replace("[/:gbname]",stripslashes($art[2]),$this->ret); 
      if(ereg(":gbhmp",$this->ret)) $this->ret=str_replace("[/:gbhmp]","",$this->ret); 
      if(ereg(":gbemail",$this->ret)) $this->ret=str_replace("[/:gbemail]","",$this->ret); 
      if(ereg(":gdicq",$this->ret)) $this->ret=str_replace("[/:gdicq]","",$this->ret); 
      if(ereg(":gbcity",$this->ret)) $this->ret=str_replace("[/:gbcity]",stripslashes($art[3]),$this->ret); 
      if(ereg(":gbcntr",$this->ret)) $this->ret=str_replace("[/:gbcntr]",stripslashes($art[4]),$this->ret); 
      if(ereg(":gbmess",$this->ret)) $this->ret=str_replace("[/:gbmess]",stripslashes($art[5])."...",$this->ret); 
      if(ereg(":gbdate",$this->ret)) $this->ret=str_replace("[/:gbdate]",$art[1],$this->ret); 
      if(ereg(":gbcomm",$this->ret)) $this->ret=str_replace("[/:gbcomm]","",$this->ret); 
      return $this->ret;
   }


}
?>
