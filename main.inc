<link rel="icon" href="http://weibo.com/favicon.ico" />
<title>WBY Checker</title>SID:&nbsp;<?php echo $sid;?>&nbsp;&nbsp;&nbsp;CSRF:&nbsp;<?php echo $csrf;?>&nbsp;<span style="float:right;"><a href="javascript:getcontent('wby_run.php?check=1','content');">刷新</a></span>&nbsp;
<br/><div id="oox">Operation:&nbsp;
<a href="javascript:document.getElementById('oox').innerHTML=getcontent('wby_api.php',0);">手动发布</a>&nbsp;
<a href="javascript:document.getElementById('oox').innerHTML=getcontent('getlist.php','oox');">列表采集</a>
&nbsp;<a href="javascript:var t=prompt('请输入新的SID','');if(t){getcontent('wby_run.php?sid='+t,'1');}">更换SID</a>&nbsp;

<span style="float:right;">
<?php
if(!checkcron('json/corn_run.json',Corn_RUN_Time)){
echo '<a href="javascript:getcontent(\'corn_run.php?run=1\',\'oox\');">开启定时发送</a>|';
}else{
echo '<a href="javascript:getcontent(\'corn_run.php?stop=1\',\'oox\');">关闭定时发送</a>|';
}
?>
<?php
if(!checkcron('json/corn_login.json',Corn_LOGIN_Time)){
echo '<a href="javascript:getcontent(\'corn_login.php?run=1\',\'oox\');">开启登录守护</a>|';
}else{
echo '<a href="javascript:getcontent(\'corn_login.php?stop=1\',\'oox\');">关闭登录守护</a>|';
}
?>
<?php
if(!checkcron('json/corn_csrf.json',Corn_CSRF_Time)){
echo '<a href="javascript:getcontent(\'corn_csrf.php?run=1\',\'oox\');">开启CSRF守护</a>|';
}else{
echo '<a href="javascript:getcontent(\'corn_csrf.php?stop=1\',\'oox\');">关闭CSRF守护</a>|';
}
?>
<?php
if(!checkcron('json/corn_lists.json',Corn_LISTS_Time)){
echo '<a href="javascript:getcontent(\'corn_lists.php?run=1\',\'oox\');">开启定时列表</a>|';
}else{
echo '<a href="javascript:getcontent(\'corn_lists.php?stop=1\',\'oox\');">关闭定时列表</a>|';
}
?>
</span>

<br/>Result:&nbsp;<span style="float:right;"><a href="javascript:document.getElementById('oox').innerHTML=getcontent('wby_login.php',0);">自动登录</a>|</span><br/>
<?php
$s=json_decode( curlFetch('http://qudao.weiboyi.com/',$sid,'http://qudao.weiboyi.com/','username=&password=&piccode=&mode=1&typelogin=1'),1);
if(!isset($s['status'])){echo '已登录！';$hh="80%";}else{echo '未登录！<br/><iframe frameborder="0" scrolling="no" width="250px" height="115px" src="wby_login.php?sd=1"></iframe>';$hh="60%";}
echo '<br/>Logs:<br/><iframe style="float:left;" frameborder="0" scrolling="yes" width="50%" height="'.$hh.'" src="logs/run_log.htm"></iframe><iframe style="float:right;" frameborder="0" scrolling="yes" width="50%" height="'.$hh.'" src="logs/login_log.htm"></iframe>';
?>
</div>