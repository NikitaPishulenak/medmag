<?php

include("languages/lang_".$language['lng'].".php");


Class FNnw {
   var $retcon;


   function banner_view ($banVar){
   global $language;
      $result=mysql_query("select banners".$language['lng']." from iws_banners where name='".substr($banVar,3,-1)."'");
      if(mysql_numrows($result)>=1){
         list($banKod) = mysql_fetch_row($result);
         $this->retBan = stripslashes($banKod);
      }else{
         $this->retBan = "";
      }
      return $this->retBan;
   }


   function viewnw($id,$start,$archiv){
   global $hostName,$language,$lang;
      if(is_numeric($id)){
      $result=mysql_query("select content,title from iws_news where id=".$id." and lng='".$language['lng']."'");
      if(mysql_numrows($result)>=1){
         $this->retcon="<div id='NewsUrls'>";                
         if($archiv){
            $this->retcon.="<a href=\"".$hostName."/index.php?go=news&archiv=$archiv&start=$start\">".$lang['newstoar']."</a>";
         }else{
            $this->retcon.="<a href=\"".$hostName."/index.php?go=news&archiv=$archiv&start=$start\">".$lang['newstonw']."</a>";
         }

         $this->retcon.="</div><div id='NewsBody'>";  
                 list($this->retcn,$this->NewsTitle)=mysql_fetch_row($result);
         $this->retcon.="<h1>".stripslashes($this->NewsTitle)."</h1>";
         $this->retcon.= stripslashes($this->retcn)."</div>";
         $reslt = mysql_query("select vr from iws_vars where lc=3");
         if(mysql_numrows($reslt)>=1){
            while(list($this->art)=mysql_fetch_row($reslt)){
                        $this->retcon = str_replace($this->art,$this->banner_view($this->art),$this->retcon);
            }
         }
         return true;         
       }else{
         return false;
      }
       }else{
         return false;
      }
   }
   
   function nws($archiv=0){
      global $QUERY_STRING,$start,$hostName,$language,$lang;
      if(!isset($archiv)) $archiv=0;
      list($lmt)=mysql_fetch_row(mysql_query("select IF(limt>=1,limt,10) from iws_newspref"));
      if(!$archiv) list($this->arcn)=mysql_fetch_row(mysql_query("select count(id) from iws_news where arc=1 and lng='".$language['lng']."' and datu<=NOW()"));

      $this->qwr=ereg_replace("&start=".$start,"",$QUERY_STRING);
      if(!$start) $start=1; 

      $this->retcon="<table width=100% border=0 cellpadding=6 cellspacing=0>";
          $this->retcon.="<tr><td>".$lang['news'];
      if($archiv) $this->retcon.=$lang['newsarc'];
      $this->retcon.="</td></tr>";            
      if($archiv) $this->retcon.="<tr><td><a href=\"".$hostName."/index.php?go=news&archiv=0\">".$lang['newsret']."</a></td></tr>";
      $this->prom=$this->numlink($start,$this->qwr,$lmt,$archiv);
      if($this->prom!="none"){

         $this->retcon.="<tr><td align=center>".$this->prom."</td></tr>";
         $res=mysql_query("SELECT id,DATE_FORMAT(datu,'%e.%m.%Y'),title FROM iws_news WHERE arc=$archiv and lng='".$language['lng']."' and datu<=NOW() ORDER by datu DESC LIMIT ".($start-1).",$lmt");

         if(mysql_numrows($res)>=1){
            list($this->tmpl)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=1"));
            if(ereg("(:newsday|:newsmonth|:newsyear)",$this->tmpl) && ereg(":newshdr",$this->tmpl)){
               while($arr=mysql_fetch_row($res)){
                  $ddate=explode(".",$arr[1]);
                  $this->retcon.="<tr><td>"
                                    .str_replace("[/:newshdr]","<a class=newsm href=\"".$hostName."/index.php?go=news&id=".$arr[0]."&archiv=$archiv&act=view&start=$start\">"
                                    .stripslashes($arr[2])."</a>",
                                    str_replace("[/:newsday]",$ddate[0],
                                    str_replace("[/:newsmonth]",$lang[$ddate[1]],
                                    str_replace("[/:newsyear]",$ddate[2],stripslashes($this->tmpl)))))
                                    ."</td></tr>";
            }
            }else{
               while($arr=mysql_fetch_row($res)){
               $this->retcon.="<tr><td><b>".$arr[1]."</b>&nbsp;<a class=newsm href=\"".$hostName."/index.php?go=news&id=".$arr[0]."&archiv=$archiv&act=view&start=$start\">"
                                    .stripslashes($arr[2])."</td></tr>";
            }
            }

         }else{
            return false;
         }
         $this->retcon.="<tr><td align=center>".$this->prom."</td></tr>";

      }else{
         $this->retcon.="<tr><td align=center><h5>".$lang['newsno']."</h5></td></tr>";
      }
      if(!$archiv && $this->arcn) $this->retcon.="<tr><td><a href=\"".$hostName."/index.php?go=news&archiv=1\">".$lang['newsarcm']."</a></td></tr>";

      $this->retcon.="</table>";
      return $this->retcon;
   }

   function numlink($stt,$oper,$lmt,$archiv){
   global $language,$lang,$hostName;
   
   $this->qw="select count(id) from iws_news where arc=$archiv and lng='".$language['lng']."' and datu<=NOW()";

   list($cnt)=mysql_fetch_row(mysql_query($this->qw));
   if($cnt>=1){
      if(is_integer($cnt/$lmt)){
         $cr=$cnt/$lmt;       
      }else{
         $cr=round(($cnt/$lmt)+(0.5));
      }
      if(!$viv){
         $nv=($stt-1)/$lmt;
         if((round(($nv/10)+(0.5)))*10<$cr){          
            $kn=(round(($nv/10)+(0.5)))*10;
         } else {
            $kn=$cr;          
         }
         $rd=round(($nv/10)-0.5);
         if($rd<0){ $rd=0; }
         $nv=($rd*10)+1;
      } else {
         $nv=1;
         $kn=$cr;
      }

   if($kn>=2){
      $this->rt="<table width=100% border=0 cellpadding=1 cellspacing=0><tr><td>"; 
      if($stt<>1 && !$viv){ $this->rt.="<a class=cm href=\"".$hostName."/index.php?$oper&start=".($stt-$lmt)."\">".$lang['newsprev']."</a> "; }
      for($i=$nv;$i<=$kn;$i++){
         if($stt==1 && $i==1){
            $this->rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         }elseif((($i-1)*$lmt)+1==$stt){
            $this->rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         } else {
            if($viv){
               $this->rt.=" [&nbsp;<a class=cm href=\"".$hostName."/index.php?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;] ";
            } else {
               $this->rt.=" <span class=oth>&nbsp;<a class=cm href=\"".$hostName."/index.php?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;</span> ";
            }
         }
      }
      if((($cr-1)*$lmt)+1!=$stt && !$viv){ $this->rt.=" <a class=cm href=\"".$hostName."/index.php?$oper&start=".($stt+$lmt)."\">".$lang['newsnext']."</a>"; }    

      $this->rt.="</td></tr></table>";
/*
      $this->rt.="</td><td align=right valign=top nowrap>&nbsp;$stt..";
      if($cnt-$stt>=$lmt-1){ 
         $this->rt.=$stt+$lmt-1;
      }else{
         $this->rt.=$cnt;        
      }
      $this->rt.=" ".$lang['newsf']." ".$cnt."</td></tr></table>";
*/
   }
      return $this->rt;
   } else {
      return "none";
   }
   }

}
?>