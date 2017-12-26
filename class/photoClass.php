<?php

Class FNAlbums {

   var $retcon;
   
   var $countinpage;
   var $countinmain;

   var $templateAlbum;
   var $templateAll;


   function retPreference()
   {
      list($this->countinpage, $this->countinmain)=mysql_fetch_row(mysql_query("SELECT IF(countInPage>=1,countInPage,15), IF(countInMain>=1,countInMain,15) FROM iws_photos_prefernce WHERE id=1"));
   }


   function retTemplates($t)
   {
      switch ($t){
         case 1:
            list($this->templateAll)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=26"));
            $this->templateAll=stripslashes($this->templateAll); 
         break;
         case 2:
            list($this->templateAll)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=25"));
            $this->templateAll=stripslashes($this->templateAll);
         break;
      }

      list($this->templateAlbum)=mysql_fetch_row(mysql_query("SELECT templateru FROM iws_html_templ_vivod WHERE id=24"));
      $this->templateAlbum=stripslashes($this->templateAlbum);
      
   }



//-----------------------------------------------------------------------------------------------------------------------------

  
   function GetAlbum($idAlbum,$hostN,$title)
   {

         $result=mysql_query("SELECT alt,file FROM `iws_photos_records` WHERE aid =".$idAlbum." ORDER BY id");
         if(mysql_num_rows($result)!=0){
            $out_form="<div class='album' alt='".$title."' name='album".$idAlbum."'>
                       <div class='alb_cont' id='alb_cont".$idAlbum."'>
                       <div id='btn_left' class='btn_left_noactive'>&nbsp;</div>
                       <div class='album_box' id='album_box".$idAlbum."'><div class='album_img' id='album_img".$idAlbum."' alt='".$title."'>";
            $a=0;
         
            while($arr = mysql_fetch_row($result)){
               $a++;

               $size = getimagesize($hostN."/PhotoAlbums/s_".$arr[1]);
               $out_form.="<a href='".$hostN."/PhotoAlbums/".$arr[1]."' rel='lightbox[album".$idAlbum."]' title='".$arr[0]."' class='pre'><img src='".$hostN."/PhotoAlbums/s_".$arr[1]."' width='".$size[0]."' height='".$size[1]."'></a>";
            }
               $out_form.="</div></div><div id='btn_right' class='btn_right_active' alt='".$a."'>&nbsp;</div></div>
                           <script type='text/javascript'>
                           Album_pre['album".$idAlbum."']=[".$a."];
                           </script>
                           </div><div style='clear:both;'>&nbsp;</div>";
               return $out_form;
   
         } else {
            return "";
         }
   }


//----------------------------------------------------------------------------------------------------------------------------------------

   function AlbumRubricTag($orderBy,$start,$rt)
   {
      global $hostName;

      $start = substr($start,0,10);

      $orderBy=mysql_real_escape_string(addslashes(substr($orderBy,0,200)));

      if($rt==0){
        $this->resCategory = mysql_query("SELECT id FROM iws_photos_category WHERE id=".$orderBy." AND view=1");
        if(!mysql_numrows($this->resCategory)) return false;
        $this->whereRule="WHERE cid=".$orderBy;
      } else if($rt==1) {
        $this->whereRule="WHERE cid>0 AND hashtags LIKE '%#".$orderBy."#%'";
      }

      include('languages/lang_ru.php');
      include('photoFunctions.php');
      $this->retPreference();
      if(isset($_GET['tag']) && $_GET['tag']) $_GET['tag']=urlencode($_GET['tag']);
      $QS = http_build_query($_GET);
      $this->prom=numlink($start, ereg_replace("&start=".$start,"",$QS), "iws_photos_albums", $this->whereRule, $this->countinpage, $lang, $hostName,$rt);
      if(!$this->prom) return false; 

         $this->retTemplates(2);
         if(ereg("/:photoAlbumAll",$this->templateAll) && ereg("/:photoAlbumList",$this->templateAll)){

         $this->retcon=str_replace("[/:photoAlbumAll]",$this->AllAlbums($hostName, $orderBy, $start, $rt),$this->templateAll);
         if($this->prom){ $this->retcon=str_replace("[/:photoAlbumList]",$this->prom,$this->retcon); } else { $this->retcon=str_replace("[/:photoAlbumList]","",$this->retcon); }
         if(ereg("/:photoAlbumTitleRubric",$this->templateAll)) $this->retcon=str_replace("[/:photoAlbumTitleRubric]",(!$rt ? '' : $lang['PhotoLabel'].$orderBy),$this->retcon);
         if(ereg("/:photoHashtagsList",$this->templateAll)) $this->retcon=str_replace("[/:photoHashtagsList]",$this->GetTags($hostName,$lang['PhotoAlbumText'],$orderBy),$this->retcon);

         }
      return true;
   }


//----------------------------------------------------------------------------------------------------------------------------------------------------------------


   function AllAlbums($hostN, $orderBy="", $start="", $rt=2)
   {

      if($rt==0){
         $this->result=mysql_query("SELECT A.id, A.title, A.description, A.hashtags, (SELECT COUNT(B.id) FROM iws_photos_records B WHERE B.aid=A.id) 
                                 FROM iws_photos_albums A WHERE A.cid=".$orderBy." ORDER BY A.data DESC LIMIT ".($start-1).",".$this->countinpage);
      } else if($rt==1) {
         $this->result=mysql_query("SELECT A.id, A.title, A.description, A.hashtags, (SELECT COUNT(B.id) FROM iws_photos_records B WHERE B.aid=A.id) 
                                 FROM iws_photos_albums A WHERE A.cid>0 AND A.hashtags LIKE '%#".$orderBy."#%' ORDER BY A.data DESC LIMIT ".($start-1).",".$this->countinpage);

      } else if($rt==2) {
         $this->result=mysql_query("SELECT A.id, A.title, A.description, A.hashtags, (SELECT COUNT(B.id) FROM iws_photos_records B WHERE B.aid=A.id) 
                                 FROM iws_photos_albums A WHERE A.cid>0 AND (SELECT C.view FROM iws_photos_category C WHERE C.id=A.cid)=1 ORDER BY A.data DESC LIMIT ".$this->countinmain);
      }      


      if(mysql_numrows($this->result)>=1){
         $this->lstAlbum="";
         if(ereg("/:photoAlbumTitle",$this->templateAlbum) && ereg("/:photoAlbumGalery",$this->templateAlbum)){
            while($this->arr=mysql_fetch_row($this->result)){
               if($this->arr[4]>=1){
      
                  $this->preAlbum=str_replace("[/:photoAlbumTitle]",$this->arr[1],$this->templateAlbum);
                  $this->preAlbum=str_replace("[/:photoAlbumGalery]",$this->GetAlbum($this->arr[0],$hostN,$this->arr[1]),$this->preAlbum);
                  if(ereg("/:photoAlbumDescription",$this->templateAlbum)) $this->preAlbum=str_replace("[/:photoAlbumDescription]",$this->arr[2],$this->preAlbum);
                  if(ereg("/:photoAlbumCountPhoto",$this->templateAlbum)) $this->preAlbum=str_replace("[/:photoAlbumCountPhoto]",$this->arr[4],$this->preAlbum);
                  if(ereg("/:photoAlbumHashtags",$this->templateAlbum)){

                     $this->hasht=explode("#",trim($this->arr[3],"#"));
                     $this->cntHS=count($this->hasht);
         
                     for($iH=0; $iH<=($this->cntHS-1); $iH++){
                        if(!$iH){
                           $this->hashString="<a href=\"".$hostN."/index.php?go=photosA&tag=".urlencode($this->hasht[0])."\">".$this->hasht[0]."</a>";
                        } else {
                           $this->hashString.=", <a href=\"".$hostN."/index.php?go=photosA&tag=".urlencode($this->hasht[$iH])."\">".$this->hasht[$iH]."</a>";
                        }
                     }

                     $this->preAlbum=str_replace("[/:photoAlbumHashtags]",$this->hashString,$this->preAlbum);

                  }

                  $this->lstAlbum.="<DIV class=AlbumInPage>\n".$this->preAlbum."</DIV>\n\n";
               }
            }
         }
         unset($this->arr,$this->result);
         return $this->lstAlbum;
      }
   }



//----------------------------------------------------------------------------------------------------------

   function AlbumAll()
   {
      global $hostName;

      include('languages/lang_ru.php');
      $this->retPreference();
      $this->retTemplates(1);
      if(ereg("/:photoRubricAll",$this->templateAll)){

         $this->retcon=str_replace("[/:photoRubricAll]",$this->AllAlbums($hostName, "", "", 2),$this->templateAll);
         if(ereg("/:photoHashtagsList",$this->templateAll)) $this->retcon=str_replace("[/:photoHashtagsList]",$this->GetTags($hostName,$lang['PhotoAlbumText'],$orderBy),$this->retcon);

      }
      return true;

   }


//------------------------------------------------------------------------------------------------------------

   function GetTags($hostN,$albumText,$currTag="")
   {
      $this->lstTags="<div class='content-tags'>";
      $this->result=mysql_query("SELECT A.name,(SELECT COUNT(B.id) FROM iws_photos_albums B WHERE B.cid>0 AND B.hashtags LIKE CONCAT('%#',A.name,'#%')), (SELECT ROUND(LN(COUNT(B.id)),2)+1 FROM iws_photos_albums B WHERE B.cid>0 AND B.hashtags LIKE CONCAT('%#',A.name,'#%')) FROM iws_photos_hashtags A WHERE (SELECT COUNT(B.id) FROM iws_photos_albums B WHERE B.cid>0 AND B.hashtags LIKE CONCAT('%#',A.name,'#%'))>0 ORDER BY A.name");
      if(mysql_numrows($this->result)>=1){
         while($this->arr=mysql_fetch_row($this->result)){
            if($this->arr[0]==$currTag){
               $this->lstTags.="<a class=\"currTag\" href=\"".$hostN."/index.php?go=photosA&tag=".urlencode($this->arr[0])."\" title=\"".$albumText.$this->arr[1]."\" style=\"font-size:".$this->arr[2]."em;\">".$this->arr[0]."</a> ";
            } else {
               $this->lstTags.="<a href=\"".$hostN."/index.php?go=photosA&tag=".urlencode($this->arr[0])."\" title=\"".$albumText.$this->arr[1]."\" style=\"font-size:".$this->arr[2]."em;\">".$this->arr[0]."</a> ";
            }
         }
      }
      return $this->lstTags."</div>";
   }

}
?>
