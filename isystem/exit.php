<?php

unset($mainadvar);
session_register("mainadvar");
if(!isset($mainadvar) || empty($mainadvar['ath']) || $mainadvar['ath']!="avtores") { 
   echo "����� �����, ������ �� ����� �����.";
   exit;
}
?>