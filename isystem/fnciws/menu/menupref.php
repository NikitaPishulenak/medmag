<?php


function prefmn(){
global $mainadvar;
include('fnciws/menu/menu.inc.php');

if($mainadvar['sadm']){
	$sql="select ".$fieldnmmp['descr'].",".$fieldnmmp['url']." from ".$mpatbl;
	$dst = 1;
} else {
	if($mainadvar['prf']){	
		$prf=explode("-",$mainadvar['prf']);
		for($i=0;$i<=count($prf)-1;$i++){
			if(!$i){
				$sql=$fieldnmmp['url']."='".$prf[$i]."'";
			}else{
				$sql.=" or ".$fieldnmmp['url']."='".$prf[$i]."'";
			}
		}
		$sql="select ".$fieldnmmp['descr'].",".$fieldnmmp['url']
		." from ".$mpatbl." where ".$sql;
		$dst = 1;
	}else{
	  $dst = 0;
	}
}
$res=mysql_query($sql);
$ret="";
if($dst && mysql_numrows($res)>=1)
	while($arr=mysql_fetch_row($res)){ $ret.="<tr><td><li><a target=C href=\"mainiwspref.php?gopr=".$arr[1]."\">".$arr[0]."</a></td></tr>\n";	}
return $ret;
}

?>