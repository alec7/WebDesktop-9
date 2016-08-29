<?php
header("Content-Type:text/html;charset=utf-8"); 
if($_GET['checkcsrf']){
checkcsrf();
die('checked!');
}
if($_GET['sid']){
include('config.php');
if($_GET['qd']){
file_put_contents('config.php','<?php $sid=\''.$_GET['sid'].'\';$csrf="'.$csrf.'";?>');
checkcsrf();
include('config.php');
die('<script>alert("已经更新Sid: '.$sid.'");</script><style>body{margin:0;}</style>服务器Sid为: '.$sid.'&nbsp;<a href="javascript:void();" onclick="location.href=\'getlist.php?qd=1&sid='.$_GET['sid'].'\'">更新Sid</a>&nbsp;<a href="javascript:void();" onclick="window.open(\'wby_run.php?check=1\',\'_blank\');">检查Sid</a>&nbsp;<a href="javascript:void();" onclick="window.open(\'logs/run_log.htm\',\'_blank\');">历史记录</a>');
}else{
die('<style>body{margin:0;}</style>服务器Sid为: '.$sid.'&nbsp;<a href="javascript:void();" onclick="location.href=\'getlist.php?qd=1&sid='.$_GET['sid'].'\'">更新Sid</a>&nbsp;<a href="javascript:void();" onclick="window.open(\'wby_run.php?check=1\',\'_blank\');">检查Sid</a>&nbsp;<a href="javascript:void();" onclick="window.open(\'logs/run_log.htm\',\'_blank\');">历史记录</a>');
}
};
ignore_user_abort(true);
set_time_limit(0);
require('lib_wby.php');

if(isset($_POST['num'])&&isset($_POST['data'])){
if(!file_exists('lists')){mkdir('lists');}
$r=getcontents($_POST['data']);
$v=array();
$v['num']=count($r);
$v['tid']=$r;
include('config.php');
for($i=0;$i<count($r);$i++){
$rs=getarticle($sid,$r[$i]);
if($rs){
for($x=0;$x<count($rs);$x++){
$v['data'][$r[$i]][$x]=$rs[$x];
}
}
}
file_put_contents('lists/'.$_POST['num'].'.xhr',json_encode($v));
file_put_contents('lists/sum.xhr',$_POST['sum']);
die ('已经采集 '.$_POST['num'].'.xhr<br/>');
print_r($v);
}
if($_GET['auto']){
pp('getlists',10800);
checkcsrf();
include ('config.php');
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 开始采集列表！'.'<br/>',FILE_APPEND);
die(collectlists($sid,$csrf));
}
$r=ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'getlist.php?auto=1');
if(!$r){$r='Setted!';}
die('<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:700\' rel=\'stylesheet\' type=\'text/css\' /><style>a,html{background:black;color:white;font-family:\'Open Sans\', sans-serif;font-size:12px;line-height:20px;}</style>'.$r);
?>