<?php 
header("Content-Type:text/html;charset=utf-8");
require('lib_wby.php');
require('corn_config.php');
//pp('run',60);
if($_GET['del']){
if($_GET['del']=='login'){
$sname='login';
}
if($_GET['del']=='run'){
$sname='run';
}
if($sname){
if(!file_exists('logs')){mkdir('logs');}
if(file_exists('logs/'.$sname.'_log.htm')){unlink('logs/'.$sname.'_log.htm');}
wlogs();
die('<script>window.open("logs/'.$sname.'_log.htm?'.time().'","_self");</script>');
}
}
if($_GET['sid']){
include('config.php');
writeconfig($_GET['sid'],$csrf);
checkcsrf();
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' '.$_GET['sid'].' 已更换SID！'.'<br/>',FILE_APPEND);
die('sid 修改成功！'.'&nbsp;<a href="wby_run.php?check=1">检查SID可用性</a>');
}else{
if ($_GET['check']){
wlogs();
include('config.php');
include('main.inc');
}else{
if ($_GET['login']){
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'wby_login.php');
echo '布置登录完成！';
}else{
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'wby_api.php');
echo '布置发布完成！';
}
}
}
?>