<?php


include("languages/lang_".$language['lng'].".php");

class FNMultipage{
	var $retcon;

	function replCO($mn,$bl,$at){
	global $uservar,$language;
	list($act,$grp)=mysql_fetch_row(mysql_query("select activ,usrgrp from iws_blockmenu where bid=".$bl." and lng='".$language['lng']."'"));
	if(($grp==0 && $act) || (isset($uservar) && $uservar['tr']=="ars" && $uservar['grp']==$grp && $act)){	
		$result=mysql_query("select A.id from iws_page_simple A, iws_menu B where A.mid=".$mn." and B.idm=A.mid and B.blk=".$bl);
		if(mysql_numrows($result)>=1){
			$result=mysql_query("select longcontent from iws_page_multi where id=$at");
			if(mysql_numrows($result)>=1){
				list($this->retcon)=mysql_fetch_row($result);
				$this->retcon = stripslashes($this->retcon);
				return true;
			}else{
				return false;
			}
		} else {
			return false;
		}
	}else{
		return false;
	}
	}

	function replCL($mn,$bl){ //родительская страница
	global $hostName,$uservar,$language,$lang;
	if(isset($bl) && isset($mn) && $bl>2){
	list($act,$grp)=mysql_fetch_row(mysql_query("select activ,usrgrp from iws_blockmenu where bid=".$bl." and lng='".$language['lng']."'"));
	if(($grp==0 && $act) || (isset($uservar) && $uservar['tr']=="ars" && $uservar['grp']==$grp && $act)){	
		$result=mysql_query("select A.content from iws_page_simple A, iws_menu B where A.mid=".$mn." and B.idm=A.mid and B.blk=".$bl);
		if(mysql_numrows($result)>=1){
			list($this->retcon)=mysql_fetch_row($result);
			$this->retcon = stripslashes($this->retcon);
			if(ereg("\[\/:multipage\]",$this->retcon)){
				$rslt=mysql_query("select id,shortcontent from iws_page_multi where mid=$mn");
				if(mysql_numrows($rslt)>=1){
					list($this->lnk)=mysql_fetch_row(mysql_query("select IF(link>=1,1,0) from iws_page_multi_pref where mid=$mn"));

					list($this->tmplmp)=mysql_fetch_row(mysql_query("select template".$language['lng']." from iws_html_templ_vivod where id=7"));
					if(ereg(":shortcontent",$this->tmplmp)){


						while($arr=mysql_fetch_row($rslt)){
						 	if($this->lnk){
						 		$this->pret.=str_replace("[/:shortcontent]","<a href=\"".$hostName."/index.php?go=mpage&block=$bl&menu=$mn&act=".$arr[0]."\">".stripslashes($arr[1])."</a>",$this->tmplmp);
							}else{
						 		$this->pret.=str_replace("[/:shortcontent]",stripslashes($arr[1])." <a href=\"".$hostName."/index.php?go=mpage&block=$bl&menu=$mn&act=".$arr[0]."\">".$lang['mpagen']."</a>",$this->tmplmp);
							}
						}
						$this->retcon = str_replace("[/:multipage]",$this->pret,$this->retcon);



					}else{
						$this->pret="<table cellpadding=2 cellspacing=0 border=0>";
						while($arr=mysql_fetch_row($rslt)){
						 	if($this->lnk){
						 		$this->pret.="<tr><td><a href=\"".$hostName."/index.php?go=mpage&block=$bl&menu=$mn&act=".$arr[0]."\">".stripslashes($arr[1])."</a></td></tr>";
							}else{
						 		$this->pret.="<tr><td>".stripslashes($arr[1])." <a href=\"".$hostName."/index.php?go=mpage&block=$bl&menu=$mn&act=".$arr[0]."\">".$lang['mpagen']."</a></td></tr>";
							}
						}
						$this->pret.="</table>";
						$this->retcon = str_replace("[/:multipage]",$this->pret,$this->retcon);
					}
				} else {
					$this->pret.="";
				}
					$this->retcon = str_replace("[/:multipage]",$this->pret,$this->retcon);
			}
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
	}else{
		return false;
	}
	}

}
?>
