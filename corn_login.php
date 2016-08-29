<?php
ignore_user_abort(true);
set_time_limit(0);
require('lib_wby.php');
require('corn_config.php');
if(!file_exists('json')){mkdir('json');}
if($_GET['stop']){file_put_contents('json/corn_login.json','{"run":"0","time":"0"}');die('Stop LOGIN Setted!');}

if(file_exists('json/corn_login.json')){
$s=file_get_contents('json/corn_login.json');
$r0=json_decode($s,1);
if(time<($r0['time']+Corn_LOGIN_Time+60)){
die('Corn Login Running! '.$s);
}
}else{
if($_GET['run']){
@unlink('json/corn_login.json');
$ss=(ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'corn_login.php'));
if($ss){die($ss);}else{die('Run LOGIN Setted!');}
}else{echo 'Corn Login Starting...';
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 已开启登录守护！'.'<br/>',FILE_APPEND);
}
}
while(true){
if(file_exists('json/corn_login.json')){
$r=json_decode(file_get_contents('json/corn_login.json'),1);
if(!$r['run']){
@unlink('json/corn_login.json');
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 已关闭登录守护！'.'<br/>',FILE_APPEND);
die('Corn Login Ended!');
}
}
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'wby_login.php');
file_put_contents('json/corn_login.json','{"run":"1","time":"'.time().'"}');
sleep(Corn_LOGIN_Time);
}
?>