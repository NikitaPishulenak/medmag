<?php

include("languages/lang_".$language['lng'].".php");

Class FNsearch {
         var $templateSearch;
         var $templateOt;
         var $retcon;
         
         
   function retTemplatesSearch($sel=0){
      switch ($sel){
         case 1:
            list($this->templateOt)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=21"));
            $this->templateOt=stripslashes($this->templateOt); 
         break;
         case 2:
            list($this->templateOt)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=14"));
            $this->templateOt=stripslashes($this->templateOt);
         break;
      }
      list($this->templateSearch)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=9"));
      $this->templateSearch=stripslashes($this->templateSearch);
   }


// Поиск по страницам ----------------------------------------------------------------------------------------------------------------------------------------------

   function src($wrd){
      global $hostName,$language,$lang;
      $wrd=preg_replace("/  +/"," ",trim($wrd));
      if(isset($wrd) && strlen($wrd)>=3){  
         $wrd = htmlspecialchars(substr($wrd,0,120));
         $this->wrdMass=explode(" ",$wrd);
         $this->cntWM=count($this->wrdMass);

         for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
            if(strlen($this->wrdMass[$iWM])>=3){
            
/*
WHEN A.content LIKE '%".$this->wrdMass[$iWM]."%' THEN concat(substring_index(SUBSTRING(A.bigcontent,IF(instr(A.bigcontent,'".$this->wrdMass[$iWM]."')>300,instr(A.bigcontent,'".$this->wrdMass[$iWM]."')-300,1),
IF(instr(A.bigcontent,'".$this->wrdMass[$iWM]."')>300,300+LENGTH('".$this->wrdMass[$iWM]."'),instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."')))," ",-10),
substring_index(SUBSTRING(A.bigcontent,instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'),IF((instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+300)<LENGTH(A.bigcontent)
,(300+LENGTH('".$this->wrdMass[$iWM]."')),(LENGTH(A.bigcontent)-instr(A.bigcontent,'".$this->wrdMass[$iWM]."'))))," ",10))
           
*/            
               if(isset($this->likeSel) && $this->likeSel){
                  $this->likeSel.=" OR  A.content LIKE '%".$this->wrdMass[$iWM]."%'";
                  $this->contSel.=" WHEN A.content LIKE '%".$this->wrdMass[$iWM]."%' THEN concat(substring_index(SUBSTRING(A.content,IF(instr(A.content,'".$this->wrdMass[$iWM]."')>300,instr(A.content,'".$this->wrdMass[$iWM]."')-300,1),";
                  $this->contSel.="IF(instr(A.content,'".$this->wrdMass[$iWM]."')>300,300+LENGTH('".$this->wrdMass[$iWM]."'),instr(A.content,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'))),' ',-20),";
                  $this->contSel.="substring_index(SUBSTRING(A.content,instr(A.content,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'),IF((instr(A.content,'".$this->wrdMass[$iWM]."')+300)<LENGTH(A.content)";
                  $this->contSel.=",(300+LENGTH('".$this->wrdMass[$iWM]."')),(LENGTH(A.content)-instr(A.content,'".$this->wrdMass[$iWM]."')))),' ',20))";
               
               } else {
                  $this->likeSel=" A.content LIKE '%".$this->wrdMass[$iWM]."%'";
                  $this->contSel=" WHEN A.content LIKE '%".$this->wrdMass[$iWM]."%' THEN concat(substring_index(SUBSTRING(A.content,IF(instr(A.content,'".$this->wrdMass[$iWM]."')>300,instr(A.content,'".$this->wrdMass[$iWM]."')-300,1),";
                  $this->contSel.="IF(instr(A.content,'".$this->wrdMass[$iWM]."')>300,300+LENGTH('".$this->wrdMass[$iWM]."'),instr(A.content,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'))),' ',-20),";
                  $this->contSel.="substring_index(SUBSTRING(A.content,instr(A.content,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'),IF((instr(A.content,'".$this->wrdMass[$iWM]."')+300)<LENGTH(A.content)";
                  $this->contSel.=",(300+LENGTH('".$this->wrdMass[$iWM]."')),(LENGTH(A.content)-instr(A.content,'".$this->wrdMass[$iWM]."')))),' ',20))";
               }
            }
         }           
         if(isset($this->likeSel) && $this->likeSel){  
            $this->count_send=$this->count_search(0,$this->wrdMass);

            $this->sql = "SELECT (CASE ".$this->contSel." END),B.blk,B.urlmenu,B.idm,B.name FROM iws_page_simple A,iws_menu B, iws_blockmenu C where (".$this->likeSel.") and A.mid=B.idm and B.blk=C.bid and C.activ=1 and C.lng='".$language['lng']."' ORDER BY B.blk and B.m_left LIMIT 100";  

            $this->resultSearch = mysql_query($this->sql);

            if((mysql_num_rows(mysql_query($this->sql)))>0){

               $search_a = array ("/(&nbsp;\s*)/","/  +/","/^(.*?)>/","/<(.*?)$/","/\[(.*?)\]/");
               $replace_a = array (" "," ","","","");
               
               $count_f=1;

               $this->retTemplatesSearch();

               setlocale(LC_ALL, "russian", "ru_RU.CP1251", "rus_RUS.CP1251", "Russian_Russia.1251");

               $this->retInfo="<DIV class='search_info'>";
            $this->retInfo.="<div>".$lang['search_pre']."</div>";;
               if(mysql_numrows($this->resultSearch)>=1){ $this->retInfo.="<DIV class=searchCurrentResult>".$lang['srn1'].mysql_numrows($this->resultSearch).$lang['srn2']."</DIV>"; } 

               if($this->count_send[0]>=1){$this->retInfo.="<div><a href='".$hostname."/searchInNews/?words=".urlencode($wrd)."'>".$lang['artSearchMsg1n'].$this->count_send[0].$lang['srn2']."</a></div>";}
               if($this->count_send[1]>=1){$this->retInfo.="<div><a href='".$hostname."/searchInFiles/?words=".urlencode($wrd)."'>".$lang['filesSearchMsg1f'].$this->count_send[1].$lang['srn2']."</a></div>";}
               $this->retInfo.="</DIV>";

               if(ereg("\[\/:searchtopath\]",$this->templateSearch)){
                  while($this->arrA=mysql_fetch_row($this->resultSearch)){  
                     $this->arrA[0]=strip_tags($this->arrA[0]);
                     $this->arrA[0]=preg_replace($search_a,$replace_a,$this->arrA[0]);
                        for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
                           if(strlen($this->wrdMass[$iWM])>=3){
                              $this->arrA[0]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arrA[0]);
                              $this->arrA[4]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arrA[4]);
                           }
                        }
                     $this->search_founds=str_replace("[/:searchcounter]",($count_f++),$this->templateSearch);
                     $this->search_founds=str_replace("[/:searchurl]",$hostName."/index.php".$this->arrA[2]."&block=".$this->arrA[1]."&menu=".$this->arrA[3],$this->search_founds);
                     $this->search_founds=str_replace("[/:searchtext]",stripslashes($this->arrA[0]),$this->search_founds);
                     $this->search_founds=str_replace("[/:searchtopath]","<a href=\"".$hostName."/index.php".$this->arrA[2]."&block=".$this->arrA[1]."&menu=".$this->arrA[3]."\">".$this->arrA[4]."</a>",$this->search_founds);
                     $this->retcon.=$this->search_founds;
                  }
               }
   
            } else {
               if($this->count_send[0]>0){header("location: ".$hostname."/searchInNews/?words=".urlencode($wrd));}
               else if($this->count_send[1]>0){header("location: ".$hostname."/searchInFiles/?words=".urlencode($wrd));
               }else{$this->retInfo="<DIV class=searchNotFound>".$lang['srnot']."</DIV>";}
            }
         } else { $this->retInfo = "<DIV class=searchNotFound>".$lang['srnt']."</DIV>";}
   } else { $this->retInfo = "<DIV class=searchNotFound>".$lang['srnt']."</DIV>";}
   $this->retcon = "<DIV class=searchTitle>".$lang['Titleress'].$this->retInfo."<DIV style=\"clear:both;\"></DIV></DIV>
                    <div class=searchEndTitle></div><DIV class=searchContent>".$this->retcon."</DIV>";
   return true;
}



//Поиск в новостях ------------------------------------------------------------------------------------------------------------------------------------

function search_news($wrd){
   global $hostName,$lang;


   if(isset($wrd) && strlen($wrd)>=3){
      $this->wrdMass=explode(" ",$wrd);
      $this->cntWM=count($this->wrdMass);

      for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
         if(strlen($this->wrdMass[$iWM])>=3){
            if(isset($this->nameSel) && $this->nameSel){
               $this->nameSel.=" OR A.name LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->descrSel.=" OR A.description LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->bigcontent_like.=" OR A.bigcontent LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->bigcontent.=" WHEN A.bigcontent LIKE '%".$this->wrdMass[$iWM]."%' THEN concat(substring_index(SUBSTRING(A.bigcontent,IF(instr(A.bigcontent,'".$this->wrdMass[$iWM]."')>300,instr(A.bigcontent,'".$this->wrdMass[$iWM]."')-300,1),";
               $this->bigcontent.="IF(instr(A.bigcontent,'".$this->wrdMass[$iWM]."')>300,300+LENGTH('".$this->wrdMass[$iWM]."'),instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'))),' ',-20),";
               $this->bigcontent.="substring_index(SUBSTRING(A.bigcontent,instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'),IF((instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+300)<LENGTH(A.bigcontent)";
               $this->bigcontent.=",(300+LENGTH('".$this->wrdMass[$iWM]."')),(LENGTH(A.bigcontent)-instr(A.bigcontent,'".$this->wrdMass[$iWM]."')))),' ',20))";
            } else {
               $this->nameSel="A.name LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->descrSel="A.description LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->bigcontent_like=" A.bigcontent LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->bigcontent=" WHEN A.bigcontent LIKE '%".$this->wrdMass[$iWM]."%' THEN concat(substring_index(SUBSTRING(A.bigcontent,IF(instr(A.bigcontent,'".$this->wrdMass[$iWM]."')>300,instr(A.bigcontent,'".$this->wrdMass[$iWM]."')-300,1),";
               $this->bigcontent.="IF(instr(A.bigcontent,'".$this->wrdMass[$iWM]."')>300,300+LENGTH('".$this->wrdMass[$iWM]."'),instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'))),' ',-20),";
               $this->bigcontent.="substring_index(SUBSTRING(A.bigcontent,instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+LENGTH('".$this->wrdMass[$iWM]."'),IF((instr(A.bigcontent,'".$this->wrdMass[$iWM]."')+300)<LENGTH(A.bigcontent)";
               $this->bigcontent.=",(300+LENGTH('".$this->wrdMass[$iWM]."')),(LENGTH(A.bigcontent)-instr(A.bigcontent,'".$this->wrdMass[$iWM]."')))),' ',20))";
            }
         }
      }

      if(isset($this->nameSel) && $this->nameSel){ 
         $this->sql = "SELECT A.id, DATE_FORMAT(A.data,'%e.%m.%Y'), A.name, A.file, A.description, A.department, ( CASE ".$this->bigcontent." END ) FROM iws_art_records A RIGHT JOIN iws_art_department B ON A.department=B.id AND B.activ=1 WHERE (".$this->nameSel.") OR (".$this->descrSel.") OR (".$this->bigcontent_like.") AND A.data<=NOW() ORDER BY A.data DESC LIMIT 100";
         $this->resultSearch = mysql_query($this->sql);

         $this->count_send=$this->count_search(1,$this->wrdMass);

         $this->retInfo="<DIV class='search_info'>";
       $this->retInfo.="<div>".$lang['search_pre']."</div>";;
       if($this->count_send[0]>=1){$this->retInfo.="<div><a href='".$hostname."/search/?words=".urlencode($wrd)."'>".$lang['srn1p'].$this->count_send[0].$lang['artSearchMsg2']."</a></div>";}
         if(mysql_numrows($this->resultSearch)>=1){ $this->retInfo.="<DIV class=searchCurrentResult>".$lang['artSearchMsg1'].mysql_numrows($this->resultSearch).$lang['artSearchMsg2']."</DIV>"; }
         if($this->count_send[1]>=1){$this->retInfo.="<div><a href='".$hostname."/searchInFiles/?words=".urlencode($wrd)."'>".$lang['filesSearchMsg1f'].$this->count_send[1].$lang['srn2']."</a></div>";}
         $this->retInfo.="</DIV>";

         if(mysql_num_rows($this->resultSearch)>0){

            $this->retTemplatesSearch(1);

            setlocale(LC_ALL, "russian", "ru_RU.CP1251", "rus_RUS.CP1251", "Russian_Russia.1251");

            $search_a = array ("/(&nbsp;\s*)/","/  +/","/^(.*?)>/","/<(.*?)$/","/\[(.*?)\]/");
            $replace_a = array (" "," ","","","");

            $i=1;

            if(ereg("\[\/:searchtopath\]",$this->templateSearch)){
               while($this->arrA=mysql_fetch_row($this->resultSearch)){   
                  for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
                     $this->arrA[6]=strip_tags($this->arrA[6]);
                     $this->arrA[6]=preg_replace($search_a,$replace_a,$this->arrA[6]);
                     if(strlen($this->wrdMass[$iWM])>=3){
                        $this->arrA[2]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arrA[2]);
                        $this->arrA[4]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arrA[4]);
                        $this->arrA[6]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arrA[6]);
                     }
                  }
                  $this->Pre=str_replace("[/:searchcounter]",($i++),$this->templateSearch);
                  $this->PreR=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateOt);
                  $this->PreR=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0]."\"><img src=\"".$hostN."/ImgForArticles/s_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->PreR);
                  $this->PreR=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4])."<br>... ".stripslashes($this->arrA[6])." ...<br><span>".$hostName."/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0]."</span>",$this->PreR);
                  $this->ddate=explode(".",$this->arrA[1]);
                  $this->PreR=str_replace("[/:artDay]",$this->ddate[0],$this->PreR);         
                  $this->PreR=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->PreR);         
                  $this->PreR=str_replace("[/:artYear]",$this->ddate[2],$this->PreR);         
                  $this->Pre=str_replace("[/:searchtopath]",$this->PreR,$this->Pre);
                  $this->Pre=str_replace("[/:searchtext]","",$this->Pre);
                  $this->Pre=str_replace("[/:searchurl]","",$this->Pre);
                  $this->retcon.="<DIV>".$this->Pre."</DIV>";                
               }
            }
            mysql_free_result($this->resultSearch);
         }else {$this->retInfo="<DIV class=searchNotFound>".$lang['artSearchNot']."</DIV>";}
      } else {$this->retInfo="<DIV class=searchNotFound>".$lang['artSearchNT']."</DIV>";}
   } else { $this->retInfo="<DIV class=searchNotFound>".$lang['artSearchNT']."</DIV>"; }

   $this->retcon = "<DIV class=searchTitle>".$lang['Titleress'].$this->retInfo."<DIV style=\"clear:both;\"></DIV></DIV>
                    <div class=searchEndTitle></div><DIV class=searchContent>".$this->retcon."</DIV>";
   return true;
}
 
 

//Поиск по статьям------------------------------------------------------------------------------------------------------------------

function search_files($wrd){
   global $hostName,$lang;


   if(isset($wrd) && strlen($wrd)>=3){
      $this->wrdMass=explode(" ",$wrd);
      $this->cntWM=count($this->wrdMass);
      

      for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
         if(strlen($this->wrdMass[$iWM])>=3){
            if(isset($this->Selname) && $this->Selname){
               $this->Selname.=" OR A.name LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->Selauth.=" OR A.authors LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->Seldesc.=" OR A.description LIKE '%".$this->wrdMass[$iWM]."%'";
            } else {
               $this->Selname="A.name LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->Selauth="A.authors LIKE '%".$this->wrdMass[$iWM]."%'";
               $this->Seldesc="A.description LIKE '%".$this->wrdMass[$iWM]."%'";
            }
         }
      }
      if(isset($this->Selname) && $this->Selname){  
         $this->sql = "SELECT  A.pse, A.name, A.authors, A.description, A.file, (SELECT B.name FROM iws_arfiles_department B WHERE B.id=A.department )
                      FROM iws_arfiles_records A WHERE (".$this->Selname.") OR (".$this->Selauth.") OR (".$this->Seldesc.") ORDER BY A.data DESC LIMIT 100";
         $this->resultSearch = mysql_query($this->sql);         

         $this->count_send=$this->count_search(2,$this->wrdMass);

         $this->retInfo="<DIV class='search_info'>";
       $this->retInfo.="<div>".$lang['search_pre']."</div>";;
         if($this->count_send[0]>=1){$this->retInfo.="<div><a href='".$hostname."/search/?words=".urlencode($wrd)."'>".$lang['srn1p'].$this->count_send[0].$lang['filesSearchMsg2']."</a></div>";}
         if($this->count_send[1]>=1){$this->retInfo.="<div><a href='".$hostname."/searchInNews/?words=".urlencode($wrd)."'>".$lang['artSearchMsg1n'].$this->count_send[1].$lang['filesSearchMsg2']."</a></div>";}
         if(mysql_numrows($this->resultSearch)>=1){ $this->retInfo.="<DIV class=searchCurrentResult>".$lang['filesSearchMsg1'].mysql_numrows($this->resultSearch).$lang['filesSearchMsg2']."</DIV>"; }
       $this->retInfo.="</DIV>";

         if(mysql_num_rows($this->resultSearch)>0){ 
            $this->retTemplatesSearch(2);

            $i=1;

            setlocale(LC_ALL, "russian", "ru_RU.CP1251", "rus_RUS.CP1251", "Russian_Russia.1251");

            if(ereg("\[\/:searchtext\]",$this->templateSearch) && (ereg("\[\/:searchtopath\]",$this->templateSearch) || ereg("\[\/:searchurl\]",$this->templateSearch))){
               while($this->arr=mysql_fetch_row($this->resultSearch)){
                  for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
                     if(strlen($this->wrdMass[$iWM])>=3){
                        $this->arr[1]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arr[1]);
                        $this->arr[2]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arr[2]);
                        $this->arr[3]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arr[3]);
                     }
                  }
                  $this->retcon.="<DIV>".str_replace("[/:searchcounter]",($i++),
                  str_replace("[/:searchtopath]",$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), $this->arr[5], $this->display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostName, $lang['fileslink']),
                  str_replace("[/:searchtext]","",
                  str_replace("[/:searchurl]",$hostName."/index.php?go=GetFile&uid=".$this->arr[0],$this->templateSearch))))."</DIV>";
               } 
            } else {
               while($this->arr=mysql_fetch_row($this->resultSearch)){
                  for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
                     if(strlen($this->wrdMass[$iWM])>=3){
                        $this->arr[1]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arr[1]);
                        $this->arr[2]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arr[2]);
                        $this->arr[3]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arr[3]);
                     }
                  }
                  $this->retcon="<DIV><table width=80%><tr>
                                 <td width=10 rowspan=2 valign=top>".($i++).".</td><td width=3 rowspan=2 bgcolor=#A8A8A8></td>
                                 <td>".$this->replaceTemplate($this->arr[0], stripslashes($this->arr[1]), stripslashes($this->arr[2]), stripslashes($this->arr[3]), $this->arr[5], $this->display_size($this->arr[4]), substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))), $hostName, $lang['fileslink'])."</td></tr>
                                 <tr><td class=path>".$hostName."/index.php?go=GetFile&uid=".$this->arr[0]."</td></tr>
                                 </table></DIV>";
               }
            }
            mysql_free_result($this->resultSearch);
         }
         else{$this->retInfo="<DIV class=searchNotFound>".$lang['filesSearchNot']."</DIV>";}
      } else{ $this->retInfo="<DIV class=searchNotFound>".$lang['filesSearchNT']."</DIV>";}
   } else { $this->retInfo="<DIV class=searchNotFound>".$lang['filesSearchNT']."</DIV>"; }

   $this->retcon = "<DIV class=searchTitle>".$lang['Titleress'].$this->retInfo."<DIV style=\"clear:both;\"></DIV></DIV>
                    <div class=searchEndTitle></div><DIV class=searchContent>".$this->retcon."</DIV>";
   return true; 
}


//Функция подсчёта в других----------------------------------------------------------------------------------------------------------------------------------------------

function count_search($param,$wrdMass)
{   
   $cntWM=count($wrdMass);
   switch ($param)
   {
      case 0:
         for($iWM=0; $iWM<=($cntWM-1); $iWM++){
            if(strlen($wrdMass[$iWM])>=3){
               if(isset($likeSel_Arts) && $likeSel_Arts ){
                  $likeSel_Arts.=" OR (name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (bigcontent LIKE '%".$wrdMass[$iWM]."%')";}
               else {
                  $likeSel_Arts="(name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (bigcontent LIKE '%".$wrdMass[$iWM]."%')";}
               if(isset($likeSel_Arfiles) && $likeSel_Arfiles ){
                  $likeSel_Arfiles.=" OR (name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (authors LIKE '%".$wrdMass[$iWM]."%')";}
                else {
                  $likeSel_Arfiles="(name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (authors LIKE '%".$wrdMass[$iWM]."%')";}
            }
         }
         list($count_sendf[0])=mysql_fetch_row(mysql_query("SELECT IF(COUNT(id)>100,100,COUNT(id)) FROM `iws_art_records` WHERE ".$likeSel_Arts));            
         list($count_sendf[1])=mysql_fetch_row(mysql_query("SELECT IF(COUNT(id)>100,100,COUNT(id)) FROM `iws_arfiles_records` WHERE ".$likeSel_Arfiles));
      break;
   
      case 1:
         for($iWM=0; $iWM<=($cntWM-1); $iWM++){
            if(strlen($wrdMass[$iWM])>=3){
               if(isset($likeSel) && $likeSel){
                  $likeSel.=" OR (content LIKE '%".$wrdMass[$iWM]."%') ";}
               else {
                  $likeSel="(content LIKE '%".$wrdMass[$iWM]."%') ";}
               if(isset($likeSel_Arfiles) && $likeSel_Arfiles ){
                  $likeSel_Arfiles.=" OR (name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (authors LIKE '%".$wrdMass[$iWM]."%')";}
               else {
                  $likeSel_Arfiles="(name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (authors LIKE '%".$wrdMass[$iWM]."%')";}
            }
         }
         list($count_sendf[0])=mysql_fetch_row(mysql_query("SELECT IF(COUNT(id)>100,100,COUNT(id)) FROM `iws_page_simple` WHERE ".$likeSel));            
           list($count_sendf[1])=mysql_fetch_row(mysql_query("SELECT IF(COUNT(id)>100,100,COUNT(id)) FROM `iws_arfiles_records` WHERE ".$likeSel_Arfiles));
         break;
   
   case 2: 
         for($iWM=0; $iWM<=($cntWM-1); $iWM++){
            if(strlen($wrdMass[$iWM])>=3){
               if(isset($likeSel) && $likeSel){$likeSel.=" OR (content LIKE '%".$wrdMass[$iWM]."%') ";}
               else {$likeSel="(content LIKE '%".$wrdMass[$iWM]."%') ";}
               if(isset($likeSel_Arts) && $likeSel_Arts ){
                  $likeSel_Arts.=" OR (name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (bigcontent LIKE '%".$wrdMass[$iWM]."%')";}
               else {
                  $likeSel_Arts="(name LIKE '%".$wrdMass[$iWM]."%') OR (description LIKE '%".$wrdMass[$iWM]."%') OR (bigcontent LIKE '%".$wrdMass[$iWM]."%')";}
            }
         }
         list($count_sendf[0])=mysql_fetch_row(mysql_query("SELECT IF(COUNT(id)>100,100,COUNT(id)) FROM `iws_page_simple` WHERE ".$likeSel));            
         list($count_sendf[1])=mysql_fetch_row(mysql_query("SELECT IF(COUNT(id)>100,100,COUNT(id)) FROM `iws_art_records` WHERE ".$likeSel_Arts));
         break;
   }  
return $count_sendf;
}





//------------------------------------------------------------------------------------------------------------------------------------

function display_size($file)
{
    global $docRoot;
    $file_size = filesize($docRoot."/FilesForDownload/".$file);
    if($file_size >= 1073741824) {
        $file_size = round($file_size / 1073741824 * 100) / 100 . " Гб";
    } elseif($file_size >= 1048576) {
        $file_size = round($file_size / 1048576 * 100) / 100 . " Мб";
    } elseif($file_size >= 1024) {
        $file_size = round($file_size / 1024 * 100) / 100 . " Кб";
    } else {
        $file_size = $file_size . " б";
    }
    return $file_size;
}




function replaceTemplate($Uid, $FileName, $FileAuthors='', $FileContent='', $FileFrom='', $FileSpace='', $FileExt='', $hostN, $TextLink)
   {
      $this->PreFiles=str_replace("[/:filesName]","<a title=\"$TextLink\" href=\"$hostN/index.php?go=GetFile&uid=$Uid\">$FileName</a>",$this->templateOt);
      if(ereg("/:filesAuthors",$this->templateOt)) $this->PreFiles=str_replace("[/:filesAuthors]",$FileAuthors,$this->PreFiles);
      if(ereg("/:filesShortContent",$this->templateOt)) $this->PreFiles=str_replace("[/:filesShortContent]",$FileContent,$this->PreFiles);
      if(ereg("/:filesFromList",$this->templateOt)) $this->PreFiles=str_replace("[/:filesFromList]",$FileFrom,$this->PreFiles);
      if(ereg("/:filesSpace",$this->templateOt)) $this->PreFiles=str_replace("[/:filesSpace]",$FileSpace,$this->PreFiles);
      if(ereg("/:filesExtention",$this->templateOt)) $this->PreFiles=str_replace("[/:filesExtention]",$FileExt,$this->PreFiles);
      if(ereg("/:filesLink",$this->templateOt)) $this->PreFiles=str_replace("[/:filesLink]","<a href=\"$hostN/index.php?go=GetFile&uid=$Uid\">$TextLink</a>",$this->PreFiles);

      return $this->PreFiles;
   }
   
}

?>
