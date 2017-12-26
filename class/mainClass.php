<?php

unset($uservar);
session_start();
session_register("uservar");

include('isystem/inc/config.inc.php');
include("languages/lang_".$language['lng'].".php");

$dblink = mysql_connect($dbhost, $dbuname, $dbpass) or die("Не могу подключиться к базе");
@mysql_select_db($dbname) or die("Не могу выбрать базу");
mysql_query('SET NAMES "utf8"');


list($title,$descript,$keyword)=mysql_fetch_row(mysql_query("select title".$language['lng'].",descr".$language['lng'].",keyword".$language['lng']." from iws_pref"));
$robots="all";

include("bannersClass.php");
$bnr = new FNBanner;



function menu_view($menuB) {
global $hostName,$uservar,$language;
list($act,$grp)=mysql_fetch_row(mysql_query("select activ,usrgrp from iws_blockmenu where bid=".$menuB));
$retBMN="";
if(($grp==0 && $act) || (isset($uservar) && $uservar['tr']=="ars" && $uservar['grp']==$grp && $act)){
   list($retMN)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=3"));
   $result=mysql_query("select idm,name,urlmenu from iws_menu where "
                        ."blk=".$menuB." and m_level=1 ORDER BY m_left");        
   if(mysql_numrows($result)>=1){
      while($arr = mysql_fetch_row($result)){
         $preMN = "<a class=menu href=\"".$hostName."/index.php".$arr[2]."&block=".$menuB
                  ."&menu=".$arr[0]."\">".$arr[1]."</A>";
         if(ereg("\[\/:menupunkt\]",$retMN)){
            $retBMN.= str_replace("[/:menupunkt]",$preMN,stripslashes($retMN));
         }else{
            $retBMN.= $preMN."<br>";
         }
      }
   }
}
return $retBMN;
}

function submenu_ret($smn){
global $hostName,$language;
$retBMN="";
$result=mysql_query("SELECT A.idm, A.name, A.urlmenu, A.blk FROM iws_menu A, iws_menu B 
                     WHERE B.idm=$smn AND A.blk=B.blk AND A.m_level=B.m_level+1 AND A.m_left BETWEEN B.m_left AND B.m_right 
                     ORDER BY A.m_left");          
if(mysql_numrows($result)>=1){
   list($retMN)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=6"));
      while($arr = mysql_fetch_row($result)){
         $preMN = "<a class=submenu href=\"".$hostName."/index.php".$arr[2]."&block=".$arr[3]."&menu=".$arr[0]."\">".$arr[1]."</A>";
         if($retMN && ereg("\[\/:menupunkt\]",$retMN)){
            $retBMN.= str_replace("[/:menupunkt]",$preMN,stripslashes($retMN));
         }else{
            $retBMN.= $preMN."<br>";
         }
      }
}
return $retBMN;
}


// Модуль Фотогалереии для превью альбома без рубрики---------------------------------------------------------------------------------------------------------------------------

   function GetAlbum($idAlbum)
   {
   global $hostName;

      $arr=mysql_query("SELECT title,description FROM  `iws_photos_albums` WHERE id =".$idAlbum." ");
      if(mysql_num_rows($arr)>=1){      
         $result=mysql_query("SELECT alt,file FROM `iws_photos_records` WHERE aid =".$idAlbum." ORDER BY id");
         if(mysql_num_rows($result)!=0){
            $arr=mysql_fetch_row($arr);
            $out_form="<div class='album' alt='".$arr[0]."' name='album".$idAlbum."'>
                        <div class='alb_cont' id='alb_cont".$idAlbum."'>
                        <div id='btn_left' class='btn_left_noactive'>&nbsp;</div>
                        <div class='album_box' id='album_box".$idAlbum."'><div class='album_img' id='album_img".$idAlbum."' alt='".$arr[0]."'>";
            $a=0;
         
            while($arr = mysql_fetch_row($result)){
               $a++;

               $size = getimagesize($hostName."/PhotoAlbums/s_".$arr[1]);
               $out_form.="<a href='".$hostName."/PhotoAlbums/".$arr[1]."' rel='lightbox[album".$idAlbum."]' title='".$arr[0]."' class='pre'>"
                         ."<img src='".$hostName."/PhotoAlbums/s_".$arr[1]."' width='".$size[0]."' height='".$size[1]."'></a>";
            }
            $out_form.="</div></div><div id='btn_right' class='btn_right_active' alt='".$a."'>&nbsp;</div></div>";
         $out_form.=" <script type='text/javascript'>
                     Album_pre['album".$idAlbum."']=[".$a."];
                     </script></div><div style='clear:both;'></div>";
            return $out_form;
   
         } else {
            return "";
         }
      } else {
         return "";
      }   
   }



// Основной класс ----------------------------------------------------------------------------------------------------------------------------------------

class Func {

   var $design_page;//шаблон дизайна
   var $arr_vrs;//массив переменных
   var $content;//содержание страницы;

   function replText($mn,$bl){
      global $bnr;

      $this->get_vrs(2);
      $cnt=count($this->arr_vrs);
      for($i=0;$i<=$cnt-1;$i++){
         switch($this->arr_vrs[$i]){
               case "[/:rotation]":
                        $this->design_page = str_replace("[/:rotation]",$this->rotation_view(),$this->design_page);
               break;
               case "[/:users]":
                        $this->design_page = str_replace("[/:users]",$this->login_view(),$this->design_page);
               break;
               case "[/:map]":
                        $this->design_page = str_replace("[/:map]",$this->map_view(),$this->design_page);
               break;
               case "[/:content]":
                        $this->design_page = str_replace("[/:content]",$this->content,$this->design_page);
               break;
            }
      
      }

      if(ereg("/:formqa",$this->design_page)) $this->design_page = str_replace("[/:formqa]",$this->FormAddmess(),$this->design_page);

      $this->get_vrs(8);
      $cnt=count($this->arr_vrs);

      for($i=0;$i<=$cnt-1;$i++){
        if(ereg("(:banner|:button)",$this->arr_vrs[$i]))
               $bnr->banner_view($this->arr_vrs[$i]);
               $this->design_page = str_replace($this->arr_vrs[$i],$bnr->retcon,$this->design_page);
         
      }
      if(ereg("/:menu",$this->design_page)) $this->design_page = preg_replace("/\[\/:menu\|(\d{1,})\]/e","menu_view('\\1')",$this->design_page);
      
      if(ereg("/:submenu",$this->design_page)) $this->design_page = preg_replace("/\[\/:submenu\|(\d{1,})\]/e","submenu_ret('\\1')",$this->design_page);

      if(ereg("/:photoalbum",$this->design_page)) $this->design_page = preg_replace("/\[\/:photoalbum\|(\d{1,})\]/e","GetAlbum('\\1')",$this->design_page);

      if(ereg("/:pathtopage",$this->design_page)) $this->design_page = str_replace("[/:pathtopage]",$this->get_ptp($mn,$bl),$this->design_page);

      if(ereg("/:news",$this->design_page)) $this->design_page = str_replace("[/:news]",$this->LastNews(),$this->design_page);

      if(ereg("/:search",$this->design_page)) $this->design_page = str_replace("[/:search]",$this->search_view(),$this->design_page);

      if(ereg("/:artHeadings",$this->design_page)) $this->design_page = str_replace("[/:artHeadings]",$this->NameHeadings(),$this->design_page);

      if(ereg("/:artCurrDate",$this->design_page)) $this->design_page = str_replace("[/:artCurrDate]",$this->CurrDat(),$this->design_page);

      if(ereg("/:artNewSearch",$this->design_page)) $this->design_page = str_replace("[/:artNewSearch]",$this->FormSearchArt(),$this->design_page);

      if(ereg("/:artNew",$this->design_page)) $this->design_page = str_replace("[/:artNew]",$this->LastArt(),$this->design_page);

      if(ereg("/:artWithoutNew",$this->design_page)) $this->design_page = str_replace("[/:artWithoutNew]",$this->LastWithoutArt(),$this->design_page);

      if(ereg("/:photoAlbumAllRubric",$this->design_page)) $this->design_page = str_replace("[/:photoAlbumAllRubric]",$this->photoRubricNames(),$this->design_page);

      if(ereg("/:filesAllMag",$this->design_page)) $this->design_page = str_replace("[/:filesAllMag]",$this->journalNumbers(),$this->design_page);

      if(ereg("/:filesAllRubric",$this->design_page)) $this->design_page = str_replace("[/:filesAllRubric]",$this->journalRubric(),$this->design_page);

      if(ereg("/:filesNewMag",$this->design_page)) $this->design_page = str_replace("[/:filesNewMag]",$this->journalNew(),$this->design_page);


      $this->stts();
   }  



//----------------------------------------------------------------------------------------------------------------------------------------------------------------

   function journalNew()
   {
      global $hostName;

      $this->result=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name DESC LIMIT 1");
      if(mysql_numrows($this->result)===1){      

         list($this->journalId,$this->journalName)=mysql_fetch_row($this->result);
         list($this->templateFile)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=14"));
         $this->templateFile=stripslashes($this->templateFile);

         $this->lstFiles="<div class=NewNumber>".$this->journalName."</div>\n";

         $this->resultR=mysql_query("SELECT id,name FROM iws_arfiles_A_rubric ORDER BY name");

         if(mysql_numrows($this->resultR)>=1){
            if(ereg("/:filesName",$this->templateFile)){
               while($this->arrR=mysql_fetch_row($this->resultR)){

                  $this->result=mysql_query("SELECT id, name, authors, description FROM iws_arfiles_A_records WHERE department=".$this->journalId." AND rubric=".$this->arrR[0]." ORDER BY data DESC");

                  if(mysql_numrows($this->result)>=1){
                     $this->lstFiles.="<DIV class=NewRubric>\n<DIV class=RubricName><a href=\"".$hostName."/index.php?go=filesarchive_A&Rubric=".$this->arrR[0]."\">".stripslashes($this->arrR[1])."</a></div>\n\n";

                        while($this->arr=mysql_fetch_row($this->result)){

                           $this->PreFiles=str_replace("[/:filesName]","<a href=\"$hostName/index.php?go=filesarchive_A&orderBy=".$this->journalId."&id=".$this->arr[0]."\">".$this->arr[1]."</a>",$this->templateFile);
                           if(ereg("/:filesAuthors",$this->templateFile)) $this->PreFiles=str_replace("[/:filesAuthors]",$this->arr[2],$this->PreFiles);
                           if(ereg("/:filesShortContent",$this->templateFile)) $this->PreFiles=str_replace("[/:filesShortContent]",$this->arr[3],$this->PreFiles);
                           $this->PreFiles=str_replace("[/:filesFromList]","",$this->PreFiles);
                           $this->PreFiles=str_replace("[/:filesSpace]","",$this->PreFiles);
                           $this->PreFiles=str_replace("[/:filesExtention]","",$this->PreFiles);
                           $this->PreFiles=str_replace("[/:filesLink]","",$this->PreFiles);

                           $this->lstFiles.="<DIV class=NewFileRow>".$this->PreFiles."</DIV>\n\n";

                        }
                     $this->lstFiles.="</DIV>\n\n";
                     unset($this->arr,$this->result);
                  }
               }
               unset($this->arrR,$this->resultR);               
            }
         }

         return $this->lstFiles;
      }

   }


   function journalNumbers()
   {
      global $hostName;
      $this->resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_department ORDER BY name DESC");
      if(mysql_numrows($this->resDep)>=1){
         $this->retCat="";
         while($this->arr=mysql_fetch_row($this->resDep)){
               $this->retCat.="<div><a href=\"".$hostName."/index.php?go=filesarchive_A&orderBy=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></div>\n";

         }
         unset($this->arr,$this->resDep);
      }
      return $this->retCat;
   }

   function journalRubric()
   {
      global $hostName;
      $this->resDep=mysql_query("SELECT id,name FROM iws_arfiles_A_rubric ORDER BY name");
      if(mysql_numrows($this->resDep)>=1){
         $this->retCat="";
         while($this->arr=mysql_fetch_row($this->resDep)){
               $this->retCat.="<div><a href=\"".$hostName."/index.php?go=filesarchive_A&Rubric=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></div>\n";

         }
         unset($this->arr,$this->resDep);
      }
      return $this->retCat;
   }


//----------------------------------------------------------------------------------------------------------------------------------------------------------------

   function photoRubricNames()
   {
      global $hostName,$lang;

      $this->retCat="";
      $this->resDep=mysql_query("SELECT A.id, A.name,(SELECT COUNT(B.id) FROM iws_photos_albums B WHERE B.cid=A.id) FROM iws_photos_category A WHERE A.view=1 AND (SELECT COUNT(B.id) FROM iws_photos_albums B WHERE B.cid=A.id)>0 ORDER BY A.name");
      if(mysql_numrows($this->resDep)>=1){
           while($this->arr=mysql_fetch_row($this->resDep)){
               if(isset($_GET['rubric']) && $_GET['rubric']==$this->arr[0]){
                  $this->retCat.="<a class=current_url href=\"$hostName/index.php?go=photosA&rubric=".$this->arr[0]."\" title=\"".$lang['PhotoAlbumText'].$this->arr[2]."\">".stripslashes($this->arr[1])."</a> ";
               } else {
                  $this->retCat.="<a href=\"$hostName/index.php?go=photosA&rubric=".$this->arr[0]."\" title=\"".$lang['PhotoAlbumText'].$this->arr[2]."\">".stripslashes($this->arr[1])."</a> ";
               }
           }
         unset($this->resDep);
      }
      return $this->retCat;

   }



// Модуль Статей ака Новости с рубриками---------------------------------------------------------------------------------------------------------------------------


   function LastWithoutArt()
   {
      global $hostName,$lang;

      list($this->countinmain)=mysql_fetch_row(mysql_query("SELECT IF(countinmain>=1,countinmain,4) FROM iws_art_prefernce WHERE id=1"));
      list($this->templateAnons)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=20"));
      $this->templateAnons = stripslashes($this->templateAnons);

      $this->retCat="\n\n<DIV class=AnonsInMain>";
         
         $this->resAnons=mysql_query("SELECT id, DATE_FORMAT(data,'%e.%m.%Y'), name, file, description, department FROM iws_art_records WHERE data<=NOW() ORDER BY data DESC LIMIT ".$this->countinmain);
         if(mysql_numrows($this->resAnons)>=1){   
            if(ereg("\[\/:artTitle\]",$this->templateAnons)){
               while($this->arrA=mysql_fetch_row($this->resAnons)){
                  $this->Pre=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateAnons);
                  $this->Pre=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arrA[5]."&anons=".$this->arrA[0]."\"><img src=\"".$hostName."/ImgForArticles/m_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->Pre);
                  $this->Pre=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->Pre);

                  $this->ddate=explode(".",$this->arrA[1]);
                  $this->Pre=str_replace("[/:artDay]",$this->ddate[0],$this->Pre);         
                  $this->Pre=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->Pre);         
                  $this->Pre=str_replace("[/:artYear]",$this->ddate[2],$this->Pre);         

                  $this->retCat.=$this->Pre;

               }
            }
         }
      $this->retCat=$this->retCat."</DIV>";
      return $this->retCat;

   }


   function LastArt()
   {
      global $hostName,$lang;

      list($this->countinmain)=mysql_fetch_row(mysql_query("SELECT IF(countinmain>=1,countinmain,4) FROM iws_art_prefernce WHERE id=1"));
      list($this->templateHead)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=22"));
      list($this->templateAnons)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=20"));
      $this->templateHead = stripslashes($this->templateHead);
      $this->templateAnons = stripslashes($this->templateAnons);

      $this->retCat="";
      $this->resDep=mysql_query("SELECT id, name, banner FROM iws_art_department WHERE mid<=0 AND activ=1 ORDER BY pos");
      if(mysql_numrows($this->resDep)>=1){
         if(ereg("\[\/:artNameHeadings\]",$this->templateHead)){
            while($this->arr=mysql_fetch_row($this->resDep)){
               $this->Pre=str_replace("[/:artNameHeadings]","<div class=TitleMain><a href=\"$hostName/index.php?go=articles&orderBy=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></div>",$this->templateHead);
               $this->Pre=str_replace("[/:artBannerHeadings]",$this->arr[2],$this->Pre);
               $this->retCat.="\n\n<div class=Head>".$this->Pre."</div>\n\n<DIV class=AnonsInMain>";
         
               $this->resAnons=mysql_query("SELECT id, DATE_FORMAT(data,'%e.%m.%Y'), name, file, description FROM iws_art_records WHERE department=".$this->arr[0]." AND data<=NOW() ORDER BY data DESC LIMIT ".$this->countinmain);
               if(mysql_numrows($this->resAnons)>=1){   
                  if(ereg("\[\/:artTitle\]",$this->templateAnons)){
                     while($this->arrA=mysql_fetch_row($this->resAnons)){
                        $this->Pre=str_replace("[/:artTitle]","<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arr[0]."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateAnons);
                        $this->Pre=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a title=\"".$lang['artlink']."\" href=\"$hostName/index.php?go=articles&orderBy=".$this->arr[0]."&anons=".$this->arrA[0]."\"><img src=\"".$hostName."/ImgForArticles/m_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->Pre);
                        $this->Pre=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->Pre);

                        $this->ddate=explode(".",$this->arrA[1]);
                        $this->Pre=str_replace("[/:artDay]",$this->ddate[0],$this->Pre);         
                        $this->Pre=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->Pre);         
                        $this->Pre=str_replace("[/:artYear]",$this->ddate[2],$this->Pre);         

                        $this->retCat.=$this->Pre;

                     }
                  }
               }
               $this->retCat=$this->retCat."</DIV>";


               $this->resDepIn=mysql_query("SELECT id, name, banner FROM iws_art_department WHERE mid=".$this->arr[0]." AND activ=1 ORDER BY pos");
               if(mysql_numrows($this->resDepIn)>=1){
                  if(ereg("\[\/:artNameHeadings\]",$this->templateHead)){
                     while($this->arr=mysql_fetch_row($this->resDepIn)){
                        $this->Pre=str_replace("[/:artNameHeadings]","<div class=TitleMainIn><a href=\"$hostName/index.php?go=articles&orderBy=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></div>",$this->templateHead);
                        $this->Pre=str_replace("[/:artBannerHeadings]",$this->arr[2],$this->Pre);
                        $this->retCat.="\n\n<div class=HeadIn>".$this->Pre."</div>\n\n<DIV class=AnonsInMain>";
         
                        $this->resAnons=mysql_query("SELECT id, DATE_FORMAT(data,'%e.%m.%Y'), name, file, description FROM iws_art_records WHERE department=".$this->arr[0]." AND data<=NOW() ORDER BY data DESC LIMIT ".$this->countinmain);
                        if(mysql_numrows($this->resAnons)>=1){   
                           if(ereg("\[\/:artTitle\]",$this->templateAnons)){
                              while($this->arrA=mysql_fetch_row($this->resAnons)){
                                 $this->Pre=str_replace("[/:artTitle]","<a href=\"$hostName/index.php?go=articles&orderBy=".$this->arr[0]."&anons=".$this->arrA[0]."\">".stripslashes($this->arrA[2])."</a>",$this->templateAnons);
                                 $this->Pre=str_replace("[/:artImg]",(($this->arrA[3]) ? "<a href=\"$hostName/index.php?go=articles&orderBy=".$this->arr[0]."&anons=".$this->arrA[0]."\"><img src=\"".$hostName."/ImgForArticles/m_".$this->arrA[3]."\" alt=\"".stripslashes($this->arrA[2])."\"></a>" : ""),$this->Pre);
                                 $this->Pre=str_replace("[/:artAnnouncement]",stripslashes($this->arrA[4]),$this->Pre);

                                 $this->ddate=explode(".",$this->arrA[1]);
                                 $this->Pre=str_replace("[/:artDay]",$this->ddate[0],$this->Pre);         
                                 $this->Pre=str_replace("[/:artMonth]",$lang[$this->ddate[1]],$this->Pre);         
                                 $this->Pre=str_replace("[/:artYear]",$this->ddate[2],$this->Pre);         
               
                                 $this->retCat.=$this->Pre;

                              }
                           }
                        }
                        $this->retCat=$this->retCat."</DIV>";
                     }
                  }
                  unset($this->resDepIn);
               }


            }
         }
         unset($this->resDep);
      }
      return $this->retCat;

   }



   function CurrDat()
   {
//      global $lang;
      
      $this->ddate=explode(".",date("j.m.Y"));
//      return $this->ddate[0]." ".$lang[$this->ddate[1]]." ".$this->ddate[2]." г.";
      return $this->ddate[2];
   }


   function FormSearchArt()
   {
      global $hostName,$lang;
      return "<form name=frmsearch method=get action=\"$hostName/searchArticles/\">".$lang['artSearchNameButton']."
               <div style=\"vertical-align: middle;\"><input class=searchArt name=words maxlength=200><input type=image class=search_image title=\"".$lang['artSearchButton']."\" alt=\"".$lang['artSearchButton']."\" align=top src=\"$hostName/design/searchArt.gif\"></div>
               </form>";
   }

   function NameHeadings()
   {
      global $hostName;

      $this->retCat="";
      $this->resDep=mysql_query("SELECT id, name FROM iws_art_department WHERE mid<=0 AND activ=1 ORDER BY pos");
      if(mysql_numrows($this->resDep)>=1){
           while($this->arr=mysql_fetch_row($this->resDep)){
               $this->retCat.="<a href=\"$hostName/index.php?go=articles&orderBy=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a> ";
/*
               $this->resDepIn=mysql_query("SELECT id, name FROM iws_art_department WHERE mid=".$this->arr[0]." AND activ=1 ORDER BY pos");
               if(mysql_numrows($this->resDepIn)>=1){
                  while($this->arrA=mysql_fetch_row($this->resDepIn)){
                     $this->retCat.="<a class=RubricaIn href=\"$hostName/index.php?go=articles&orderBy=".$this->arrA[0]."\">".stripslashes($this->arrA[1])."</a> ";   
                  }
                  unset($this->resDepIn);
               }
*/
           }
         unset($this->resDep);
      }
      return $this->retCat;

   }





// Форма модуля Вопрос/Ответ с капчей---------------------------------------------------------------------------------------------------------------------------

   function FormAddmess()
   {
      global $hostName, $language, $lang, $category, $start, $act, $check;
      
     
//    if(isset($act) && $act=="addmess")
//    {
//       return "";
//    } else {}
     if(!$start) $start=1;

     list($capcha,$maxlen)=mysql_fetch_row(mysql_query("SELECT capcha,maxlen FROM iws_guestpref WHERE id=1"));
 
return "<script><!--  
".($capcha ? '
function sbmn(){

   if(frm.nme.value){
      if(frm.cont.value){
         if(frm.capcha.value){
            $.ajax({
                     type: "POST",
                     url: "/scripts/capcha_chek.php",
                     data: "capcha=" + frm.capcha.value,
                     error: function(){ alert("'.$lang['ajaxError'].'    "); },
                     success: function(msg)
                        {
                           if ( msg==1 ){ 
                     if ($("textarea[name=cont]").val().length > "'.$maxlen.'" ) {  alert("'.$lang['maxlen2'].$maxlen.$lang['maxlen1'].'" + $("textarea[name=cont]").val().length  );    } else { 
                              frm.submit();}
                           } else {
                              alert("'.$lang['capchaError'].'    "); 
                              frm.capcha.focus();
                           }
                        }
                  });
         } else {
            alert("'.$lang['FieldsError'].'    ");
         }
      } else {
            alert("'.$lang['gbnt'].'     ");
            frm.cont.focus();
      }
   } else {
      alert("'.$lang['gbnn'].'     ");
      frm.nme.focus();
   }                                      

}

' : 
'
function sbmn(){
   if(frm.nme.value){
      if(frm.cont.value){
     if($("textarea[name=cont]").val().length > "'.$maxlen.'" ) {  alert("'.$lang['maxlen2'].$maxlen.$lang['maxlen1'].'" + $("textarea[name=cont]").val().length  );  } else { 
         frm.submit();}
      } else {
         alert("'.$lang['gbnt'].'     ");
         frm.cont.focus();
      }
   } else {
      alert("'.$lang['gbnn'].'     ");
      frm.nme.focus();
   }
}

')."  


//--></script>
                    
                                         <div id='Main_qa'>
                     <form action=\"".$hostName."/index.php\" name=frm method=post>
                           <input type=hidden name=go value=qa>
                           <input type=hidden name=category value=\"$category\">
                           <input type=hidden name=start value=$start>
                           <input type=hidden name=act value=addOk>
                           <div id='qa_name'>".$lang['gbname']."<br><input class='Qa_ip' name=nme maxlength=50 size=43></div>
                           <div id='qa_ci'>".$lang['gbhmp']."<br><input  class='Qa_ip' name=hmp maxlength=150 size=43></div>
                           <div id='qa_cont'>".$lang['gbtm']."<br><textarea class='Qa_tf' name=cont ></textarea></div>"
                           .($capcha ? '<div id="cap_text">'.$lang['capcha_mesage'].'</div><div id="cap_img"><img  src="'.$hostName.'/scripts/capcha.php" title="'.$lang['capcha'].'" alt="'.$lang['capcha'].'" /></div><div id="cap_fi"><input style="margin: 0px; padding: 0px;" name=capcha maxlength=4 size=6></div>' : '')."
                           <div id='qa_btn_main'> <input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/add_".$language['lng'].".gif\" title=\"".$lang['gbadd']."\" alt=\"".$lang['gbadd']."\" onclick=\"sbmn(); return false;\"></div>
                       

                     </form>
                     
                       
</div>";
   
   }
//---------------------------------------------------------------------------------------------------------------------------

   function FormSearchFiles()
   {
      global $hostName,$lang;
      return "<form name=frmsearch method=get action=\"$hostName/searchFiles/\">
               <div style=\"vertical-align: middle;\"><input class=searchFile name=words maxlength=200><input type=image class=search_image title=\"".$lang['filesSearchButton']."\" alt=\"".$lang['filesSearchButton']."\" align=absMiddle src=\"$hostName/design/searchFile.gif\"></div>
               </form>";
   }


//-------------------------------------------------------------------------------------------------------------------------

   function LastFiles(){
   global $hostName,$lang;
      list($this->lmt)=mysql_fetch_row(mysql_query("SELECT IF(countinmain>=1,countinmain,5) FROM iws_arfiles_prefernce WHERE id=1"));
      $this->result=mysql_query("SELECT A.pse, A.name, A.authors, A.description, A.file, (SELECT B.name FROM iws_arfiles_department B WHERE B.id=A.department) FROM iws_arfiles_records A ORDER BY A.data DESC LIMIT ".$this->lmt);

      if(mysql_numrows($this->result)>=1){
         list($this->template)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=15"));

         $this->lstFiles="<DIV class=FilesInMain>\n";

         if(ereg("/:filesName",$this->template)){

            include("filesArFunctions.php");
            $this->template=stripslashes($this->template);

            while($this->arr=mysql_fetch_row($this->result)){
            
               $this->PreFiles=str_replace("[/:filesName]","<a title=\"".$lang['fileslink']."\" href=\"".$hostName."/index.php?go=GetFile&uid=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a>",$this->template);
               if(ereg("/:filesAuthors",$this->template)) $this->PreFiles=str_replace("[/:filesAuthors]",stripslashes($this->arr[2]),$this->PreFiles);
               if(ereg("/:filesShortContent",$this->template)) $this->PreFiles=str_replace("[/:filesShortContent]",stripslashes($this->arr[3]),$this->PreFiles);
               if(ereg("/:filesFromList",$this->template)) $this->PreFiles=str_replace("[/:filesFromList]",$this->arr[5],$this->PreFiles);
               if(ereg("/:filesSpace",$this->template)) $this->PreFiles=str_replace("[/:filesSpace]",display_size($this->arr[4]),$this->PreFiles);
               if(ereg("/:filesExtention",$this->template)) $this->PreFiles=str_replace("[/:filesExtention]",substr($this->arr[4],-(strpos(strrev($this->arr[4]),"."))),$this->PreFiles);
               if(ereg("/:filesLink",$this->template)) $this->PreFiles=str_replace("[/:filesLink]","<a href=\"".$hostName."/index.php?go=GetFile&uid=".$this->arr[0]."\">".$lang['fileslink']."</a>",$this->PreFiles);

               $this->lstFiles.="   <DIV>".$this->PreFiles."</DIV>\n";
            
            }
            unset($this->arr,$this->result,$this->template,$this->PreFiles);

         } else {

            while($this->arr=mysql_fetch_row($this->result)){
               $this->lstFiles.="<DIV><b>".stripslashes($this->arr[2])."</b><br><a href=\"".$hostName."/index.php?go=GetFile&uid=".$this->arr[0]."\">".stripslashes($this->arr[1])."</a></DIV>";
            }
            unset($this->arr,$this->result,$this->template);

         }

         $this->lstFiles.="</DIV><a class=FilesAll href=\"".$hostName."/index.php?go=filesarchive\">".$lang['filesAll']."</a>";

         unset($this->lmt);

         return $this->lstFiles;
      }
   }


//---------------------------------------------------------------------------------------------------------------------------

   function rotation_view(){
      global $QUERY_STRING;
      if(!$QUERY_STRING || ereg("go=main",$QUERY_STRING)){
         $dbresult = mysql_query("select url,href,alt from iws_rotation where main=1");
         if(mysql_numrows($dbresult)>=1){
            list($imgRtt,$imgHref,$imgAlt) = mysql_fetch_row($dbresult);
            if($imgHref){
               return "<img style=\"cursor:hand; cursor:pointer;\" onclick=\"document.location='".$imgHref."'\" src=\"".$imgRtt."\" title=\"".$imgAlt."\" border=0  alt=\"".$imgAlt."\">";
//             return "<a href=\"".$imgHref."\" title=\"".$imgAlt."\"><img src=\"".$imgRtt."\" border=0  alt=\"".$imgAlt."\"></a>";
            } else {
               return "<img src=\"".$imgRtt."\" title=\"".$imgAlt."\" border=0  alt=\"".$imgAlt."\">";
            }
         } else {
            return $this->genericRotation();
         }
      } else {
         return $this->genericRotation();
      }
   }

   function genericRotation(){
      $dbresult = mysql_query("select id from iws_rotation");
      if(mysql_numrows($dbresult)>=1){
         if(mysql_numrows($dbresult)==1){
            list($id) = mysql_fetch_row($dbresult);
            list($imgRtt,$imgHref,$imgAlt) = mysql_fetch_row(mysql_query("select url,href,alt from iws_rotation where id=$id"));
            if($imgHref){
               return "<img style=\"cursor:hand; cursor:pointer;\" onclick=\"document.location='".$imgHref."'\" src=\"".$imgRtt."\" title=\"".$imgAlt."\" border=0  alt=\"".$imgAlt."\">";
//             return "<a href=\"".$imgHref."\" title=\"".$imgAlt."\"><img src=\"".$imgRtt."\" border=0  alt=\"".$imgAlt."\"></a>";
            } else {
               return "<img src=\"".$imgRtt."\" title=\"".$imgAlt."\" border=0 alt=\"".$imgAlt."\">";
            }
         } else {
            $i=0;
            while(list($preId)=mysql_fetch_row($dbresult)){ $arrId[$i]=$preId; $i++; }
            mt_srand((double)microtime()*1000000);
            $rnd=mt_rand(1,count($arrId));
            list($imgRtt,$imgHref,$imgAlt) = mysql_fetch_row(mysql_query("select url,href,alt from iws_rotation where id=".$arrId[$rnd-1]));
            if($imgHref){
               return "<img style=\"cursor:hand; cursor:pointer;\" onclick=\"document.location='".$imgHref."'\" src=\"".$imgRtt."\" title=\"".$imgAlt."\" border=0  alt=\"".$imgAlt."\">";
//             return "<a href=\"".$imgHref."\" title=\"".$imgAlt."\"><img src=\"".$imgRtt."\" border=0  alt=\"".$imgAlt."\"></a>";
            } else {
               return "<img src=\"".$imgRtt."\" title=\"".$imgAlt."\" border=0 alt=\"".$imgAlt."\">";
            }
         }
      } else {
         return "";
      }
   }

//---------------------------------------------------------------------------------------------------------------------------

//функция авторизации

   function logon($lgn,$pwd){
      global $uservar,$hostName;
      if(empty($lgn) or empty($pwd)) return false;

      $lgn = substr($lgn, 0, 30);
      $pwd = substr($pwd, 0, 25);

      if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $lgn)) return false;

      $dbresult = mysql_query("SELECT id,pwd,grp FROM iws_user WHERE aid='".$lgn."'");
      if(!$dbresult) return false;
      if(mysql_numrows($dbresult) != 1) {
         mysql_free_result($dbresult);
         return false;
      }
      list($did,$pass,$grp) = mysql_fetch_row($dbresult);
      mysql_free_result($dbresult);

      if($pass != crypt($pwd,$pass))   return false;

      $uservar['tr']="ars";
      $uservar['id']=$did;
      $uservar['grp']=$grp;
      unset($did,$pass);
      header("Location: ".$hostName."/");
   }

//---------------------------------------------------------------------------------------------------------------------------

   function viewopros($bl){
   global $hostName,$lang;
      list($vw)=mysql_fetch_row(mysql_query("select IF(view=1,1,0) from iws_opros_pref where id=1"));
      if($vw){
         $result=mysql_query("select A.name,MAX(B.golos) from iws_opros A, iws_oprot B where A.id=$bl and A.activ=1 and B.oid=A.id GROUP BY A.name");
         if(mysql_numrows($result)>=1){
            list($nme,$mxi)=mysql_fetch_row($result);
            $name=stripslashes($name);
            $res=mysql_query("select otvet,golos from iws_oprot where oid=$bl order by id"); 
            while($arr=mysql_fetch_row($res)){
               $otvet[]=stripslashes($arr[0]);
               $gl[]=$arr[1];
               $sum+=$arr[1];
            }
            $cnt=count($otvet)-1;
            for($i=0;$i<=$cnt;$i++){
               if($sum>0){
                  $per=substr(($gl[$i]/$sum)*100,0,5);
               }else{
                  $per = 0;
               }
               if($mxi>0){
                  $lnw=($gl[$i]/$mxi)*100;
               }else{
                 $lnw = 0;
               }
               $resl.="<tr><td align=right>".$otvet[$i].": </td><td>"
               ."<img src=\"".$hostName."/design/vote.gif\" width=".(3*$lnw)." height=10 border=0> <b>".$gl[$i]."</b> ( ".$per."% )</td></tr>";
            }
            $this->content.="<table border=0 cellpadding=3 cellspacing=0>"
                                 ."<tr><td>".$lang['resv']."</td></tr></table>"
                                 ."<table border=0 cellpadding=3 cellspacing=2>"
                                 ."<tr><td></td><td><b>$nme</b></td></tr>"
                                 .$resl
                                 ."</table>";
            return true;
         }else{
            return false;
         }
      }else{
         return false;
      }
   }

   function voiceAd($bl,$did){
   global $hostName,$voiced;
      if($bl && $did){
         list($vw)=mysql_fetch_row(mysql_query("select IF(view=1,1,0) from iws_opros_pref where id=1"));
         if($voiced != $bl){
            if(mysql_query("update iws_oprot set golos=golos+1 where id=".$did." and oid=".$bl)){
               SetCookie("voiced",$bl,time()+7200);  
            }
            if($vw){
//          header("location: ".$hostName."/index.php?go=opros&act=viewresult&id=".$bl);
            header("location: ".$hostName."/opros/viewresult/".$bl);
               return true;
            }else{
//          header("location: ".$hostName."/index.php?go=main");
            header("location: ".$hostName."/main");
               return true;
            }
         }else{
            if($vw){
//             header("location: ".$hostName."/index.php?go=opros&act=viewresult&id=".$bl);
               header("location: ".$hostName."/opros/viewresult/".$bl);
               return true;
            }else{
               header("location: ".$hostName."/main");
//             header("location: ".$hostName."/index.php?go=main");
               return true;
            }
         }
      }else{
         return false;
      }
   }
   
//---------------------------------------------------------------------------------------------------------------------------

   function LastNews(){
   global $hostName,$language,$lang;
      list($lmt)=mysql_fetch_row(mysql_query("select IF(lmtveiw>=1,lmtveiw,10) from iws_newspref where id=1"));
      $result=mysql_query("SELECT id,DATE_FORMAT(datu,'%e.%m.%Y'),title FROM iws_news WHERE arc=0 AND lng='".$language['lng']."' AND datu<=NOW() ORDER BY datu DESC LIMIT ".$lmt);

      if(mysql_numrows($result)>=1){
         list($tmpl)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=1"));

         $this->lstnews="<DIV class=NewsInMain>\n";
         if(ereg("(:newsday|:newsmonth|:newsyear)",$tmpl) && ereg(":newshdr",$tmpl)){
            while($arr=mysql_fetch_row($result)){
               $ddate=explode(".",$arr[1]);
               $this->lstnews.="<DIV>"
                              .str_replace("[/:newshdr]","<a href=\"".$hostName."/index.php?go=news&id=".$arr[0]."&archiv=0&act=view\">".stripslashes($arr[2])."</a>",
                              str_replace("[/:newsday]",$ddate[0],
                              str_replace("[/:newsmonth]",$lang[$ddate[1]],
                              str_replace("[/:newsyear]",$ddate[2],stripslashes($tmpl)))))
                              ."</DIV>\n";
         }
         }else{
            while($arr=mysql_fetch_row($result)){
               $this->lstnews.="<DIV><b>".$arr[1]."</b>&nbsp;<a class=newsm href=\"".$hostName."/index.php?go=news&id=".$arr[0]."&archiv=0&act=view\">".stripslashes($arr[2])."</a></DIV>";
         }
         }
         $this->lstnews.="</DIV><a class=newsAll href=\"".$hostName."/index.php?go=news&archiv=0\">".$lang['mnews']."</a>";         
         return $this->lstnews;
      }
   }

//---------------------------------------------------------------------------------------------------------------------------

   function retEr(){
      global $language;
      list($this->content)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=5"));
      $this->content = stripslashes($this->content);
   }

//---------------------------------------------------------------------------------------------------------------------------
   
   function get_design(){
      global $language;
      $result = mysql_query("select templ".$language['lng']." from iws_html_templ_design");
      if(mysql_numrows($result)>=1){
         list($pg)=mysql_fetch_row($result);
         if($pg){
            $this->design_page = stripslashes($pg);
         }else{
            $this->design_page = false;
         }
      } else {
         $this->design_page = false;
      }
   }

   function get_design_inBlock($blockM){
      $result = mysql_query("SELECT A.template FROM iws_html_templ A RIGHT JOIN iws_blockmenu B ON A.id=B.inTemplate AND B.bid=$blockM AND B.activ=1 WHERE A.inTemplate=1");
      if(mysql_numrows($result)>=1){
         list($pg)=mysql_fetch_row($result);
         if($pg && ereg("/:content",$pg)){
            $this->design_page = stripslashes($pg);
         } else {
            $this->design_page = false;
         }
      } else {
         $this->design_page = false;
      }
   }

//---------------------------------------------------------------------------------------------------------------------------

   function get_vrs($lc = 2){
      unset($this->arr_vrs);
      $result = mysql_query("select vr from iws_vars where lc=$lc");
      if(mysql_numrows($result)>=1){
         $i = 0;
         while($arr=mysql_fetch_row($result)) $this->arr_vrs[$i++] = $arr[0];
      }
   }

//--------------------------------------------------------------------------------------------------------------------------- 

   function get_ptp ($menu = 0,$block = 0) {
      global $QUERY_STRING,$title,$hostName,$lang,$language,$keyword,$start,$category,$descript,$robots,$orderBy,$anons;

      $this->patMN="<DIV class=path>";

      list($this->tmpl)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=8"));
      if(ereg("\[\/:menupunkt\]",$this->tmpl)){

         if($QUERY_STRING) $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."\">".$lang['main']."</a> ",stripslashes($this->tmpl));

         if(ereg("go=news",$QUERY_STRING)){
            $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=news&archiv=0\">".$lang['newslink']."</a>",stripslashes($this->tmpl));
            $title=$lang['newslink'];

         }elseif(ereg("go=photosA",$QUERY_STRING)){

            $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=photosA\">".$lang['PhotoTitle']."</a> ",stripslashes($this->tmpl));
            $title=$lang['PhotoTitle'];
            if(isset($_GET['rubric']) && $_GET['rubric']>=1){   
               $this->resCategory = mysql_query("SELECT name FROM iws_photos_category WHERE id=".$_GET['rubric']);
               if(mysql_numrows($this->resCategory)>=1){
                  list($this->nameCat)=mysql_fetch_row($this->resCategory);
                  $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=photosA&rubric=".$_GET['rubric']."\">".stripslashes($this->nameCat)."</a>",stripslashes($this->tmpl));
                  $title.=" &#0139; ".stripslashes($this->nameCat);
                  $keyword = stripslashes($this->nameCat).", ".$keyword;
               }
               mysql_free_result($this->resCategory);
            }

      
         }elseif(ereg("go=filesarchive_A",$QUERY_STRING)){

            $title=$lang['A_filesTitle'];
            if(isset($orderBy) && $orderBy>=1){   
               $this->resCategory = mysql_query("SELECT name FROM iws_arfiles_A_department WHERE id=$orderBy");
                  if(mysql_numrows($this->resCategory)>=1){
                     list($this->nameCat)=mysql_fetch_row($this->resCategory);
                     $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=filesarchive_A&orderBy=".$orderBy."\">".stripslashes($this->nameCat)."</a>",stripslashes($this->tmpl));
                     $title=stripslashes($this->nameCat);
                     $keyword = stripslashes($this->nameCat).", ".$keyword;
                  }
                  if(isset($_GET['id']) && is_numeric($_GET['id'])){
                     $this->resCategory = mysql_query("SELECT name,keyw,name_e,keyw_e,description FROM iws_arfiles_A_records WHERE id=".$_GET['id']);
                     if(mysql_numrows($this->resCategory)===1){
                        $this->TxtAll=mysql_fetch_row($this->resCategory);
                        $title=stripslashes($this->TxtAll[0]);
                        $keyword = stripslashes($this->TxtAll[1]).", ".stripslashes($this->TxtAll[3]).", ".$keyword;
                        $descript = stripslashes($this->TxtAll[4]); 
                     }
                  }
               mysql_free_result($this->resCategory);
            } elseif(isset($_GET['Rubric']) && $_GET['Rubric']>=1){   
               $this->resCategory = mysql_query("SELECT name FROM iws_arfiles_A_rubric WHERE id=".$_GET['Rubric']);
               if(mysql_numrows($this->resCategory)>=1){
                  list($this->nameCat)=mysql_fetch_row($this->resCategory);
                  $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=filesarchive_A&Rubric=".$_GET['Rubric']."\">".stripslashes($this->nameCat)."</a>",stripslashes($this->tmpl));
                  $title=stripslashes($this->nameCat);
                  $keyword = stripslashes($this->nameCat).", ".$keyword;
               }
               mysql_free_result($this->resCategory);
            }
            if(ereg("act=search",$QUERY_STRING)){
               $this->patMN.=str_replace("[/:menupunkt]",$lang['A_filesResultSearch'],stripslashes($this->tmpl));
               $title=$lang['A_filesResultSearch'];
            }



         }elseif(ereg("go=articles",$QUERY_STRING)){

            if(isset($orderBy) && $orderBy>=1){  
               $this->resCategory = mysql_query("SELECT name,mid FROM iws_art_department WHERE id=$orderBy AND activ=1");
               if(mysql_numrows($this->resCategory)>=1){
                  $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=articles&act=AllArt\">".$lang['artTitlelink']."</a> ",stripslashes($this->tmpl));
                  list($this->nameCat,$this->mid)=mysql_fetch_row($this->resCategory);
                  if($this->mid>=1)
                  {
                     $this->resCategoryMid = mysql_query("SELECT name FROM iws_art_department WHERE id=".$this->mid." AND activ=1");
                     if(mysql_numrows($this->resCategoryMid)>=1){
                        list($this->nameCatMid)=mysql_fetch_row($this->resCategoryMid);
                        $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=articles&orderBy=".$this->mid."\">".stripslashes($this->nameCatMid)."</a> ",stripslashes($this->tmpl));
                        $title=stripslashes($this->nameCatMid);
                        $keyword = stripslashes($this->nameCatMid).", ".$keyword;
                        mysql_free_result($this->resCategoryMid);
                     }
                  }
                  
                  $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=articles&orderBy=$orderBy\">".stripslashes($this->nameCat)."</a>",stripslashes($this->tmpl));
                  $title=stripslashes($this->nameCat);
                  $keyword = stripslashes($this->nameCat).", ".$keyword;
               }
               mysql_free_result($this->resCategory);

               if(isset($anons) && $anons>=1){
                  $this->resAnons=mysql_query("SELECT name FROM iws_art_records WHERE id=$anons");
                  if(mysql_numrows($this->resAnons)>=1){
                     list($this->nameAnons)=mysql_fetch_row($this->resAnons);
                     $title=stripslashes($this->nameAnons);
                     mysql_free_result($this->resAnons);
                  }
               }
            } elseif(ereg("act=AllArt",$QUERY_STRING)){
               $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=articles&act=AllArt\">".$lang['artTitlelink']."</a>",stripslashes($this->tmpl));
               $title=$lang['artTitlelink'];

            } elseif(ereg("act=search",$QUERY_STRING)){
               $this->patMN.=str_replace("[/:menupunkt]",$lang['artResultSearch'],stripslashes($this->tmpl));
               $title=$lang['artResultSearch'];
            }


         }elseif(ereg("go=map",$QUERY_STRING)){
            $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=map\">".$lang['map']."</a>",stripslashes($this->tmpl));
            $title=$lang['map'];
         }elseif(ereg("go=search",$QUERY_STRING)){
            $this->patMN.=str_replace("[/:menupunkt]",$lang['ress'],stripslashes($this->tmpl));
            $title=$lang['ress'];
         }elseif(ereg("act=viewresult",$QUERY_STRING)){
            $this->patMN.=str_replace("[/:menupunkt]",$lang['resvP'],stripslashes($this->tmpl));
            $title=$lang['resvP'];
         }elseif(ereg("go=qa",$QUERY_STRING)){
            $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=qa\">".$lang['gb']."</a> ",stripslashes($this->tmpl));
            $title=$lang['gb'];
            if(isset($category) && $category>=1){  
               $this->resCategory = mysql_query("select name from iws_guestbk_category where id=$category AND activ=1");
               if(mysql_numrows($this->resCategory)>=1){
                  list($this->nameCat)=mysql_fetch_row($this->resCategory);
                  $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php?go=qa&category=$category\">".stripslashes($this->nameCat)."</a>",stripslashes($this->tmpl));
                  $title.=" &#0139; ".stripslashes($this->nameCat);
                  $keyword = stripslashes($this->nameCat).", ".$keyword;
               }
               mysql_free_result($this->resCategory);
            }
         }else{
            if($menu >= 1 && $block >=1){    
            $result=mysql_query("select A.idm,A.name,A.urlmenu from iws_menu A,iws_menu B "
                     ."where A.blk=".$block." and B.idm=".$menu." and B.m_left BETWEEN A.m_left AND A.m_right ORDER BY A.m_left");        
               if(mysql_numrows($result)>=1){
                  while($arr = mysql_fetch_row($result)){
                     $this->patMN.=str_replace("[/:menupunkt]","<a href=\"".$hostName."/index.php".$arr[2]."&block=".$block."&menu=".$arr[0]."\">".$arr[1]."</a> ",stripslashes($this->tmpl));
                     $title=$arr[1];
                     $keyword = $arr[1];
                  }
               }
               list($tt,$rob,$keyw,$dscr)=mysql_fetch_row(mysql_query("select title,robots,keywords,descr from iws_page_simple where mid=$menu"));
               if(!empty($tt)) $title = $tt;
               if(!empty($rob)) $robots = $rob;
               if(!empty($keyw)) $keyword = $keyw;
               if(!empty($dscr)) $descript = $dscr;
            }
         }

      } else {

         if($QUERY_STRING) $this->patMN.="// <a href=\"".$hostName."\">".$lang['main']."</a>";

         if(ereg("go=news",$QUERY_STRING)){
            $this->patMN.=" // <a href=\"".$hostName."/index.php?go=news&archiv=0\">".$lang['newslink']."</a>";
            $title.=" // ".$lang['newslink'];
         }elseif(ereg("go=filesarchive",$QUERY_STRING)){
            $this->patMN.=" // <a href=\"".$hostName."/index.php?go=filesarchive\">".$lang['filesTitle']."</a> ";
            $title.=" // ".$lang['filesTitle'];
            if(ereg("act=search",$QUERY_STRING)){
               $this->patMN.=" // ".$lang['filesResultSearch'];
               $title.=" // ".$lang['filesResultSearch'];
            }
         }elseif(ereg("go=map",$QUERY_STRING)){
            $this->patMN.=" // <a href=\"".$hostName."/index.php?go=map\">".$lang['map']."</a>";            
            $title.=" // ".$lang['map'];
         }elseif(ereg("go=search",$QUERY_STRING)){
            $this->patMN.=" // ".$lang['ress'];          
            $title.=" // ".$lang['ress'];
         }elseif(ereg("act=viewresult",$QUERY_STRING)){
            $this->patMN.=" // ".$lang['resvP'];          
            $title.=" // ".$lang['resvP'];
         }elseif(ereg("go=qa",$QUERY_STRING)){
            $this->patMN.=" // <a href=\"".$hostName."/index.php?go=qa\">".$lang['gb']."</a> ";          
            $title.=" // ".$lang['gb'];
            if(isset($category) && $category>=1){  
               $this->resCategory = mysql_query("select name from iws_guestbk_category where id=$category AND activ=1");
               if(mysql_numrows($this->resCategory)>=1){
                  list($this->nameCat)=mysql_fetch_row($this->resCategory);
                  $this->patMN.=" // <a href=\"".$hostName."/index.php?go=qa&category=$category\">".stripslashes($this->nameCat)."</a>";
                  $title.=" // ".stripslashes($this->nameCat);
                  $keyword = stripslashes($this->nameCat).", ".$keyword;
               }
               mysql_free_result($this->resCategory);
            }
         } else {
            if($menu >= 1 && $block >=1){    
            $result=mysql_query("select A.idm,A.name,A.urlmenu from iws_menu A,iws_menu B "
                     ."where A.blk=".$block." and B.idm=".$menu." and B.m_left BETWEEN A.m_left AND A.m_right ORDER BY A.m_left");        
               if(mysql_numrows($result)>=1){
                  while($arr = mysql_fetch_row($result)){
                     $this->patMN.=" // <a href=\"".$hostName."/index.php"
                           .$arr[2]."&block=".$block."&menu=".$arr[0]."\">".$arr[1]."</a>";           
                     $title.=" // ".$arr[1];
                     $keyword = $arr[1].", ".$keyword;
                  }
               }
               list($tt,$rob,$keyw,$dscr)=mysql_fetch_row(mysql_query("select title,robots,keywords,descr from iws_page_simple where mid=$menu"));
               if(!empty($tt)) $title = $tt;
               if(!empty($rob)) $robots = $rob;
               if(!empty($keyw)) $keyword = $keyw;
               if(!empty($dscr)) $descript = $dscr;

            }

         }

      }  
      $this->patMN.="</DIV>";
      return $this->patMN;
   }

//---------------------------------------------------------------------------------------------------------------------------

   function search_view ($wrdRet='') {
   global $hostName,$language,$lang;


      return "<FORM NAME=\"frmsearch\" CLASS=\"search\" METHOD=GET ACTION=\"".$hostName."/search/\">
               <div><input class=search_input name=words maxlength=120 value='$wrdRet'><input type=image class=search_image title=\"".$lang['srh']."\" alt=\"".$lang['srh']."\" src=\"".$hostName."/design/search_".$language['lng'].".gif\"></div>
               </FORM>";

//    return "<form name=frmsearch class=search method=get action=\"".$hostName."/search/\">
//               <div style=\"vertical-align: middle;\"><input class=search_input name=words maxlength=120 value='$wrdRet'><input type=image class=search_image title=\"".$lang['srh']."\" alt=\"".$lang['srh']."\" align=top src=\"".$hostName."/design/search_".$language['lng'].".gif\"></div>
//               </form>";

//    return "<nobr><input class=\"search\" name=wrd style=\"width:185px;\"><IMG SRC=\"".$hostName."/design/search_".$language['lng'].".gif\" "
//          ."border=0 align=absMiddle alt=\"".$lang['srh']."\" style=\"cursor:hand\" "
//          ."onClick=\"javascript: if(wrd.value){ document.location='".$hostName."/index.php?go=search&words='+wrd.value; }\"></nobr>";  

//    return "<table border=0 cellpadding=0 cellspacing=0><tr><td><input class=\"search\" name=wrd size=40></td><td><IMG SRC=\"".$hostName."/design/search_".$language['lng'].".gif\" "
//          ."border=0 alt=\"".$lang['srh']."\" style=\"cursor:hand\" "
//          ."onclick=\"if(wrd.value) document.location='".$hostName."/index.php?go=search&words='+wrd.value;\"></td></tr></table>";   
   }

//---------------------------------------------------------------------------------------------------------------------------

   function login_view () {
   global $hostName,$language,$lang;
      return "<script><!--
               function goLgn(){
                if(fr.lgn.value && fr.pwd.value) fr.submit();
               }
               //--></script>
            <table cellpadding=0 cellspacing=0 border=0 class=FormUsers><form name=fr method=\"post\" action=\"".$hostName."/index.php\">
            <input type=hidden name=go value=logn>
            <tr valign=bottom><td>".$lang['name']." <input name=lgn maxlength=30 size=20></td>
            <td>".$lang['pwd']." <input type=password name=pwd size=20></td>
            <td>
            <input class=btn type=\"image\" src=\"".$hostName."/design/logn_".$language['lng'].".gif\" title=\"".$lang['ok']."\" alt=\"".$lang['ok']."\" onclick=\"goLgn(); return false;\">
            </td></tr>
            </form></table>";
   }

//---------------------------------------------------------------------------------------------------------------------------

   function map_view () {
   global $hostName,$language,$lang;
      return "<a href=\"".$hostName."/index.php?go=map\"><IMG SRC=\"".$hostName."/design/tree_".$language['lng'].".gif\" border=0 title=\"".$lang['map']."\" alt=\"".$lang['map']."\"></a>";
   }

//  Функции загона статистики ---------------------------------------------------------------------------------------------------------------------------

   function stts(){
   global $go,$menu,$language,$anons;

      $useragent = $_SERVER['HTTP_USER_AGENT'];
      if ( preg_match("/Mozilla|Opera/i", $useragent) && !preg_match("!Bot/|robot|Slurp/|yahoo!i", $useragent))
      {

      $hostT = parse_url(getenv("HTTP_REFERER"));
      $tempar=$this->statcoock($_SERVER["REMOTE_ADDR"]);
      if($go=="qa"){
         mysql_query("insert into iws_statistics (dt,frm,ip_adr,url,menu,lng,coockie) values ('".date('Y-m-d H:i:s', time())."','".$hostT['host']."','".$tempar[1]."','qa',3,'".$language['lng']."','".$tempar[0]."')");
      } elseif($go=="news"){
         mysql_query("insert into iws_statistics (dt,frm,ip_adr,url,menu,lng,coockie) values ('".date('Y-m-d H:i:s', time())."','".$hostT['host']."','".$tempar[1]."','news',2,'".$language['lng']."','".$tempar[0]."')");
      } else {
         if(!$menu) $menu = 0;
         if($go=="articles"){
            $go="arts";
            if(isset($anons) && is_numeric($anons)) $menu=$anons;
         }

         if(!isset($go) || !$go || ($go!="main" && $go!="news" && $go!="page" && $go!="mpage" && $go!="arts") ) $go="main";
         if($go!="logn" && $go!="opros" && $go!="map" && $go!="search" && $go!="viewresult"){
               mysql_query("insert into iws_statistics (dt,frm,ip_adr,url,menu,lng,coockie) values ('".date('Y-m-d H:i:s', time())."','".$hostT['host']."','".$tempar[1]."','$go',$menu,'".$language['lng']."','".$tempar[0]."')");
         }
      }

      }
   }

  
     function statcoock($ip_host){
     if ($_COOKIE['CI']){
         return unserialize(base64_decode($_COOKIE['CI']));
     } else {
         $this->CI=Array($this->unicumIdCoocke(),$ip_host);
         $arr=mysql_fetch_row(mysql_query("SELECT ip,enabled FROM `iws_stat_inner_ip` WHERE id=1"));
         if ($arr[1]==1){
            if(substr_count($arr[0],$ip_host)>=1){ 
               setcookie('CI',base64_encode(serialize($this->CI)));
            } else {
               setcookie('CI',base64_encode(serialize($this->CI)),mktime(0,0,0,date(m)+1,1,date(Y)));
            }
         } else {
            setcookie('CI',base64_encode(serialize($this->CI)),mktime(0,0,0,date(m)+1,1,date(Y)));
         }
         return $this->CI;
      }
      }

   function unicumIdCoocke(){  return md5(uniqid(rand(),1));  }


}

?>