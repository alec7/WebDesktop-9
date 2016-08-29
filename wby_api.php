<?php
ignore_user_abort(true);
set_time_limit(0);
require('lib_wby.php');
function sendnow($account,$tid,$cid,$sid,$csrf){
if($account&&$tid&&$cid){
$stid=split('\/',$tid);
$stid=$stid[0];
echo 'Data: account[]='.$account.'&cid='.$cid.'&tid='.$stid;
file_get_contents('http://qudao.weiboyi.com/auth/neworder?web_csrf_token='.$csrf);
$r=curlFetch('http://qudao.weiboyi.com/bgtask/index/create'.'?'.'account[]='.$account.'&cid='.$cid.'&tid='.$stid,$sid,'http://qudao.weiboyi.com/',null);
$rr=json_decode($r,1);
wlogs();
file_put_contents('logs/run_log.htm',date('y-m-d h:i:s').' '.$account.' '.$stid.' '.$cid.'<br/>Result: '.$rr['message'].'<br/>',FILE_APPEND);
return $r;
}
}
function run($sid,$csrf){
if($sid){
$b=$sid;echo 'SID: '.$b.'<br/>';echo 'CSRF: '.$csrf.'<br/>';
$a=getuser($b);
$p=getpage($b);
for($i=0;$i<count($a);$i++){
echo 'User: '.$a[$i].'<br/>';
$h=rand(0,$p-1);echo 'Page: '.$h;//page
$c=gettask($b,$h);
$d=$c[rand(0,count($c)-1)];
echo '<br/>'.'TID: '.$d.'<br/>';//tid
$list=json_decode(file_get_contents('lists/'.$h.'.xhr'),1);
$e=$list['data'][$d];
$f=$e[rand(0,count($e)-1)];
echo 'GID: '.$f.'<br/>';
echo 'Result: '.sendnow($a[$i],$d,$f,$b,$csrf).'<br/>'.'<br/>';
}
}
}
pp('api',60,1);
checkcsrf();
//corn_all();
include('config.php');
echo '<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:700\' rel=\'stylesheet\' type=\'text/css\' /><style>a,html{background:black;color:white;font-family:\'Open Sans\', sans-serif;font-size:12px;line-height:20px;}</style>';
run($sid,$csrf);//sid=n61rtarrb6hh81c54il21qcsh5
?>