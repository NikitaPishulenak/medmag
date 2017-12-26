<?php
include('../../inc/config.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
   <title>�������� ����������</title>
   <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
   <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
   <style type="text/css">
      .TextInner {font-family:Arial; font-size:13px; margin-bottom:5px;}
      .ProgressBar {border:1px #c0c0c0 solid; padding:5px; height:20px; width:50%}
      .PrCell {width:3px; height:15p�; display:table; background-color:#d10202;}
   </style>
   <script type='text/javascript' src='http://code.jquery.com/jquery-1.4.3.min.js'></script>
   <script><!--

$(document).ready(function(){
var domenN='<?php echo $hostName; ?>';

setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=hour',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (����)    '); },
      success: function(data){
         $('.PrCell').css('width','8%');
         $('.TextInner').text('�������� �� ����...');
         Next1();
      }
   });
}, 1000);

function Next1(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=day',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (���)    '); },
      success: function(data){
         $('.PrCell').css('width','16%');
         $('.TextInner').text('�������� �� �������...');
         Next2();
      }
   });
}, 1000);
}

function Next2(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=month',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (�����)   '); },
      success: function(data){
         $('.PrCell').css('width','24%');
         $('.TextInner').text('�������� �� ��������...');
         Next3();
      }
   });
}, 1000);
}

function Next3(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=menu',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (�������)   '); },
      success: function(data){
         $('.PrCell').css('width','32%');
         $('.TextInner').text('�������� �� ������������...');
         Next4();
      }
   });
}, 1000);
}

function Next4(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=photo',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (����������)    '); },
      success: function(data){
         $('.PrCell').css('width','40%');
         $('.TextInner').text('�������� �� �����������...');
         Next5();
      }
   });
}, 1000);
}

function Next5(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=files',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (����������)    '); },
      success: function(data){
         $('.PrCell').css('width','48%');
         $('.TextInner').text('�������� �� a������������...');
         Next6();
      }
   });
}, 1000);
}

function Next6(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=files_C',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (������������)   '); },
      success: function(data){
         $('.PrCell').css('width','56%');
         $('.TextInner').text('�������� ������� ������� ��������...');
         Next7();
      }
   });
}, 1000);
}

function Next7(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=files_B',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (������� ��������)   '); },
      success: function(data){
         $('.PrCell').css('width','64%');
         $('.TextInner').text('�������� �������...');
         Next8();
      }
   });
}, 1000);
}

function Next8(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=files_A',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (������)   '); },
      success: function(data){
         $('.PrCell').css('width','72%');
         $('.TextInner').text('�������� �� ��������� �������...');
         Next9();
      }
   });
}, 1000);
}

function Next9(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=arts',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (�������-������)    '); },
      success: function(data){
         $('.PrCell').css('width','80%');
         $('.TextInner').text('�������� �� ��������� � ������...');
         Next10();
      }
   });
}, 1000);
}

function Next10(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=frm',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (�������� � ������)    '); },
      success: function(data){
         $('.PrCell').css('width','90%');
         $('.TextInner').text('�������� ������ � ����������� �������...');
         Next11();
      }
   });
}, 1000);
}

function Next11(){
setTimeout(function(){  
   $.ajax({
      url: domenN+'/cron/dump_statistic.php',
      data: 'dumpAct=delANDopt',
      async: false,
      error: function(){alert('������ �������� ����������, ���������� �����. (�������� � �����������)    '); },
      success: function(data){
         $('.PrCell').css('width','100%');
         $('.TextInner').text('������!');
      }
   });
}, 1000);
}
});            
//--></script>
</head>
<body>
<div class="TextInner">�������� �� �����...</div>
<div class="ProgressBar">
      <div class="PrCell">&nbsp;</div>
</div>
</body>
</html>