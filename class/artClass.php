<?php

Class FNArt {

   var $retcon;

   var $countinmain;
   var $countinpage;

   var $templateArt;
   var $templateAll;
   var $templateFull;

   var $templateSearch;

   function retPreference()
   {
      list($this->countinmain, $this->countinpage)=mysql_fetch_row(mysql_query("SELECT IF(countinmain>=1,countinmain,4), IF(countinpage>=1,countinpage,15) FROM iws_art_prefernce WHERE id=1"));
   }

   function retTemplatesFull()
   {
      list($this->templateArt)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=21"));
      list($this->templateFull)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=23"));
      $this->templateArt=stripslashes($this->templateArt);
      $this->templateFull=stripslashes($this->templateFull);
   }

   function retTemplates()
   {
      list($this->templateArt)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=21"));
      list($this->templateAll)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=19"));
      $this->templateArt=stripslashes($this->templateArt);
      $this->templateAll=stripslashes($this->templateAll);
   }

   function retTemplatesSearch()
   {
      list($this->templateArt)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=21"));
      list($this->templateSearch)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=9"));
      $this->templateArt=stripslashes($this->templateArt);
      $this->templateSearch=stripslashes($this->templateSearch);
   }

//-----------------------------------------------------------------------------------------------------------------------------
//функция поиска файлов

   function srcArt($wrd)
   {
      global $hostName;
      include('languages/lang_ru.php');

      $wrd=preg_replace("/  +/"," ",trim($wrd));
      if(isset($wrd) && strlen($wrd)>=3)
      {  
         $wrd = htmlspecialchars(substr($wrd,0,120));
         $this->wrdMass=explode(" ",$wrd);
         $this->cntWM=count($this->wrdMass);
         
         for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
            if(strlen($this->wrdMass[$iWM])>=3){
               if(isset($this->nameSel) && $this->nameSel){
                  $this->nameSel.=" OR A.name LIKE '%".$this->wrdMass[$iWM]."%'";
                  $this->descrSel.=" OR A.description LIKE '%".$this->wrdMass[$iWM]."%'";
               } else {
                  $this->nameSel="A.name LIKE '%".$this->wrdMass[$iWM]."%'";
                  $this->descrSel="A.description LIKE '%".$this->wrdMass[$iWM]."%'";
               }
            }
         }

         if(isset($this->nameSel) && $this->nameSel){                  

            $this->sql = "SELECT A.id, DATE_FORMAT(A.data,'%e.%m.%Y'), A.name, A.file, A.description, A.department FROM iws_art_records A RIGHT JOIN iws_art_department B ON A.department=B.id AND B.activ=1 WHERE (".$this->nameSel.") OR (".$this->descrSel.") AND A.data<=NOW() ORDER BY A.data DESC";

            $this->resultSearch = mysql_query($this->sql);

            if(mysql_numrows($this->resultSearch)>=1){
               include('artArFunctions.php');
               $this->retTemplatesSearch();
      
               setlocale(LC_ALL, "russian", "ru_RU.CP1251", "rus_RUS.CP1251", "Russian_Russia.1251");
      
               $this->retcon.="<DIV>".$lang['artSearchMsg1']." ".mysql_numrows($this->resultSearch)." ".$lang['artSearchMsg2']."</DIV><br>";
               $i=1;
               if(ereg("\[\/:searchtopath\]",$this->templateSearch)){
                  while($this->arrA=mysql_fetch_row($this->resultSearch))
                  {
         
                     for($iWM=0; $iWM<=($this->cntWM-1); $iWM++){
                        if(strlen($this->wrdMass[$iWM])>=3){
                           $this->arrA[2]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arrA[2]);
                           $this->arrA[4]=preg_replace("/(".preg_quote($this->wrdMass[$iWM]).")/i", "<strong>\\1</strong>", $this->arrA[4]);
                        }
                     }
                  
                     $this->Pre=str_replace("[/:searchcounter]",($i++),$this->templateSearch);

               $this->PreR=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateArt);
               $this->PreR=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0]."\"><img src=\"".$hostN."/ImgForArticles/s_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->PreR);
               $this->PreR=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->PreR);

               $this->ddate=explode(".",$this->arrA[1]);
               $this->PreR=str_replace("[/:artDay]",$this->ddate[0],$this->PreR);         
               $this->PreR=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->PreR);         
               $this->PreR=str_replace("[/:artYear]",$this->ddate[2],$this->PreR);         

                     $this->Pre=str_replace("[/:searchtopath]",$this->PreR,$this->Pre);


                     $this->Pre=str_replace("[/:searchtext]","",$this->Pre);
                     $this->Pre=str_replace("[/:searchurl]",$hostName."/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0],$this->Pre);
                     $this->retcon.="<DIV>".$this->Pre."</DIV>";
                                    
                  }
               }
            } else {

               $this->retcon.="<DIV>".$lang['artSearchNot']."</DIV>";
            }
            mysql_free_result($this->resultSearch);

         } else {

            $this->retcon.="<DIV>".$lang['artSearchNT']."</DIV>";
         }

      } else {

         $this->retcon.="<DIV>".$lang['artSearchNT']."</DIV>";
      }
      $this->retcon = "<DIV class=searchArts><DIV>".$lang['artTitleResult']."</DIV>".$this->retcon."</DIV>";
//      $this->retcon = "<DIV class=searchArts><DIV>".$this->FormSearch($hostName,$lang['artSearchButton'],$wrd)."</DIV><DIV>".$lang['artTitleResult']."</DIV>".$this->retcon."</DIV>";
      return true;
   }

//конец функции поиска
//-----------------------------------------------------------------------------------------------------------------------------


   function replaceContentAnons($start,$orderBy,$anons)
   {
      global $hostName;
      $orderBy = substr($orderBy,0,3);
      $start = substr($start,0,10);

      if(!$start) $start=1;
      $this->resDep=mysql_query("SELECT DATE_FORMAT(A.data,'%e.%m.%Y'), A.name, A.file, A.description, A.bigcontent FROM iws_art_records A RIGHT JOIN iws_art_department B ON A.department=B.id AND B.activ=1 WHERE A.id=".$anons." AND A.data<=NOW()");
      if(mysql_numrows($this->resDep)<1) return false;

      include('languages/lang_ru.php');
      $this->retPreference();
      $this->retTemplatesFull();

      if(ereg("/:artTitle",$this->templateFull) && ereg("/:artFullText",$this->templateFull)){

         List($this->dat, $this->name, $this->file, $this->description, $this->bigcontent)=mysql_fetch_row($this->resDep);

         $this->retcon=str_replace("[/:artTitle]",stripslashes($this->name),$this->templateFull);
         $this->retcon=str_replace("[/:artImg]",(($this->file) ? "<img src=\"$hostName/ImgForArticles/".$this->file."\" alt=\"".stripslashes($this->name)."\">" : ""),$this->retcon);
         $this->retcon=str_replace("[/:artAnnouncement]",stripslashes($this->description),$this->retcon);
                  
         $this->ddate=explode(".",$this->dat);
         $this->retcon=str_replace("[/:artDay]",$this->ddate[0],$this->retcon);         
         $this->retcon=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->retcon);         
         $this->retcon=str_replace("[/:artYear]",$this->ddate[2],$this->retcon);    

         $this->retcon=str_replace("[/:artFullText]",stripslashes($this->bigcontent),$this->retcon);

         $this->retcon=str_replace("[/:artNextAnnons]",$this->AllArtNext($hostName, $orderBy, $lang, $anons),$this->retcon);
               
      }

      unset($this->resDep);
      return true;

   }


   function AllArtNext($hostN, $orderBy, $lang, $anons)
   {
      
      $this->result=mysql_query("SELECT id, DATE_FORMAT(data,'%e.%m.%Y'), name, file, description FROM iws_art_records WHERE department=".$orderBy." AND data<=NOW() AND id<>".$anons." ORDER BY data DESC LIMIT ".$this->countinmain);

      if(mysql_numrows($this->result)>=1){
         $this->lstArt="\n\n";
         if(ereg("/:artTitle",$this->templateArt)){
            while($this->arrA=mysql_fetch_row($this->result)){
               $this->Pre=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$orderBy."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateArt);
               $this->Pre=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$orderBy."&anons=".$this->arrA[0]."\"><img src=\"".$hostN."/ImgForArticles/s_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->Pre);
               $this->Pre=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->Pre);

               $this->ddate=explode(".",$this->arrA[1]);
               $this->Pre=str_replace("[/:artDay]",$this->ddate[0],$this->Pre);         
               $this->Pre=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->Pre);         
               $this->Pre=str_replace("[/:artYear]",$this->ddate[2],$this->Pre);         

               $this->lstArt.=$this->Pre."\n\n";
            }
         }

         unset($this->arrA,$this->result);

         return $this->lstArt;
      }
   }


//-----------------------------------------------------------------------------------------------------------------------------
   
   function replaceContentAll($start)
   {
      global $QUERY_STRING,$hostName;

      $start = substr($start,0,10);

      if(!$start) $start=1;

      include('languages/lang_ru.php');
      include('artArFunctions.php');
      $this->retPreference();
      $this->retTemplates();
      $this->prom=numlink_doc($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_art_records",'',$this->countinpage,$lang,$hostName);

      if(ereg("/:artAnnonsAll",$this->templateAll) && ereg("/:artList",$this->templateAll)){

         $this->retcon=str_replace("[/:artAnnonsAll]",$this->AllArtAll($hostName, $start, $lang),$this->templateAll);
         $this->retcon=str_replace("[/:artNameHeadings]",$lang['artTitle'],$this->retcon);
         $this->retcon=str_replace("[/:artBannerHeadings]","",$this->retcon);
         if($this->prom){ $this->retcon=str_replace("[/:artList]",$this->prom,$this->retcon); } else { $this->retcon=str_replace("[/:artList]","",$this->retcon); }
         $this->retcon=str_replace("[/:artHeadingsIn]","",$this->retcon);

      }
      return true;
   }

// Функция выводящая все новости всех рубрик ------------------------------------------------------------------------------------------------------------

   function AllArtAll($hostN, $start, $lang)
   {
      
      $this->result=mysql_query("SELECT id, DATE_FORMAT(data,'%e.%m.%Y'), name, file, description, department FROM iws_art_records WHERE data<=NOW() ORDER BY data DESC LIMIT ".($start-1).",".$this->countinpage);

      if(mysql_numrows($this->result)>=1){
         $this->lstArt="\n\n";
         if(ereg("/:artTitle",$this->templateArt)){
            while($this->arrA=mysql_fetch_row($this->result)){
               $this->Pre=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$this->arrA[5].(($start) ? "&start=".$start : "")."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateArt);
               $this->Pre=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$this->arrA[5].(($start) ? "&start=".$start : "")."&anons=".$this->arrA[0]."\"><img src=\"".$hostN."/ImgForArticles/s_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->Pre);
               $this->Pre=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->Pre);

               $this->ddate=explode(".",$this->arrA[1]);
               $this->Pre=str_replace("[/:artDay]",$this->ddate[0],$this->Pre);         
               $this->Pre=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->Pre);         
               $this->Pre=str_replace("[/:artYear]",$this->ddate[2],$this->Pre);         

               $this->lstArt.=$this->Pre."\n\n";
            }
         }

         unset($this->arrA,$this->result);

         return $this->lstArt;
      }
   }



//-----------------------------------------------------------------------------------------------------------------------------
   
   function replaceContent($start,$orderBy)
   {
      global $QUERY_STRING,$hostName;

      $orderBy = substr($orderBy,0,3);
      $start = substr($start,0,10);

      if(!$start) $start=1;

      $this->resDep=mysql_query("SELECT name, banner FROM iws_art_department WHERE id=$orderBy AND activ=1");
      if(mysql_numrows($this->resDep)<1) return false;


      include('languages/lang_ru.php');
      include('artArFunctions.php');
      $this->retPreference();
      $this->retTemplates();
      $this->prom=numlink_doc($start,ereg_replace("&start=".$start,"",$QUERY_STRING),"iws_art_records",$orderBy,$this->countinpage,$lang,$hostName);

      if(ereg("/:artAnnonsAll",$this->templateAll) && ereg("/:artList",$this->templateAll)){

         List($this->name,$this->banner)=mysql_fetch_row($this->resDep);

         $this->retcon=str_replace("[/:artAnnonsAll]",$this->AllArt($hostName, $start, $orderBy, $lang),$this->templateAll);
         $this->retcon=str_replace("[/:artNameHeadings]",stripslashes($this->name),$this->retcon);
         $this->retcon=str_replace("[/:artBannerHeadings]",$this->banner,$this->retcon);
         if($this->prom){ $this->retcon=str_replace("[/:artList]",$this->prom,$this->retcon); } else { $this->retcon=str_replace("[/:artList]","",$this->retcon); }
         $this->retcon=str_replace("[/:artHeadingsIn]","\n\n".$this->AnnonsIn($hostName, $orderBy, $lang),$this->retcon);

      }
      unset($this->resDep);
      return true;
   }


//------------------------------------------------------------------------------------------------------------

   function AllArt($hostN, $start, $orderBy, $lang)
   {
      
      $this->result=mysql_query("SELECT id, DATE_FORMAT(data,'%e.%m.%Y'), name, file, description FROM iws_art_records WHERE department=".$orderBy." AND data<=NOW() ORDER BY data DESC LIMIT ".($start-1).",".$this->countinpage);

      if(mysql_numrows($this->result)>=1){
         $this->lstArt="\n\n";
         if(ereg("/:artTitle",$this->templateArt)){
            while($this->arrA=mysql_fetch_row($this->result)){
               $this->Pre=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$orderBy.(($start) ? "&start=".$start : "")."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateArt);
               $this->Pre=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$orderBy.(($start) ? "&start=".$start : "")."&anons=".$this->arrA[0]."\"><img src=\"".$hostN."/ImgForArticles/s_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->Pre);
               $this->Pre=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->Pre);

               $this->ddate=explode(".",$this->arrA[1]);
               $this->Pre=str_replace("[/:artDay]",$this->ddate[0],$this->Pre);         
               $this->Pre=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->Pre);         
               $this->Pre=str_replace("[/:artYear]",$this->ddate[2],$this->Pre);         

               $this->lstArt.=$this->Pre."\n\n";
            }
         }

         unset($this->arrA,$this->result);

         return $this->lstArt;
      }
   }


//------------------------------------------------------------------------------------------------------------
   function AnnonsIn($hostN,$orderBy,$lang)
   {

      $this->retCat = "";
      $this->resDepIn=mysql_query("SELECT id, name, banner FROM iws_art_department WHERE mid=".$orderBy." AND activ=1 ORDER BY pos");
      if(mysql_numrows($this->resDepIn)>=1){

         list($this->templateAnons)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=20"));
         list($this->templateHead)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=22"));
         $this->templateHead = stripslashes($this->templateHead);
         $this->templateAnons = stripslashes($this->templateAnons);

         if(ereg("\[\/:artNameHeadings\]",$this->templateHead)){
            while($this->arr=mysql_fetch_row($this->resDepIn)){
               $this->Pre=str_replace("[/:artNameHeadings]","<div class=TitleMainIn><a href=\"$hostN/index.php?go=articles&orderBy=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></div>",$this->templateHead);
               $this->Pre=str_replace("[/:artBannerHeadings]",$this->arr[2],$this->Pre);
               $this->retCat.="\n\n<div class=HeadIn>".$this->Pre."</div>\n\n<DIV class=AnonsInMainIn>\n\n";
         
               $this->resAnons=mysql_query("SELECT id, DATE_FORMAT(data,'%e.%m.%Y'), name, file, description FROM iws_art_records WHERE department=".$this->arr[0]." AND data<=NOW() ORDER BY data DESC LIMIT ".$this->countinmain);
               if(mysql_numrows($this->resAnons)>=1){   
                  if(ereg("\[\/:artTitle\]",$this->templateAnons)){
                     while($this->arrA=mysql_fetch_row($this->resAnons)){
                        $this->Pre=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$this->arr[0]."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateAnons);
                        $this->Pre=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostN/index.php?go=articles&orderBy=".$this->arr[0]."&anons=".$this->arrA[0]."\"><img src=\"$hostN/ImgForArticles/m_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->Pre);
                        $this->Pre=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->Pre);
                  
                        $this->ddate=explode(".",$this->arrA[1]);
                        $this->Pre=str_replace("[/:artDay]",$this->ddate[0],$this->Pre);         
                        $this->Pre=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->Pre);         
                        $this->Pre=str_replace("[/:artYear]",$this->ddate[2],$this->Pre);         
               
                        $this->retCat.=$this->Pre;

                     }
                  }
               }
               $this->retCat=$this->retCat."\n\n</DIV>";
            }
         }
         unset($this->resDepIn);
      }

      return $this->retCat;

   }

//------------------------------------------------------------------------------------------------------------

   function FormSearch($hostN,$TextButton,$wrdRet='')
   {
      return "<form name=frmsearch method=get action=\"$hostN/searchArticles/\">
               <div style=\"vertical-align: middle;\"><input class=searchArt name=words maxlength=120 value='$wrdRet'><input type=image class=search_image title=\"$TextButton\" alt=\"$TextButton\" align=top src=\"$hostN/design/searchArt.gif\"></div>
               </form>";
/*
      return "<form name=frmsearch method=get action=\"$hostN/index.php\">
               <input type=hidden name=go value=artarchive>
               <input type=hidden name=act value=search>
               <nobr><input class=searchArt name=words maxlength=200>
               <input type=image class=search_image title=\"$TextButton\" alt=\"$TextButton\" align=absMiddle src=\"$hostN/design/searchArt.gif\"></nobr>
               </form>";   
*/
   }


}
?>
