<?php


unset($mainadvar);
session_start();
session_register("mainadvar");


if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
   header("Location: unautor.html");
   return;
}


include('inc/main.inc.php');
include('inc/templates.inc.php');

$rst=mysql_query("SELECT path FROM iws_menu WHERE urlmenu='?go=".$go."' LIMIT 1");
if(mysql_numrows($rst)>=1){
   list($pth)=mysql_fetch_row($rst);
   switch ($go){
      case "page":                    
         include('fnciws/pagesimple/simplepage.php');
         frmIWS($cont);
         break;
      case "mpage":                    
         include('fnciws/pagemulti/multipage.php');
         frmIWS($cont);
         break;
      default:
         include($pth);
         frmIWS($cont);
         break;
   }
//   unlink($rst,$pth);
} else {
   switch ($go){
      case "page":                    
         include('fnciws/pagesimple/simplepage.php');
         frmIWS($cont);
         break;
      case "mpage":                    
         include('fnciws/pagemulti/multipage.php');
         frmIWS($cont);
         break;
      case "quit":
         $mainadvar['ath']="unavtores";
         mysql_close($dblink);
         header("Location: unautor.html"); // выход из админовских модулей;
         break;
      default:
         include('fnciws/default/deflt.php');
         frmIWS($cont);
         break;
   }
}
?>