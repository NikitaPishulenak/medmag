<?php

include("languages/lang_".$language['lng'].".php");


Class FNgb {
   var $retcon;
   var $retalert;
   var $dateminus = "datu";
// var $dateminus = "DATE_SUB(datu, INTERVAL 1 HOUR)"; // отнимает 1 час от времени, т.к. сервер расположен в Москве

   function reRussian($argD){
      $massMonth = array(
         'January'  => 'January',
         'February' => 'February',
         'March'    => 'March',
         'April'    => 'April',
         'May'      => 'May',
         'June'     => 'June',
         'July'     => 'July',
         'August'   => 'August',
         'September'=> 'September',
         'October'  => 'October',
         'November' => 'November',
         'December' => 'December'
      );
      $massDay = array(
         'Monday'    => 'Monday',
         'Tuesday'   => 'Tuesday',
         'Wednesday' => 'Wednesday',
         'Thursday'  => 'Thursday',
         'Friday'    => 'Friday',
         'Saturday'  => 'Saturday',
         'Sunday'    => 'Sunday'
      );

      while(list($key,$val) = each($massMonth)) $argD=ereg_replace($key,$val,$argD);
      while(list($key,$val) = each($massDay)) $argD=ereg_replace($key,$val,$argD);

      return $argD;
   }

   function checkCategory($category)
   {
      $this->resCheck = mysql_query("SELECT id FROM iws_guestbk_category WHERE id=$category AND activ=1");
      if(mysql_numrows($this->resCheck)>=1){
         mysql_free_result($this->resCheck);
         return true;
      } else {
         return false;
      }
   }

   function checkPosition($id)
   {
      list($cmn)=mysql_fetch_row(mysql_query("select coment from iws_guestpref"));
      if($cmn){
         $this->resCheck = mysql_query("SELECT id FROM iws_guestbk WHERE id=$id");
         if(mysql_numrows($this->resCheck)>=1){
            mysql_free_result($this->resCheck);
            return true;
         } else {
            return false;
         }
      } else {
         return false;
      }
      unset($cmn);
   }


//-------------------------------------------------------------------------------------------------------------------

   function wSpace($category,$admess=0)
   {
   global $hostName,$lang,$language;

      $this->RETMAIN = "";
      $this->tableCategory ="";
      $this->nameCategory = $lang['gbnameMain'];
      $this->resCategory = mysql_query("select id,name from iws_guestbk_category where activ=1");
      if(mysql_numrows($this->resCategory)>=1){
         $this->retCategory = "";
         while($this->arrCategory = mysql_fetch_row($this->resCategory))
         {
            if($this->arrCategory[0] == $category) $this->nameCategory = $this->arrCategory[1];
            $this->retCategory.= "<a href=\"".$hostName."/index.php?go=qa&category=".$this->arrCategory[0]."\">".stripslashes($this->arrCategory[1])."</a><br>";
         }

         if($category>=1) $this->retCategory = "<a href=\"".$hostName."/index.php?go=qa\">".$lang['gbnameMain']."</a><br>".$this->retCategory;
         $this->tableCategory = $this->retCategory;         
      }
      mysql_free_result($this->resCategory);

      list($this->TemplateMAIN)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=10"));

      $this->TemplateMAIN = stripslashes($this->TemplateMAIN);

      if(ereg("\[\/:gbpositions\]",$this->TemplateMAIN)){
         $this->TemplateMAIN = str_replace("[/:gbnamecategory]",$this->nameCategory,$this->TemplateMAIN);
         $this->TemplateMAIN = str_replace("[/:gblistcategory]",$this->tableCategory,$this->TemplateMAIN);
         if($admess){
         
         $this->RETMAIN = str_replace("[/:formqa]",$this->retcon,$this->TemplateMAIN);   
            $this->RETMAIN = str_replace("[/:gbpositions]",$this->retalert,$this->RETMAIN);     
         } else {
            $this->RETMAIN = str_replace("[/:gbpositions]",$this->retcon,$this->TemplateMAIN);     
         }
         unset($this->TemplateMAIN);
      } else {
         $this->RETMAIN="<table width=100% border=0 cellpadding=0 cellspacing=0>
                     <tr valign=top><td>".$lang['gb']." (".$this->nameCategory.")</td><td width=150 rowspan=2>".$this->tableCategory."</td></tr>
                     <tr><td valign=top>".$this->retcon."</tr></td></table>";
      }
      return $this->RETMAIN;
   }



//-------------------------------------------------------------------------------------------------------------------

   function rls($start,$id,$category=0){
   global $hostName,$language,$lang;
      list($rul)=mysql_fetch_row(mysql_query("select rules".$language['lng']." from iws_guestpref"));

      $this->retcon="<table width=100% border=0 cellpadding=10 cellspacing=0>";
      $this->retcon.="<tr><td>".stripslashes($rul)."</td></tr>"
                  ."<tr><td><input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/ret_".$language['lng'].".gif\" title=\"".$lang['gbret']."\" alt=\"".$lang['gbret']."\" onclick=\"document.location='".$hostName."/index.php?go=qa&category=$category&act=addmess&start=$start&id=$id'; return false;\"></td></tr>";
      $this->retcon.="</table>";
   }






//-------------------------------------------------------------------------------------------------------------------

   function addOk($start,$id,$nme,$hmp,$email,$icq,$city,$cntr,$cont,$category=0){
   global $hostName,$language;
   
      $nme=trim($nme);
      $cont=trim($cont);
      if (empty($cont) || empty($nme) || ($icq && !is_numeric($icq)))
         {
            header("location: ".$hostName."/qa/q$category/addmess/$start/?id=$id&err=1&nme=$nme&hmp=$hmp&email=$email&icq=$icq&city=$city&cntr=$cntr&cont=$cont");
//          header("location: ".$hostName."/err/1/?go=qa&act=addmess&start=$start&id=$id"
//                                     ."&nme=$nme&hmp=$hmp&email=$email&icq=$icq&city=$city&cntr=$cntr&cont=$cont");

            return;
         }
          list($capcha,$moder,$maxlen)=mysql_fetch_row(mysql_query("SELECT capcha,moder,maxlen FROM iws_guestpref WHERE id=1"));
          if ($capcha){
            if (empty($_POST['capcha']) || empty($_SESSION['secpic']) || ($_POST['capcha']!=$_SESSION['secpic'])){
            header("location: ".$hostName."/qa/q$category/addmess/$start/?id=$id&err=1&nme=$nme&hmp=$hmp&email=$email&icq=$icq&city=$city&cntr=$cntr&cont=$cont");
            return;
      }}
      $nme=addslashes(htmlspecialchars(substr($nme,0,50)));
//    if($hmp=="http://"){
//       $hmp="";
//    } else {
//       if(!ereg("http://",$hmp)) $hmp="http://".$hmp;
         $hmp=addslashes(htmlspecialchars(substr($hmp,0,150)));
//    }
      $email=htmlspecialchars(substr($email,0,150));
      if($icq){
         $icq=substr($icq,0,20);
      } else {
         $icq=0;
      }
      $city=addslashes(htmlspecialchars(substr($city,0,20)));
      $cntr=addslashes(htmlspecialchars(substr($cntr,0,20)));
      $cont=preg_replace("#(\r)\n#si", "<br>",addslashes(htmlspecialchars(substr($cont,0,$maxlen))));

      $this->sql="insert into ";
      if($id>0){
         $this->sql.="iws_guestcm (gid,";
      }else{
         $this->sql.="iws_guestbk (category,lng,";
      }
      $this->sql.="datu,name,email,hmp,icq,city,cntr,coment,nomod,ip) values (";
      if($id>0){
         $this->sql.="$id,";
      }else{
         $this->sql.="$category,'".$language['lng']."',";
      }
      $this->sql.="'".date('Y-m-d H:i:s', time())."','$nme','$email','$hmp',$icq,'$city','$cntr','$cont','$moder','$_SERVER[REMOTE_ADDR]')";

      if(!mysql_query($this->sql)){
         header("location: ".$hostName."/qa/q$category/addmess/$start/?id=$id&err=2&nme=$nme&hmp=$hmp&email=$email&icq=$icq&city=$city&cntr=$cntr&cont=$cont");
         return;
      } else {
            
//          $this->tomail = "Имя: ".$nme."\n\n"
//                         ."Текст сообщения:\n\n".$cont."\n";
//          mail("","Сообщение с ".$hostName,$this->tomail,"From: ".$hostName."\n"); 
//       header("location: ".$hostName."/index.php?go=qa&start=$start");

         if($moder){
            header("location: ".$hostName."/qa/q$category/$start/Ok/");return;
         }else{
            header("location: ".$hostName."/qa/q$category/$start/");return;
         }

      }
   }



//-------------------------------------------------------------------------------------------------------------------

   function addmess($start,$id=0,$err=0,$nme="",$hmp="",$email="",$icq="",$city="",$cntr="",$cont="",$category=0){
   global $hostName,$language,$lang;
//    if(!$hmp) $hmp="http://";
      list($cmn,$rul,$capcha)=mysql_fetch_row(mysql_query("select coment,rtr,capcha from iws_guestpref WHERE id=1"));
      if($id>0 && !$cmn) return false;


      if($id>0){
         $this->retalert=$lang['gbaddc']; 
      }else{
         $this->retalert=$lang['gbaddm'];                         
      }

      $this->retalert.=$lang['gbrul'];                         
      if($rul) $this->retalert.=" (<a href=\"".$hostName."/index.php?go=qa&category=$category&act=rules&start=$start&id=$id\">".$lang['gbrl']."</a>)";                           
      
      if($err==1){
         $this->retalert.=$lang['gberr1'];
      }elseif($err==2){
         $this->retalert.=$lang['gberr2'];
      }
     
     


         $this->retcon="<script><!-- ".($capcha ? '
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
                              frm.submit();
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
         frm.submit();
      } else {
         alert("'.$lang['gbnt'].'     ");
         frm.cont.focus();
      }
   } else {
      alert("'.$lang['gbnn'].'     ");
      frm.nme.focus();
   }
}

')."  //--></script>
                           <form action=\"".$hostName."/index.php\" name=frm method=post>
                           <input type=hidden name=go value=qa>
                           <input type=hidden name=category value=$category>
                           <input type=hidden name=start value=$start>
                           <input type=hidden name=act value=addOk>
                          <div id='qa_name'>".$lang['gbname']."<br><input class='Qa_ip' name=nme value=\"".stripslashes($nme)."\" value= maxlength=50 size=43></div>
                           <div id='qa_ci'>".$lang['gbhmp']."<br><input  class='Qa_ip' name=hmp value=\"$hmp\" maxlength=150 size=43></div>
                           <div id='qa_cont'>".$lang['gbtm']."<br><textarea class='Qa_tf'  name=cont >".stripslashes($cont)." </textarea></div>"
                           .($capcha ? '<div id="cap_text">'.$lang['capcha_mesage'].'</div><div id="cap_img"><img  src="'.$hostName.'/scripts/capcha.php" title="'.$lang['capcha'].'" alt="'.$lang['capcha'].'" /></div><div id="cap_fi"><input style="margin: 0px; padding: 0px;" name=capcha maxlength=4 size=6></div>' : '')."
                         <div class='niled_inner'>$nbsp</div> <div id='qa_btn'> <input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/add_".$language['lng'].".gif\" title=\"".$lang['gbadd']."\" alt=\"".$lang['gbadd']."\" onclick=\"sbmn(); return false;\"></div>
                          <div id='qa_can'> <input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/cancel_".$language['lng'].".gif\" title=\"".$lang['gbcanc']."\" alt=\"".$lang['gbcanc']."\" onclick=\"document.location='".$hostName."/index.php?go=qa&category=$category&start=$start'; return false;\"></div>

                     </form></div>";                
/*
      $this->retcon="<script><!--\n"
                           ."function sbmn(){\n"
                           ."if(frm.nme.value){\n"
                           ."if(frm.cont.value){\n"
                           ."frm.submit();\n"
                           ."}else{\n"
                           ."alert(\"".$lang['gbnt']."     \");\n"
                           ."frm.cont.focus();\n"
                           ."}\n"
                           ."}else{\n"
                           ."alert(\"".$lang['gbnn']."     \");\n"
                           ."frm.nme.focus();\n"
                           ."}\n"
                           ."}\n"
                           ."//--></script>\n";
      $this->retcon.="<table width=100% border=0 cellpadding=6 cellspacing=0>";
      $this->retcon.="<tr><td colspan=2>";                           
      if($id>0){
         $this->retcon.=$lang['gbaddc'];                          
      }else{
         $this->retcon.=$lang['gbaddm'];                          
      }
      $this->retcon.="</td></tr><tr><td colspan=2>".$lang['gbrul'];                          
      if($rul) $this->retcon.=" (<a href=\"".$hostName."/index.php?go=qa&category=$category&act=rules&start=$start&id=$id\">".$lang['gbrl']."</a>)";                          
      $this->retcon.="<br></td></tr>";

      if($err==1){
         $this->retcon.="<tr><td colspan=2>".$lang['gberr1']."</td></tr>";
      }elseif($err==2){
         $this->retcon.="<tr><td colspan=2>".$lang['gberr2']."</td></tr>";
      }
     
      $this->retcon.="<form action=\"".$hostName."/index.php\" name=frm method=post>"
                           ."<input type=hidden name=go value=qa>"
                           ."<input type=hidden name=category value=$category>"
                           ."<input type=hidden name=start value=$start>"
                           ."<input type=hidden name=id value=$id>"
                           ."<input type=hidden name=act value=addOk>"
                           ."<tr><td align=right>".$lang['gbname']."</td><td width=100%><input name=nme value=\"".stripslashes($nme)."\" maxlength=50 size=50></td></tr>"
//                         ."<tr><td align=right>".$lang['gbcity']."</td><td><input name=city value=\"".stripslashes($city)."\" maxlength=20 size=50></td></tr>"
//                         ."<tr><td align=right>".$lang['gbcntr']."</td><td><input name=cntr value=\"".stripslashes($cntr)."\" maxlength=20 size=50></td></tr>"
                           ."<tr><td align=right><nobr>".$lang['gbhmp']."</nobr></td><td><input name=hmp value=\"$hmp\" maxlength=150 size=50></td></tr>"
//                         ."<tr><td align=right>Эл. почта</td><td><input name=email value=\"$email\" maxlength=150 size=50></td></tr>"
//                         ."<tr><td align=right>ICQ</td><td><input name=icq value=\"$icq\" maxlength=20 size=50></td></tr>"
                           ."<tr><td align=right valign=top><nobr>";
      if($id>0){
         $this->retcon.=$lang['gbtc'];
      }else{
         $this->retcon.=$lang['gbtm'];
      }
      $this->retcon.="</nobr></td><td><textarea name=cont rows=10 cols=40>".stripslashes($cont)."</textarea></td></tr>"
                           ."<tr><td></td><td><input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/add_".$language['lng'].".gif\" title=\"".$lang['gbadd']."\" alt=\"".$lang['gbadd']."\" onclick=\"sbmn(); return false;\">"
                           ." <input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/cancel_".$language['lng'].".gif\" title=\"".$lang['gbcanc']."\" alt=\"".$lang['gbcanc']."\" onclick=\"document.location='".$hostName."/index.php?go=qa&category=$category&start=$start'; return false;\"></td></tr>";                           
      $this->retcon.="</table></form>";
*/
   }



//-------------------------------------------------------------------------------------------------------------------


   function gstu($category=0){
      global $QUERY_STRING,$hostName,$language,$lang,$start;

      $this->qwr=ereg_replace("&start=".$start,"",$QUERY_STRING);
      if(!$start) $start=1;
      list($lmt,$tna,$cmn,$rul,$moder)=mysql_fetch_row(mysql_query("select IF(limt>=1,limt,10),vivod,coment,rtr,moder from iws_guestpref"));

      if($_GET['allert'] && $moder){ $this->retcon.=$lang['nomod']; }

      $this->retcon.="<table width=100% border=0 cellpadding=10 cellspacing=0>";
//                   ."<tr><td><input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/addmsg_".$language['lng'].".gif\" title=\"".$lang['gbaddmes']."\" alt=\"".$lang['gbaddmes']."\" onclick=\"document.location='".$hostName."/index.php?go=qa&category=$category&act=addmess&start=$start'; return false;\"></td></tr>";                         
      $this->prom=$this->numlink($start,$this->qwr,$lmt,$tna,$category);
      if($this->prom!="none"){
      $this->retcon.="<tr><td align=center>".$this->prom."</td></tr>";
      $res=mysql_query("select id,DATE_FORMAT(".$this->dateminus.",'%W, %e %M %Y ".$lang['gbin']." %T'),name,city,cntr,coment,hmp,email,icq,nomod from "
                  ."iws_guestbk where category=$category AND lng='".$language['lng']."' order by datu DESC limit ".($start-1).",$lmt");

         if(mysql_numrows($res)>=1){
            list($this->gbTM)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=2"));
            while($arr=mysql_fetch_row($res)) if (!$arr[9]){  $this->retcon.="<tr><td>".$this->zapgb($arr,stripslashes($this->gbTM),$cmn,$start,$category)."</td></tr>";}
          }
         $this->retcon.="<tr><td align=center>".$this->prom."</td></tr>";
//       $this->retcon.="<tr><td><input class=btn type=\"image\" src=\"".$hostName."/design/guestbook/addmsg_".$language['lng'].".gif\" title=\"".$lang['gbaddmes']."\" alt=\"".$lang['gbaddmes']."\" onclick=\"document.location='".$hostName."/index.php?go=qa&category=$category&act=addmess&start=$start'; return false;\"></td></tr>";                          
         if($language['lng']=="ru") $this->retcon = $this->reRussian($this->retcon);
      } else {
         $this->retcon.="<tr><td align=center>".$lang['gbnomes']."</td></tr>";
      }
      $this->retcon.="</table>";
   }



//-------------------------------------------------------------------------------------------------------------------


   function zapgb($art,$tmpl,$tn,$start,$category,$single=0){
   global $hostName,$lang;
      $this->ret=$tmpl;
     if(ereg(":gbname",$this->ret)) $this->ret=str_replace("[/:gbname]", ($single ? $art[2] :"<a class='question_a' href='".$hostName."/index.php?go=qa&act=single&questionid=".$art[0]."'>".stripslashes($art[2])."</a>"), $this->ret);
         
/*    if($art[6]){
         $this->rt="<a target=_blank href=\"".$art[6]."\"><img src=\"".$hostName."/design/guestbook/hmp.gif\" border=0 title=\"homepage: ".$art[6]."\" alt=\"homepage: ".$art[6]."\"></a>";
      }else{
         $this->rt="";
      }
      if(ereg(":gbhmp",$this->ret)) $this->ret=str_replace("[/:gbhmp]",$this->rt,$this->ret); 
*/
      if($art[7]){
         $this->rt="<a href=\"mailto:".$art[7]."\"><img src=\"".$hostName."/design/guestbook/email.gif\" border=0 title=\"email: ".$art[7]."\" alt=\"email: ".$art[7]."\"></a>";
      }else{
         $this->rt="";
      }
      if(ereg(":gbemail",$this->ret)) $this->ret=str_replace("[/:gbemail]",$this->rt,$this->ret); 
      if($art[8]){
         $this->rt="<a target=_blank href=\"http://wwp.icq.com/".$art[8]."#pager\"><img src=\"".$hostName."/design/guestbook/icq.gif\" border=0 title=\"icq: ".$art[8]."\" alt=\"icq: ".$art[8]."\"></a>";
      }else{
         $this->rt="";
      }
      if(ereg(":gdicq",$this->ret)) $this->ret=str_replace("[/:gdicq]",$this->rt,$this->ret); 
      if(ereg(":gbhmp",$this->ret)) $this->ret=str_replace("[/:gbhmp]",stripslashes($art[6]),$this->ret);
      if(ereg(":gbcity",$this->ret)) $this->ret=str_replace("[/:gbcity]",stripslashes($art[3]),$this->ret); 
      if(ereg(":gbcntr",$this->ret)) $this->ret=str_replace("[/:gbcntr]",stripslashes($art[4]),$this->ret); 
      if(ereg(":gbmess",$this->ret)) $this->ret=str_replace("[/:gbmess]",stripslashes($art[5]),$this->ret); 
      if(ereg(":gbdate",$this->ret)) $this->ret=str_replace("[/:gbdate]",$art[1],$this->ret); 

      $res=mysql_query("select DATE_FORMAT(".$this->dateminus.",'%W, %e %M %Y ".$lang['gbin']." %T'),name,city,cntr,coment,hmp,email,icq from iws_guestcm where gid=".$art[0]." order by datu DESC");
      $this->cm="";
      if(mysql_numrows($res)>=1){
         $this->cm="<DIV>".$lang['gbcom'];
         while($arc=mysql_fetch_row($res)){
            $this->cm.="<DIV class=inTable>";
            $this->cm.="<DIV>".stripslashes($arc[1]); 
            if($arc[2]) $this->cm.=" ".stripslashes($arc[2]);
            if($arc[3]) $this->cm.=" ".stripslashes($arc[3]);
//          if($arc[5]) $this->cm.=" <a target=_blank href=\"".$arc[5]."\"><img src=\"".$hostName."/design/guestbook/hmp.gif\" border=0 title=\"homepage: ".$arc[5]."\" alt=\"homepage: ".$arc[5]."\"></a>";

            if($arc[6]) $this->cm.=" <a href=\"mailto:".$arc[6]."\"><img src=\"".$hostName."/design/guestbook/email.gif\" border=0 title=\"email: ".$arc[6]."\" alt=\"email: ".$arc[6]."\"></a>";

            if($arc[7]) $this->cm.=" <a target=_blank href=\"http://wwp.icq.com/".$arc[7]."#pager\"><img src=\"".$hostName."/design/guestbook/icq.gif\" border=0 title=\"icq: ".$arc[7]."\" alt=\"icq: ".$arc[7]."\"></a>";

            $this->cm.="<p>".stripslashes($arc[4])."</p><font class=datacolor>".$arc[0]."</font></DIV></DIV>";
         }
         $this->cm.="</DIV>";
      }
      if($tn) $this->cm.="&nbsp;&nbsp;<a class=cm href=\"".$hostName."/index.php?go=qa&category=$category&id=".$art[0]."&act=addmess&start=$start\">".$lang['gbaddcom']."</a>";

      if(ereg(":gbcomm",$this->ret)) $this->ret=str_replace("[/:gbcomm]",$this->cm,$this->ret); 
      return $this->ret;
   }



//-------------------------------------------------------------------------------------------------------------------
   function gstsingl($id)
   {
      global $hostName,$language,$lang;
     if(intval($id)){
      $this->retcon.="<table width=100% border=0 cellpadding=10 cellspacing=0>";
      $res=mysql_query("select id,DATE_FORMAT(".$this->dateminus.",'%W, %e %M %Y ".$lang['gbin']." %T'),name,city,cntr,coment,hmp,email,icq,nomod from "
                  ."iws_guestbk where id=".$id);
      if(mysql_numrows($res)>=1){
         $arr=mysql_fetch_row($res);
         list($this->gbTM)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=2"));
         $this->retcon.="<tr><td>".$this->zapgb($arr,stripslashes($this->gbTM),0,0,0,1)."</td></tr>";
         $this->retcon.="</table>";
      }else{
      header( "Location:".$hostName."/error.php");
      }
   }else{
      header( "Location:".$hostName."/error.php");
   }
    
   }
  
   

//-------------------------------------------------------------------------------------------------------------------

   function numlink($stt,$oper,$lmt,$viv,$category=0){
   global $language,$lang,$hostName;
   
   $this->qw="SELECT count(id) FROM iws_guestbk WHERE category=$category AND lng='".$language['lng']."'";

   list($cnt)=mysql_fetch_row(mysql_query($this->qw));
   if($cnt>=1){
      if(is_integer($cnt/$lmt)){
         $cr=$cnt/$lmt;       
      }else{
         $cr=round(($cnt/$lmt)+(0.5));
      }
      if(!$viv){
         $nv=($stt-1)/$lmt;
         if((round(($nv/10)+(0.5)))*10<$cr){          
            $kn=(round(($nv/10)+(0.5)))*10;
         } else {
            $kn=$cr;          
         }
         $rd=round(($nv/10)-0.5);
         if($rd<0){ $rd=0; }
         $nv=($rd*10)+1;
      } else {
         $nv=1;
         $kn=$cr;
      }
      
      $this->rt="";

      if($kn>=2){
         $this->rt.="<table width=100% border=0 cellpadding=1 cellspacing=0><tr><td";
         if($viv) $this->rt.=" align=center width=100%";
         $this->rt.=">"; 
         if($stt<>1 && !$viv){ $this->rt.="<a class=cm href=\"".$hostName."/index.php?$oper&start=".($stt-$lmt)."\">".$lang['gbprev']."</a> "; }
         for($i=$nv;$i<=$kn;$i++){
            if($stt==1 && $i==1){
               $this->rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
            }elseif((($i-1)*$lmt)+1==$stt){
               $this->rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
            } else {
               if($viv){
                  $this->rt.=" [&nbsp;<a class=cm href=\"".$hostName."/index.php?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;] ";
               } else {
                  $this->rt.=" <span class=oth>&nbsp;<a class=cm href=\"".$hostName."/index.php?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;</span> ";
               }
            }
         }
         if((($cr-1)*$lmt)+1!=$stt && !$viv){ $this->rt.=" <a class=cm href=\"".$hostName."/index.php?$oper&start=".($stt+$lmt)."\">".$lang['gbnext']."</a>"; }      
         $this->rt.="</td>";

/*
         $this->rt.="<td align=right valign=top nowrap>&nbsp;$stt..";
         if($cnt-$stt>=$lmt-1){ 
            $this->rt.=$stt+$lmt-1;
         }else{
            $this->rt.=$cnt;        
         }
         $this->rt.=" ".$lang['gbf']." ".$cnt."</td>";

*/
         $this->rt.="</tr></table>";
      }
      return $this->rt;
   } else {
      return "none";
   }
   }

}
?>