<?php

include("inc/config.inc.php");
//----------------------------------------------------------------------------------------

function display_size($doc)
{
	 global $docRoot;
    $doc_size = filesize($docRoot."/DocsForDownload/".$doc);
    if($doc_size >= 1073741824) {
        $doc_size = round($doc_size / 1073741824 * 100) / 100 . " Gb";
    } elseif($doc_size >= 1048576) {
        $doc_size = round($doc_size / 1048576 * 100) / 100 . " Mb";
    } elseif($doc_size >= 1024) {
        $doc_size = round($doc_size / 1024 * 100) / 100 . " Kb";
    } else {
        $doc_size = $doc_size . " b";
    }
    return $doc_size;
}

//----------------------------------------------------------------------------------------

function delDoc($docToDel)
{
	global $docRoot;
  	if(is_file($docRoot."/DocsForDownload/".$docToDel)) unlink($docRoot."/DocsForDownload/".$docToDel);
}

//----------------------------------------------------------------------------------------

function copy_doc_toserver()
{
	if(isset($_FILES['docarchive']) && $_FILES['docarchive']['error'] != 4) {

		if($_FILES['docarchive']['error']) return false;

		if(!is_uploaded_file($_FILES['docarchive']['tmp_name'])) return false;

//	 	if($_FILES['docarchive']['error'] == 1 || $_FILES['docarchive']['size'] > ($mb*1048576) || $_FILES['docarchive']['size']; == 0) return false;

		global $docRoot;

		if(!is_dir($docRoot."/DocsForDownload")) mkdir($docRoot."/DocsForDownload", 0755);

  		$doc = encodestring(strtolower(str_replace(" ", "", basename($_FILES['docarchive']['name']))));
		$doc = date("YmdHis").$doc;

      move_uploaded_file($_FILES['docarchive']['tmp_name'], $docRoot."/DocsForDownload/".$doc);  return $doc;

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

function unicumId()
{
 	return md5(uniqid(rand(),1));
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
