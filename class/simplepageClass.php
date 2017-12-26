<?php

Class FNSimple {
   var $retcon;

   function replCS($mn,$bl){
   global $uservar,$language;
   if(isset($bl) && isset($mn) && $bl>2){
   list($act,$grp)=mysql_fetch_row(mysql_query("select activ,usrgrp from iws_blockmenu where bid=".$bl." and lng='".$language['lng']."'"));
   if(($grp==0 && $act) || (isset($uservar) && $uservar['tr']=="ars" && $uservar['grp']==$grp && $act)){ 
      $result=mysql_query("select A.content from iws_page_simple A, iws_menu B where A.mid=".$mn." and B.idm=A.mid and B.blk=".$bl);
      if(mysql_numrows($result)>=1){
         list($this->retcon)=mysql_fetch_row($result);
         $this->retcon = stripslashes($this->retcon);
         return true;
      }else{
//         header("location: http://www.bsmu.by/page/3%D1%88/");
         return false;
      }
   }else{
//         header("location: http://www.bsmu.by/page/3%D1%88/");
      return false;
   }
   }else{
//         header("location: http://www.bsmu.by/page/3%D1%88/");
      return false;
   }
   }

}
?>