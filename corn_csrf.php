<?php
ignore_user_abort(true);
set_time_limit(0);
require('lib_wby.php');
require('corn_config.php');
if(!file_exists('json')){mkdir('json');}
if($_GET['stop']){file_put_contents('json/corn_csrf.json','{"run":"0","time":"0"}');die('Stop CSRF Setted!');}

if(file_exists('json/corn_csrf.json')){
$s=file_get_contents('json/corn_csrf.json');
$r0=json_decode($s,1);
if(time<($r0['time']+Corn_CSRF_Time+60)){
die('Corn CSRF Running! '.$s);
}
}else{
if($_GET['run']){
@unlink('json/corn_csrf.json');
$ss=(ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'corn_csrf.php'));
if($ss){die($ss);}else{die('Run CSRF Setted!');}
}else{echo 'Corn CSRF Starting...';
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 已开启CSRF守护！'.'<br/>',FILE_APPEND);
}
}
while(true){
if(file_exists('json/corn_csrf.json')){
$r=json_decode(file_get_contents('json/corn_csrf.json'),1);
if(!$r['run']){
@unlink('json/corn_csrf.json');
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 已关闭CSRF守护！'.'<br/>',FILE_APPEND);
die('Corn CSRF Ended!');
}
}
checkcsrf();
file_put_contents('json/corn_csrf.json','{"run":"1","time":"'.time().'"}');
sleep(Corn_CSRF_Time);
}
?>