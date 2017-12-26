<?php

include("inc/config.inc.php");




//----------------------------------------------------------------------------------------

function delDoc($docToDel)
{
   global $docRoot;
   if(is_file($docRoot."/ImgForArticles/".$docToDel)) unlink($docRoot."/ImgForArticles/".$docToDel);
   if(is_file($docRoot."/ImgForArticles/s_".$docToDel)) unlink($docRoot."/ImgForArticles/s_".$docToDel);
   if(is_file($docRoot."/ImgForArticles/m_".$docToDel)) unlink($docRoot."/ImgForArticles/m_".$docToDel);
}

//----------------------------------------------------------------------------------------

function Recopy_doc_toserver($nWidthS, $nWidthM, $docarchiveT)
{

   if(isset($_FILES['docarchive']) && $_FILES['docarchive']['error'] != 4) {

      if($_FILES['docarchive']['error']) return false;

      if(!is_uploaded_file($_FILES['docarchive']['tmp_name'])) return false;

      global $docRoot;

      if(!is_dir($docRoot."/ImgForArticles")) mkdir($docRoot."/ImgForArticles", 0755);

      $size = getimagesize($_FILES['docarchive']['tmp_name']);
      switch ($size[2]){
         case 1:
               $rst="gif";
            break;
         case 2:
               $rst="jpeg";
            break;
         case 3:
               $rst="png";
            break;
       }

      if(!$docarchiveT){
         $docarchiveT = encodestring(strtolower(str_replace(" ", "", basename($_FILES['docarchive']['name']))));
         $docarchiveT = date("YmdHis").$docarchiveT;
      } else { delDoc($docarchiveT); }

      if(!img_resize($_FILES['docarchive']['tmp_name'], $docRoot."/ImgForArticles/s_".$docarchiveT,$size[0],$size[1], $nWidthS, $rst)) return false;

      if(!img_resize($_FILES['docarchive']['tmp_name'], $docRoot."/ImgForArticles/m_".$docarchiveT,$size[0],$size[1], $nWidthM, $rst)) return false;

      move_uploaded_file($_FILES['docarchive']['tmp_name'], $docRoot."/ImgForArticles/".$docarchiveT); return $docarchiveT;

   } else { return false; }

}

function copy_doc_toserver($nWidthS, $nWidthM)
{

   if(isset($_FILES['docarchive']) && $_FILES['docarchive']['error'] != 4) {

      if($_FILES['docarchive']['error']) return false;

      if(!is_uploaded_file($_FILES['docarchive']['tmp_name'])) return false;

      global $docRoot;

      if(!is_dir($docRoot."/ImgForArticles")) mkdir($docRoot."/ImgForArticles", 0755);

      $doc = encodestring(strtolower(str_replace(" ", "", basename($_FILES['docarchive']['name']))));
      $doc = date("YmdHis").$doc;

      $size = getimagesize($_FILES['docarchive']['tmp_name']);
      switch ($size[2]){
         case 1:
               $rst="gif";
            break;
         case 2:
               $rst="jpeg";
            break;
         case 3:
               $rst="png";
            break;
       }

      if(!img_resize($_FILES['docarchive']['tmp_name'], $docRoot."/ImgForArticles/s_".$doc,$size[0],$size[1], $nWidthS, $rst)) return false;

      if(!img_resize($_FILES['docarchive']['tmp_name'], $docRoot."/ImgForArticles/m_".$doc,$size[0],$size[1], $nWidthM, $rst)) return false;

      move_uploaded_file($_FILES['docarchive']['tmp_name'], $docRoot."/ImgForArticles/".$doc);  return $doc;

   } else { return false; }

}


function img_resize($filename, $dest, $width, $height, $newwidth, $rst, $rgb=0xFFFFFF, $quality=100)
{
  if($newwidth<30) $newwidth = 30;

  $newheight = round(($newwidth/$width)*$height);

  $icfunc = "imagecreatefrom".$rst;
  if (!function_exists($icfunc)) return false;

  $isrc = $icfunc($filename);
  $idest = imagecreatetruecolor($newwidth, $newheight);//$width, $height);

  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

  $icfun = "image".$rst;
  if (!function_exists($icfun)) return false;

  $icfun($idest, $dest, $quality);

  imagedestroy($isrc);
  imagedestroy($idest);

  return true;
}


//----------------------------------------------------------------------------------------

function encodestring($st)
{
   $st=strtr($st,"абвгдеёзийклмнопрстуфхъыэ_","abvgdeeziyklmnoprstufh'iei");

   $st=strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_","abvgdeeziyklmnoprstufh'iei");

   $st=strtr($st, array("ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya", "Ж"=>"zh", "Ц"=>"ts", "Ч"=>"ch", "Ш"=>"sh", "Щ"=>"shch","Ь"=>"", "Ю"=>"yu", "Я"=>"ya", "ї"=>"i", "Ї"=>"yi", "є"=>"ie", "Є"=>"ye"));

   return $st;
}


//---------------------------------------------------------------------------------------


function numlink($start,$oper,$bd,$sortB='')
{
   $lmt=50;
   if(!$sortB){
      list($cnt)=mysql_fetch_row(mysql_query("SELECT count(id) FROM $bd"));
   } else {
      list($cnt)=mysql_fetch_row(mysql_query("SELECT count(id) FROM $bd WHERE department=".$sortB));
   }

   if($cnt>=1){
      if(is_integer($cnt/$lmt)){
         $cr=$cnt/$lmt;       
      } else {
         $cr=round(($cnt/$lmt)+(0.5));
      }
      $nv=($start-1)/$lmt;
      if((round(($nv/10)+(0.5)))*10<$cr){          
         $kn=(round(($nv/10)+(0.5)))*10;
      } else {
         $kn=$cr;          
      }

      $rd=round(($nv/10)-0.5);
      if($rd<0) $rd=0;

      $nv=($rd*10)+1;
      $rt="
      <table width=100% border=0 cellpadding=1 cellspacing=0><tr><td>
      <style type=\"text/css\"><!--
         span.cur {border:1px solid #666666; background-color:#eeeeee;font-size:9pt;font-weight:bold;}
         span.oth {border:1px solid #dddddd;}
      //--></style>";
   
      if($start<>1) $rt.="<a class=im href=\"?$oper&start=".($start-$lmt)."\">&lt;&lt;предыдущие $lmt</a> ";
      
      for($i=$nv;$i<=$kn;$i++){
         if($start==1 && $i==1){
            $rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         }elseif((($i-1)*$lmt)+1==$start){
            $rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         } else {
            $rt.=" <span class=oth>&nbsp;<a class=im href=\"?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;</span> ";
         }
      }
      if((($cr-1)*$lmt)+1!=$start) $rt.=" <a class=im href=\"?$oper&start=".($start+$lmt)."\">следующие $lmt>></a>";

      $rt.="</td><td align=right>$start..";

      if(($cnt-$start)>=($lmt-1)){ 
         $rt.=$start+$lmt-1;
      }else{
         $rt.=$cnt;        
      }
      $rt.=" из ".($cnt)."</td></tr></table>";

      return $rt;
   } else {
      return false;
}
}



//---------------------------------------------------------------------------------------

function rsscreate($rss,$countinmain){
   global $hostName,$docRoot;

//       <link>".$hostName."/index.php?go=articles&orderBy=".$arr[5]."&anons=".$arr[0]."</link>\n


$res=mysql_query("SELECT id, name,  description, file, DATE_FORMAT(data,'%W, %e %b %Y %T'), department FROM iws_art_records  ORDER BY data DESC LIMIT ".$countinmain);
$Grinvich = date("O");
 


$strw="";
$str="<?xml version='1.0' encoding='windows-1251'?>
<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom' xmlns:media='http://search.yahoo.com/mrss/'>
<channel>
   <title>Новости БГМУ</title>
   <link>".$hostName."</link>
   <description>Новости Белорусского Государственного Медицинского Университета</description>
   <language>ru</language>
   <image>
      <url></url>
      <title>БГМУ</title>
      <link>".$hostName."</link>
   </image>
   <lastBuildDate>".date(r)."</lastBuildDate>";


   while($arr=mysql_fetch_row($res)){
    $strw.="
      <item>
         <title>".stripslashes($arr[1])."</title>
         <link>".$hostName."/allarticles/rubric".$arr[5]."/article".$arr[0]."/</link>
         <description>&#x3C;img src=\"".$hostName."/ImgForArticles/m_".$arr[3]."\"  border=\"0\" align=\"left\" hspace=\"5\" /&#x3E; ".stripslashes($arr[2])."</description>
         <enclosure url=\"".$hostName."/ImgForArticles/".$arr[3]."\"  />
         <pubDate>".$arr[4]." ".$Grinvich."</pubDate>
      </item>";
  }

$str.="
   <pubDate>".date(r)."</pubDate>
   <ttl>60</ttl>".$strw."
</channel></rss>";

if(!is_dir($docRoot."/rss")) mkdir($docRoot."/rss", 0755);     
$file=$docRoot."/rss/rss.xml";      
$fp = fopen($file, 'w');
fwrite($fp, $str);
fclose($fp); 



//--------------------------------------------------------------------


return true;
}


?>
