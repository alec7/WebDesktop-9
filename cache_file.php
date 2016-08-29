<?php
if($_GET['url']&&$_GET['file']){
if(!is_dir('cache')){
mkdir('cache');

}

$t=time();
if(copy(urldecode($_GET['url']),'cache/'.$t.$_GET['file'])){
$r=array('pic'=>'http://'.$_SERVER[HTTP_HOST].dirname($_SERVER[PHP_SELF]).'/cache/'.$t.$_GET['file']);
echo json_encode($r);
}else{
echo 0;
}
}
?>