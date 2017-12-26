<?php

function numlink_doc($start,$oper,$bd,$sortB='',$lmt,$lang,$hostN)
{
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
      $rt="";

      $oper = preg_replace("/&err=(\d{1,})/","",$oper);  

      if($start<>1) $rt.="<a class=cm href=\"$hostN/index.php?$oper&start=".($start-$lmt)."\">".$lang['artprev']."</a> ";
      
      for($i=$nv;$i<=$kn;$i++){
         if($start==1 && $i==1){
            $rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         }elseif((($i-1)*$lmt)+1==$start){
            $rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
         } else {
            $rt.=" <span class=oth>&nbsp;<a class=cm href=\"$hostN/index.php?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;</span> ";
         }
      }
      if((($cr-1)*$lmt)+1!=$start) $rt.=" <a class=cm href=\"$hostN/index.php?$oper&start=".($start+$lmt)."\">".$lang['artnext']."</a>";

      $rt.="";
/*
      $rt.="</td><td align=right>$start..";

      if(($cnt-$start)>=($lmt-1)){ 
         $rt.=$start+$lmt-1;
      }else{
         $rt.=$cnt;        
      }
      $rt.=" из ".($cnt)."</td></tr></table>";
*/
      return $rt;
   } else {
      return false;
}
}


?>