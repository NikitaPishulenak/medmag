<?php

unset($language);
session_start();
session_register("language");

$language['lng']="ru";

/*
if(isset($lng) && ($lng=="ru" || $lng=="en")) 
{
   $language['lng']=$lng;
   header("location: /index.php");
}elseif(!isset($language) || ($language['lng']!="ru" && $language['lng']!="en")){
   $language['lng']="ru";
}
*/

include("class/mainClass.php"); 

$mainp = new Func;

if(isset($block) && $block && $block>2)
{
   $mainp->get_design_inBlock($block);
   if(!$mainp->design_page) $mainp->get_design();
} else {
   $mainp->get_design();
}

if($mainp->design_page){

   switch($go){
     case "main":
         include("class/mainpageClass.php"); 
         $mnp = new FNMainpage;
         if(!$mnp->replCM()) header("location: /error.php"); 
         $mainp->content=$mnp->retcon;
      break;
     case "articles":
         include("class/artClass.php"); 
         $filesContent = new FNArt;

         if(isset($act) && $act=="search")
         {

            if(!$filesContent->srcArt($words)) header("location: /error.php");

         } else if(isset($act) && $act=="AllArt"){

            if(!isset($start)) $start = 1;

            if(!$filesContent->replaceContentAll($start)) header("location: /error.php");

         } else {
            if(!isset($start)) $start = 1;

            if(isset($orderBy) && $orderBy){
               if(isset($anons) && $anons){            
                  if(!$filesContent->replaceContentAnons($start,$orderBy,$anons)) header("location: /error.php");         
               } else {
                  if(!$filesContent->replaceContent($start,$orderBy)) header("location: /error.php");
               }
            }
         }
         $mainp->content=$filesContent->retcon;
     break;


     case "photosA":
         include("class/photoClass.php"); 
         $AlbumsContent = new FNAlbums;

         if(isset($rubric) && $rubric)
         {
            if(!isset($start)) $start = 1;
            if(!$AlbumsContent->AlbumRubricTag($rubric,$start,0)) header("location: /error.php");
         }
         else if(isset($tag) && $tag)
         {
            if(!isset($start)) $start = 1;
            if(!$AlbumsContent->AlbumRubricTag($tag,$start,1)) header("location: /error.php");

         } else {
            if(!$AlbumsContent->AlbumAll()) header("location: /error.php");
         }
         $mainp->content=$AlbumsContent->retcon;
     break;


     case "filesarchive_A":
         include("class/A_filesClass.php"); 
         $filesContent = new FNFiles;


         if(isset($act) && $act=="addFileOk")
         {
            $filesContent->addNewFile($Category,$FileName,$FileContent,$FileAuthor,$orderBy);
         }
         else if(isset($act) && $act=="search")
         {

            if(!$filesContent->srcFile($words)) header("location: /error.php");

         }
         else if(isset($Rubric) && is_numeric($Rubric))
         {
            if(!isset($start)) $start = 1;
            if(!isset($err)) $err = 0;
            if(!$filesContent->replaceContentRubric($start,$Rubric,$err)) header("location: /error.php");
         }
         else if(isset($orderBy) && is_numeric($orderBy)){
            if(isset($id) && is_numeric($id)){
               if(!$filesContent->replaceContentId($orderBy,$id)) header("location: /error.php");
            } else {
               if(!isset($err)) $err = 0;
               if(!$filesContent->replaceContent($orderBy,$err)) header("location: /error.php");
            }
         }
         $mainp->content=$filesContent->retcon;
     break;

     case "GetFile_A":
         include("class/A_filesGetFile.php"); 
         if(isset($uid) && $uid){
            if(!replaceURLfile($uid,$docRoot)){
               include("errorFile.php");
               exit;
            }
            exit;
         } else {
            include("errorFile.php");
            exit;
         }  
     break;

     case "search":
         include("class/searchClass.php"); 
         $srch=new FNsearch;
         
         if(isset($act) && $act=="nw")
         {
            if(!$srch->search_news($words)){ header("location: /error.php");
 } else { $mainp->content=$srch->retcon; }
         }
         else if(isset($act) && $act=="fl")
         {
            if(!$srch->search_files($words)){ header("location: /error.php");
 } else { $mainp->content=$srch->retcon; }
         } else { 
            if(!$srch->src($words)){ header("location: /error.php");
 } else { $mainp->content=$srch->retcon; }
         }
      break;      
     case "map":
         include("class/mapClass.php"); 
         $mapview = new FNmap;
         if(!$mapview->replmap()){
           header("location: /error.php");
         } else {
           $mainp->content=$mapview->retcon;
         }
     break;      
     case "opros":
         if($act=="voice"){
            if(!$mainp->voiceAd($id,$rd))  header("location: /error.php");
         }elseif($act=="viewresult"){
            if(!$mainp->viewopros($id))  header("location: /error.php");
         }
      break;
     case "news":
         include("class/newsClass.php"); 
         $nwn = new FNnw;
            if($act=="view"){
                  if(!$nwn->viewnw($id,$start,$archiv)){
                      header("location: /error.php");
                  }else {
                    $mainp->content=$nwn->retcon;
                  }
            } else {
                  if(!$nwn->nws($archiv)){
                      header("location: /error.php");
                  }else {
                    $mainp->content=$nwn->retcon;
                  }
            }
      break;      
     case "qa":
         include("class/gbClass.php"); 
         $category = (isset($category) && $category>=1) ? $category : 0 ;
         $gbn = new FNgb;
         if($category>=1)
         {
            if(!$gbn->checkCategory($category)) header("location: /error.php?lng=".$language['lng']);
         }
         switch($act){
               case "addOk":
                  if(isset($id) && $id>=1)
                  {
                     if(!$gbn->checkPosition($id)) header("location: /error.php?lng=".$language['lng']);
                  }
                  $gbn->addOk($start,$id,$nme,$hmp,$email,$icq,$city,$cntr,$cont,$category);
               break;
               case "addmess":
                  if(isset($id) && $id>=1)
                  {
                     if(!$gbn->checkPosition($id)) header("location: /error.php?lng=".$language['lng']);
                  }
                  $gbn->addmess($start,$id,$err,$nme,$hmp,$email,$icq,$city,$cntr,$cont,$category);
                  $mainp->content=$gbn->wSpace($category,1);
               break;
               case "rules":
                  $gbn->rls($start,$id,$category);
                  $mainp->content=$gbn->wSpace($category);
               break;
            case "single":
                  $gbn->gstsingl($questionid);
                  $mainp->content=$gbn->wSpace($category);
               break;
               default:
                  $gbn->gstu($category);
                  $mainp->content=$gbn->wSpace($category);
               break;
         }
      break;      
     case "page":
         include("class/simplepageClass.php"); 
         $spp = new FNSimple;
         if(!$spp->replCS($menu,$block)) header("location: /error.php");
         $mainp->content=$spp->retcon;
      break;      
     case "mpage":  
         include("class/multipageClass.php"); 
         $mlp = new FNMultipage;
         if(isset($act) && $act){
            if(!$mlp->replCO($menu,$block,$act)) header("location: /error.php");
         } else {
            if(!$mlp->replCL($menu,$block)) header("location: /error.php");
         }
           $mainp->content=$mlp->retcon;
      break;      
     case "logn":
         if(!$mainp->logon($lgn,$pwd)) $mainp->retEr();
      break;      
      default:
         include("class/mainpageClass.php"); 
         $mnp = new FNMainpage;
         if(!$mnp->replCM()) header("location: /error.php");
         $mainp->content=$mnp->retcon;
      break;
   }
      
   $mainp->replText($menu,$block);

}
//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
//<META NAME="viewport" CONTENT="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
?>
<!DOCTYPE html>
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo $title; ?></TITLE>
<META NAME="DESCRIPTION" CONTENT="<?php echo $descript; ?>">
<META NAME="KEYWORDS" CONTENT="<?php echo $keyword; ?>">
<META NAME="ROBOTS" CONTENT="<?php echo $robots; ?>">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; CHARSET=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=9">
<link href='https://fonts.googleapis.com/css?family=Exo+2:300&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700&subset=cyrillic-ext" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/style_main_<?php echo $language['lng']; ?>.css">
<link REL="stylesheet" TYPE="text/css" HREF="/style_<?php echo $language['lng']; ?>.css">
<script type="text/javascript" src="/scripts/jquery.min.js"></script> 

<!--[if lt IE 9]>

<link rel="stylesheet" type="text/css" href="/style_IE.css" media="all"></link>

<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</HEAD>
<BODY>

<?php

include("class/rewriteURL.php"); 

echo $mainp->design_page;

?>

</BODY>
</HTML>