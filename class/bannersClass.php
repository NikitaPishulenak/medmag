<?php

Class FNBanner {
   var $retcon;

	function banner_view ($banVar){
	global $language;
		$result=mysql_query("select banners".$language['lng']." from iws_banners where name='".substr($banVar,3,-1)."'");
		if(mysql_numrows($result)>=1){
			list($this->retcon) = mysql_fetch_row($result);
			$this->retcon = stripslashes($this->retcon);
		}else{
			$this->retcon = "";
		}
	}
}

?>