<?php
date_default_timezone_set("PRC");
function curlFetch($url, $phpsessid = "", $referer = "", $data,$nohead=null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而非直接输出
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31"); 
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
if(!$nohead){
$header = array( 
'X-Requested-With:XMLHttpRequest', 
); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
}
        curl_setopt($ch, CURLOPT_HEADER, 0);   // 不返回header部分
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间
        if (!empty($referer))
        {
            curl_setopt($ch, CURLOPT_REFERER, $referer);   // 设置引用网址
        }
		if (!empty($phpsessid))
		{
        curl_setopt($ch,CURLOPT_COOKIE,'PHPSESSID='.$phpsessid);
		}
        if (is_null($data))
        {
            // GET
        }
        else if (is_string($data))
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // POST
        }
        else if (is_array($data))
        {
            // POST
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $str = curl_exec($ch);
        curl_close($ch);
        return $str;
}
function ffopen($url,$timeout=3){
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);   //只需要设置一个秒的数量就可以
       $output = curl_exec($ch);
       curl_close($ch);
	   return $output;
}
function cleandz($gz,$html){
preg_match_all($gz, $html, $match);
$a=array_unique($match[1]);
//echo count($match[1]);
$s=array();
for ($i=0;$i<count($match[1]);$i++){
if($a[$i]){
$s[count($s)]=$a[$i];
}
}
return ($s);
}
function getcodepic($sid){
$u=json_decode(curlFetch('http://qudao.weiboyi.com/auth/index/captcha',$sid,'http://qudao.weiboyi.com/',null),true);
return 'http://qudao.weiboyi.com'.urldecode($u['url']);
}
function sdlogin($sid,$code){
$rs= curlFetch('http://qudao.weiboyi.com/',$sid,'http://qudao.weiboyi.com/','username=ghostgzt&password=xxmcl1991&piccode='.$code.'&mode=1&typelogin=1');
$rs=json_decode($rs,true);
$rs=$rs['status'];
if($rs){
return true;
}else{
return false;
}
}
function getuser($sid){
$s=json_decode(curlFetch('http://qudao.weiboyi.com/bgtask/index/choiceaccount',$sid,'http://qudao.weiboyi.com/',null),1);
$num=$s['data']['rows'];
$arr=array();
for($i=0;$i<count($num);$i++){
$arr[count($arr)]= ($num[$i]['account_id'].':'.$num[$i]['weibo_id'].':'.$num[$i]['weibo_type'].':'.$num[$i]['is_link']);
}
return ($arr);
}
function getpage($sid){
$s0=cleandz('/task\.getPage\((.*?)\)/is',curlFetch('http://qudao.weiboyi.com/bgtask',$sid,'http://qudao.weiboyi.com/',null));
$s1=split(',',$s0[0]);
$s2=$s1[0];
$s3=$s1[2];
$s4=$s2/$s3;
$p= ceil($s4);
if(!file_exists('lists')){mkdir('lists');}
file_put_contents('lists/sum.xhr',$p);
return $p;
}
function getlist($sid,$page,$csrf){
$list=(curlFetch('http://qudao.weiboyi.com/bgtask/index/tasklist',$sid,'http://qudao.weiboyi.com/bgtask/index','page='.$page.'&web_csrf_token='.$csrf));
echo $list;
$r=getcontents($list);
$v=array();
$v['num']=count($r);
$v['tid']=$r;
for($i=0;$i<count($r);$i++){
$rs=getarticle($sid,$r[$i]);
if($rs){
for($x=0;$x<count($rs);$x++){
$v['data'][$r[$i]][$x]=$rs[$x];
}
}
}
if(count($v['data'])){
if(!file_exists('lists')){mkdir('lists');}
file_put_contents('lists/'.$page.'.xhr',json_encode($v));
}
return $list;
}
function gettask($sid,$page)
{
$list=json_decode(file_get_contents('lists/'.$page.'.xhr'),1);
$list=$list['tid'];
return $list;
}
function getcontents($content){
$aaa=array();
$ddd=cleandz('/\/bgtask\/index\/detail\/tid\/(.*?)\"/is',$content);
return ($ddd);
}
function getarticle($sid,$tid){
return cleandz('/data-cid="(.*?)"/is',curlFetch('http://qudao.weiboyi.com/bgtask/index/detail/tid/'.$tid,$sid,'http://qudao.weiboyi.com/',null));
}
function collectlists($sid,$csrf){
$p=getpage($sid);
for($i=0;$i<$p;$i++){
getlist($sid,$i,$csrf);
}
}
function getcsrf($sid){
$s=curlFetch('http://qudao.weiboyi.com/',$sid,'http://qudao.weiboyi.com/',null,1);
$r=cleandz('/web_csrf_token" value="(.*?)"/is',$s);
return $r[0];
}
function writeconfig($sid,$csrf){
file_put_contents('config.php','<?php $sid=\''.$sid.'\';$csrf="'.$csrf.'";?>');
}
function checkcsrf(){
include('config.php');
$ccr=getcsrf($sid);
if($ccr&&$ccr!=$csrf){$csrf=$ccr;writeconfig($sid,$csrf);
wlogs();
file_put_contents('logs/login_log.htm',date('y-m-d h:i:s').' '.$csrf.' 已更换CSRF！'.'<br/>',FILE_APPEND);
}
if($csrf){
file_get_contents('http://qudao.weiboyi.com/auth/neworder?web_csrf_token='.$csrf);
}
}
function wlogs(){
if(!file_exists('logs')){mkdir('logs');}
if(!file_exists('logs/login_log.htm')){file_put_contents('logs/login_log.htm','<meta charset="UTF-8" />
<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:700\' rel=\'stylesheet\' type=\'text/css\' /><style>a,html{background:black;color:white;font-family:\'Open Sans\', sans-serif;font-size:12px;line-height:20px;}</style><span style="position:fixed;right:0;top:0;float:right;">&nbsp;<a href="javascript:void();" onclick="window.location.reload();">刷新</a>&nbsp;<a href="../wby_run.php?del=login">清空日志</a>&nbsp;<a href="javascript:scroll(0,0);">顶部</a>&nbsp;<a href="javascript:scroll(0,document.body.scrollHeight);">底部</a></span><span style="font-size:20px;">Login_Logs</span><br/>');}
if(!file_exists('logs/run_log.htm')){file_put_contents('logs/run_log.htm','<meta charset="UTF-8" />
<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:700\' rel=\'stylesheet\' type=\'text/css\' /><style>a,html{background:black;color:white;font-family:\'Open Sans\', sans-serif;font-size:12px;line-height:20px;}</style><span style="position:fixed;right:0;top:0;float:right;">&nbsp;<a href="javascript:void();" onclick="window.location.reload();">刷新</a>&nbsp;<a href="../wby_run.php?del=run">清空日志</a>&nbsp;<a href="javascript:scroll(0,0);">顶部</a>&nbsp;<a href="javascript:scroll(0,document.body.scrollHeight);">底部</a></span><span style="font-size:20px;">Run_Logs</span><br/>');}
}
function pp($lx,$tt,$ts=null){
if(!file_exists('pp')){mkdir('pp');}
if(file_exists('pp/time_'.$lx.'.pp')){$t=file_get_contents('pp/time_'.$lx.'.pp');}else{$t=0;}
if(time()<($t+$tt)){
if($ts){echo '<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:700\' rel=\'stylesheet\' type=\'text/css\' /><style>a,html{background:black;color:white;font-family:\'Open Sans\', sans-serif;font-size:12px;line-height:20px;}</style>';}
die('进行中！ 请'.intval($t+$tt-time()).'秒后刷新');}
file_put_contents('pp/time_'.$lx.'.pp',time());
}
function corn_all(){
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'corn_csrf.php');
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'corn_login.php');
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'corn_run.php');
ffopen("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'corn_lists.php');
}
function checkcron($file,$time){
if(file_exists($file)){
$s=file_get_contents($file);
$r0=json_decode($s,1);
if(time()<($r0['time']+$time+60)){
return true;
}else{
return false;
}
}else{
return false;
}
}
?>