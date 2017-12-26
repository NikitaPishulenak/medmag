<?php

function replaceURLfile($id,$path)
{
   $id=substr($id,0,32);
   $result=mysql_query("SELECT id,file FROM iws_arfiles_A_records WHERE pse='".$id."'");
   if(mysql_numrows($result)>=1){
      list($idFile,$fileTo)=mysql_fetch_row($result);
      if(is_file($path."/FilesForDownload_A/".$fileTo)){
         $arrRet["filesize"]=filesize($path."/FilesForDownload_A/".$fileTo);
         $arrRet["filetime"]=date("D, d M Y H:i:s T", filemtime($path."/FilesForDownload_A/".$fileTo));
         $arrRet["filepath"]=$path."/FilesForDownload_A/".$fileTo;
         $arrRet["filename"]=$fileTo;

               $range = 0;
               $handle = @fopen($arrRet["filepath"], "rb");
               if (!$handle){
                  return false;
                  exit;
               }
 
               if ($_SERVER["HTTP_RANGE"]) {
                  $range = $_SERVER["HTTP_RANGE"];
                  $range = str_replace("bytes=", "", $range);
                  $range = str_replace("-", "", $range);
                  if ($range) fseek($handle, $range);
               }
 
               if ($range) {
                  header("HTTP/1.1 206 Partial Content");
               } else {
                  header("HTTP/1.1 200 OK");
               }
               header("Content-Disposition: attachment; filename=\"".$arrRet["filename"]."\"");
               header("Last-Modified: ".$arrRet["filetime"]);
               header("Content-Length: ".($arrRet["filesize"]-$range));
               header("Accept-Ranges: bytes");
               header("Content-Range: bytes $range-".($arrRet["filesize"] - 1)."/".$arrRet["filesize"]);
 
               if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
                  Header("Content-Type: application/force-download");
               } else {
                  Header("Content-Type: application/octet-stream");
               }

               while(!feof($handle)) {
                  $buf = fread($handle,512);
                  print($buf);
               }
 
               fclose($handle);

      $useragent = $_SERVER['HTTP_USER_AGENT'];
      if ( preg_match("/Mozilla|Opera/i", $useragent) && !preg_match("!Bot/|robot|Slurp/|yahoo!i", $useragent))
      {
               $hostT = parse_url(getenv("HTTP_REFERER"));
               $tempar=statcoock($_SERVER["REMOTE_ADDR"]);
               mysql_query("INSERT INTO iws_statistics (dt,frm,ip_adr,url,menu,lng,coockie) VALUES ('".date('Y-m-d H:i:s', time())."','".$hostT['host']."','".$tempar[1]."','files_A',$idFile,'ru','".$tempar[0]."')");
      }
         unset($arrRet,$result,$fileTo,$handle,$buf,$range,$hostT);
         return true;

      } else {
         return false;
      }
   } else {
      return false;
   }
}
     function statcoock($ip_host){
     if ($_COOKIE['CI']){
         return unserialize(base64_decode($_COOKIE['CI']));
     } else {
         $CI=Array(unicumIdCoocke(),$ip_host);
         $arr=mysql_fetch_row(mysql_query("SELECT ip,enabled FROM `iws_stat_inner_ip` WHERE id=1"));
         if ($arr[1]==1){
            if(substr_count($arr[0],$ip_host)>=1){ 
               setcookie('CI',base64_encode(serialize($CI)));
            } else {
               setcookie('CI',base64_encode(serialize($CI)),mktime(0,0,0,date(m)+1,1,date(Y)));
            }
         } else {
            setcookie('CI',base64_encode(serialize($CI)),mktime(0,0,0,date(m)+1,1,date(Y)));
         }
         return $CI;
      }
      }

   function unicumIdCoocke(){  return md5(uniqid(rand(),1));  }

?>