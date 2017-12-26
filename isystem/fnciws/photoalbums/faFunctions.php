<?php

include("inc/config.inc.php");
//----------------------------------------------------------------------------------------


function delFile($fileToDel)
{
   global $docRoot;
   if(is_file($docRoot."/PhotoAlbums/".$fileToDel)) unlink($docRoot."/PhotoAlbums/".$fileToDel);
   if(is_file($docRoot."/PhotoAlbums/s_".$fileToDel)) unlink($docRoot."/PhotoAlbums/s_".$fileToDel);
}

//----------------------------------------------------------------------------------------

function img_resize($filename, $cropW, $cropH, $imgNW, $imgNH, $cropY, $cropX)
{
   global $docRoot;

      $size = getimagesize($docRoot."/PhotoAlbums/".$filename);
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

      if($cropX<0) $cropX = 0;
      if($cropY<0) $cropY = 0;
      if($imgNW<=$cropW){ $imgNW = $cropW; $imgNH = round(($imgNW/$size[0])*$size[1]); }
      if($imgNH<=$cropH){ $imgNH = $cropH; $imgNW = round(($imgNH/$size[1])*$size[0]); }


  $icfunc = "imagecreatefrom".$rst;
  if (!function_exists($icfunc)) return false;

  $isrc = $icfunc($docRoot."/PhotoAlbums/".$filename);
  
  $idest = imagecreatetruecolor($imgNW, $imgNH);
  imagefill($idest, 0, 0, 0xFFFFFF);
  imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $imgNW, $imgNH, $size[0], $size[1]);

  $iidest = imagecreatetruecolor($cropW, $cropH);
  imagefill($iidest, 0, 0, 0xFFFFFF);
  imagecopy($iidest, $idest, 0, 0, $cropX, $cropY, $cropW, $cropH);


  $icfun = "image".$rst;
  if (!function_exists($icfun)) return false;

  $icfun($iidest, $docRoot."/PhotoAlbums/s_".$filename, 100);

  imagedestroy($isrc);
  imagedestroy($idest);
  imagedestroy($iidest);

  return true;
}

//----------------------------------------------------------------------------------------

function copy_file_toserver()
{
   if(isset($_FILES['fileImage']) && $_FILES['fileImage']['error'] != 4) {

      if($_FILES['fileImage']['error']) return false;

      if(!is_uploaded_file($_FILES['fileImage']['tmp_name'])) return false;

      if(filesize($_FILES['fileImage']['tmp_name'])>7340032) return false;

      $size = getimagesize($_FILES['fileImage']['tmp_name']);

      global $docRoot;

      if(!is_dir($docRoot."/PhotoAlbums")) mkdir($docRoot."/PhotoAlbums", 0755);

      $file = encodestring(strtolower(str_replace(" ", "", basename($_FILES['fileImage']['name']))));
      $file = date("YmdHis").$file;

      move_uploaded_file($_FILES['fileImage']['tmp_name'], $docRoot."/PhotoAlbums/".$file);  return Array($file,$size[0],$size[1]);

   } else { return false; }

}


//----------------------------------------------------------------------------------------

function encodestring($st)
{
   $st=strtr($st,"àáâãäå¸çèéêëìíîïğñòóôõúûı_","abvgdeeziyklmnoprstufh'iei");

   $st=strtr($st,"ÀÁÂÃÄÅ¨ÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÚÛİ_","abvgdeeziyklmnoprstufh'iei");

   $st=strtr($st, array("æ"=>"zh", "ö"=>"ts", "÷"=>"ch", "ø"=>"sh", "ù"=>"shch","ü"=>"", "ş"=>"yu", "ÿ"=>"ya", "Æ"=>"zh", "Ö"=>"ts", "×"=>"ch", "Ø"=>"sh", "Ù"=>"shch","Ü"=>"", "Ş"=>"yu", "ß"=>"ya", "¿"=>"i", "¯"=>"yi", "º"=>"ie", "ª"=>"ye"));

   return $st;
}


//---------------------------------------------------------------------------------------


function numlink($start,$oper,$bd,$sortB=0)
{
   $lmt=50;
   list($cnt)=mysql_fetch_row(mysql_query("SELECT count(id) FROM $bd WHERE cid=".$sortB));

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
   
      if($start<>1) $rt.="<a class=im href=\"?$oper&start=".($start-$lmt)."\">&lt;&lt;ïğåäûäóùèå $lmt</a> ";
      
      for($i=$nv;$i<=$kn;$i++){
         if($start==1 && $i==1){
            $rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         }elseif((($i-1)*$lmt)+1==$start){
            $rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         } else {
            $rt.=" <span class=oth>&nbsp;<a class=im href=\"?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;</span> ";
         }
      }
      if((($cr-1)*$lmt)+1!=$start) $rt.=" <a class=im href=\"?$oper&start=".($start+$lmt)."\">ñëåäóşùèå $lmt>></a>";

      $rt.="</td><td align=right>$start..";

      if(($cnt-$start)>=($lmt-1)){ 
         $rt.=$start+$lmt-1;
      }else{
         $rt.=$cnt;        
      }
      $rt.=" èç ".($cnt)."</td></tr></table>";

      return $rt;
   } else {
      return false;
}
}

?>
