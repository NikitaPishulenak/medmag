<?php

$natbl="iws_guestbk";
$fieldnmn= array(
		"did"=>"id",
		"dat"=>"datu",
		"nm"=>"name",
		"em"=>"email",
		"ww"=>"hmp",
		"icq"=>"icq",
		"ct"=>"city",
		"cr"=>"cntr",
		"cm"=>"coment",
		"ln"=>"lng",
		"nom"=>"nomod",
		"ip"=>"ip"
);

$gatbl="iws_guestcm";
$fieldnmg= array(
		"did"=>"id",
		"gd"=>"gid",
		"dat"=>"datu",
		"nm"=>"name",
		"em"=>"email",
		"ww"=>"hmp",
		"icq"=>"icq",
		"ct"=>"city",
		"cr"=>"cntr",
		"cm"=>"coment",
		"rt"=>"root",
		"ip"=>"ip"
		
);

$patbl="iws_guestpref";
$fieldnmp= array(
		"did"=>"id",
		"lmt"=>"limt",
		"vd"=>"vivod",
		"cm"=>"coment",
		"rt"=>"rtr",
		"rul"=>"rules".$mainadvar['lng']
);

?>