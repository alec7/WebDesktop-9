<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(600); //设置脚本超时时间，为0时不受时间限制
require('lib_wby.php');
if(is_file('turnon.php')){
@unlink('turnon.php');
}
sleep(10);
file_put_contents('turnon.php','<?php $ss=1;?>');
//include('ocr.php');
function deldir($dir) {
if (!file_exists($dir)){return true;
}else{@chmod($dir, 0777);}
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          @unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}


function pd($str){
if(!$str){return 'NULL';}else{return $str;}
}
function login($sid){
$u=json_decode(curlFetch('http://qudao.weiboyi.com/auth/index/captcha',$sid,'http://qudao.weiboyi.com/',null),true);
$t=array();
require('cache_host.php');//$t[count($t)]='http://xo.aws.af.cm/wby/cache_file.php';
$p=file_get_contents($t[rand(0,count($t)-1)].'?file=code.png&url='.urlencode('http://qudao.weiboyi.com'.$u['url']));
$p=str_replace('﻿','',$p);
$p=json_decode($p,true);
$p=$p['pic'];
$y=array();
require('ocr_host.php');//$y[count($y)]='http://mc.xn--d-fga.com/tt_oo.php';
$k=$y[rand(0,count($y)-1)].'?id='.base64_encode($p);
$x=curlFetch($k,null,null,null);
echo($k.'<br/>');
$rs= curlFetch('http://qudao.weiboyi.com/',$sid,'http://qudao.weiboyi.com/','username=ghostgzt&password=123456&piccode='.$x.'&mode=1&typelogin=1');
$rs=json_decode($rs,true);
$rs=$rs['status'];
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' code='.pd($x).' status='.pd($rs).'<br/>',FILE_APPEND);
return array('code'=>$x,'status'=>$rs);
}
function check($sid){
$rs= curlFetch('http://qudao.weiboyi.com/',$sid,'http://qudao.weiboyi.com/','username=&password=&piccode=&mode=1&typelogin=1');
$rs=json_decode($rs,true);
$rs=$rs['status'];
if(!isset($rs)){
deldir('cache');
if(is_file('turnon.php')){
@unlink('turnon.php');
}
corn_all();
checkcsrf();
die('已登录！不用重复！');
}else{

if($_GET['sd']){
require('config.php');
die('<form action="wby_login.php" method="get"><table border=10><tr><th><img src="'.getcodepic($sid).'"/></th><th><input type="button" value="刷新" style="height:100%" onclick="window.location.reload();"/></th></tr><tr><td><input name="code" value=""/></td><td><input type="submit"/></td></tr></table></form>');
}


while(true){
require('config.php');
require('turnon.php');
if(!file_get_contents('turnon.php')){die('异常！！');}
$f=login($sid);
	echo var_dump($f).'<br/>';
 echo str_repeat(" ",256);//写满IE有默认的1k buffer
  ob_flush();//将缓存中的数据压入队列
    flush();//输出缓存队列中的数据
if($f[status]){
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' 已登录！'.'<br/>',FILE_APPEND);
deldir('cache');
if(is_file('turnon.php')){
@unlink('turnon.php');
}
corn_all();
checkcsrf();
die('已登录！');
	}
	sleep(20);
	}
	}
	}
wlogs();

	if($_GET['code']){
	echo $_GET['code'].' ';
	require('config.php');
	$rs=sdlogin($sid,$_GET['code']);
	if($rs){
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' '.$_GET['code'].' 已手动登录！'.'<br/>',FILE_APPEND);
deldir('cache');
if(is_file('turnon.php')){
@unlink('turnon.php');
}
checkcsrf();
corn_all();
die('已登录！');
	}
	
	}else{
	require('config.php');
	echo '<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:700\' rel=\'stylesheet\' type=\'text/css\' /><style>a,html{background:black;color:white;font-family:\'Open Sans\', sans-serif;font-size:12px;line-height:20px;}</style>';
	check($sid);
	}
	
	//var_dump(login($sid));
?>