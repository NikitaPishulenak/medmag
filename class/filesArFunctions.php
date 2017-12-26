<?php

function display_size($file)
{
	 global $docRoot;
    $file_size = filesize($docRoot."/FilesForDownload/".$file);
    if($file_size >= 1073741824) {
        $file_size = round($file_size / 1073741824 * 100) / 100 . " Ãá";
    } elseif($file_size >= 1048576) {
        $file_size = round($file_size / 1048576 * 100) / 100 . " Ìá";
    } elseif($file_size >= 1024) {
        $file_size = round($file_size / 1024 * 100) / 100 . " Êá";
    } else {
        $file_size = $file_size . " á";
    }
    return $file_size;
}

//----------------------------------------------------------------------------------------

function delFile($fileToDel)
{
	global $docRoot;
  	if(is_file($docRoot."/FilesForDownload/".$fileToDel)) unlink($docRoot."/FilesForDownload/".$fileToDel);
}

//----------------------------------------------------------------------------------------

function copy_file_toserver($mb,$ext)
{
	if(isset($_FILES['FileUp']) && $_FILES['FileUp']['error'] != 4) {

		if($_FILES['FileUp']['error']) return false;

		if(!is_uploaded_file($_FILES['FileUp']['tmp_name'])) return false;

	 	if($_FILES['FileUp']['error'] == 1 || $_FILES['FileUp']['size'] == 0) return false;

	 	if($mb && $_FILES['FileUp']['size'] > ($mb*1048576)) return false;

	 	if($ext)
	 	{
			$ext=str_replace(" ", "", $ext);
			$ext=str_replace(",","|.",$ext);
         if(!ereg("(.".$ext.")",$_FILES['FileUp']['name'])) return false; 
	 	}

		global $docRoot;

		if(!is_dir($docRoot."/FilesForDownload")) mkdir($docRoot."/FilesForDownload", 0755);

  		$file = encodestring(strtolower(str_replace(" ", "", basename($_FILES['FileUp']['name']))));
		$file = date("YmdHis").$file;

      move_uploaded_file($_FILES['FileUp']['tmp_name'], $docRoot."/FilesForDownload/".$file);  return $file;

	} else { return false; }

}

//----------------------------------------------------------------------------------------

function encodestring($st)
{
	$st=strtr($st,"àáâãäå¸çèéêëìíîïðñòóôõúûý_","abvgdeeziyklmnoprstufh'iei");

	$st=strtr($st,"ÀÁÂÃÄÅ¨ÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÚÛÝ_","abvgdeeziyklmnoprstufh'iei");

	$st=strtr($st, array("æ"=>"zh", "ö"=>"ts", "÷"=>"ch", "ø"=>"sh", "ù"=>"shch","ü"=>"", "þ"=>"yu", "ÿ"=>"ya", "Æ"=>"zh", "Ö"=>"ts", "×"=>"ch", "Ø"=>"sh", "Ù"=>"shch","Ü"=>"", "Þ"=>"yu", "ß"=>"ya", "¿"=>"i", "¯"=>"yi", "º"=>"ie", "ª"=>"ye"));

   return $st;
}

//---------------------------------------------------------------------------------------

function unicumId()
{
 	return md5(uniqid(rand(),1));
}

//-----------------------------------------------------------------------------------------------------------------------

function numlink($start,$oper,$bd,$sortB='',$lmt,$lang,$hostN)
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
	
		if($start<>1) $rt.="<a class=cm href=\"$hostN/index.php?$oper&start=".($start-$lmt)."\">".$lang['filesprev']."</a> ";
		
		for($i=$nv;$i<=$kn;$i++){
			if($start==1 && $i==1){
				$rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
			}elseif((($i-1)*$lmt)+1==$start){
				$rt.=" <b><span class=cur>&nbsp;".$i."&nbsp;</span></b> ";
			} else {
				$rt.=" <span class=oth>&nbsp;<a class=cm href=\"$hostN/index.php?$oper&start=".((($i-1)*$lmt)+1)."\">".$i."</a>&nbsp;</span> ";
			}
		}
		if((($cr-1)*$lmt)+1!=$start) $rt.=" <a class=cm href=\"$hostN/index.php?$oper&start=".($start+$lmt)."\">".$lang['filesnext']."</a>";

		$rt.="";
/*
		$rt.="</td><td align=right>$start..";

		if(($cnt-$start)>=($lmt-1)){ 
			$rt.=$start+$lmt-1;
		}else{
			$rt.=$cnt;			
		}
		$rt.=" èç ".($cnt)."</td></tr></table>";
*/
		return $rt;
	} else {
		return false;
}
}


?>