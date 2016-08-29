<?php
ignore_user_abort(true);
set_time_limit(0);
require('lib_wby.php');
require('corn_config.php');
if(!file_exists('json')){mkdir('json');}
if($_GET['stop']){file_put_contents('json/corn_run.json','{"run":"0","time":"0"}');die('Stop RUN Setted!');}

if(file_exists('json/corn_run.json')){
$s=file_get_contents('json/corn_run.json');
$r0=json_decode($s,1);
if(time<($r0['time']+Corn_RUN_Time+60)){
die('Corn Run Running! '.$s);
}
}else{
if($_GET['run']){
@unlink('json/corn_run.json');
$ss=(ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'corn_run.php'));
if($ss){die($ss);}else{die('Run RUN Setted!');}
}else{echo 'Corn Run Starting...';
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 已开启定时发送！'.'<br/>',FILE_APPEND);
}
}
while(true){
if(file_exists('json/corn_run.json')){
$r=json_decode(file_get_contents('json/corn_run.json'),1);
if(!$r['run']){
@unlink('json/corn_run.json');
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 已关闭定时发送！'.'<br/>',FILE_APPEND);
die('Corn Run Ended!');
}
}
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'wby_api.php');
file_put_contents('json/corn_run.json','{"run":"1","time":"'.time().'"}');
sleep(Corn_RUN_Time);
}
?>