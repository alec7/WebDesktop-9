<?php
class ocr{

//echo getit('http://www.newocr.com/',$data);
function getit($url,$post_variables){
$content = http_build_query($post_variables); 
$content_length = strlen($content); 
$options = array( 
    'http'=>array( 
        'method'  => 'POST', 
        'header'  => 
              "Content-type: application/x-www-form-urlencoded\r\n" .  
              "Content-length: $content_length\r\n", 
        'content' => $content 
    ) 
); 
$context = stream_context_create($options); 
$file = file_get_contents($url, false, $context);
return $file;
}
function cleandz($gz,$html){
preg_match_all($gz, $html, $match);
$a=$match[1];
$s=array();
for ($i=0;$i<count($a);$i++){
if($a[$i]){
$s[count($s)]=$a[$i];
}
}
return array_unique($s);
}
function ocr_run($pic){
//echo $pic;
$data = array(
url=>$pic,
l=>eng,
preview=>1
); 
$t0=$this->getit('http://www.newocr.com/',$data);
//echo $t0;
$ii= $this->cleandz('/name="u" value="(.*?)"/is',$t0);
//var_dump($ii);
$data = array(
r=>0,
psm=>6,
u=>$ii[0],
x1=>5,
x2=>53,
y1=>6,
y2=>33,
ocr=>1,
l=>eng,
preview=>1
); 
return($this->cleandz('/id="ocr-result">(.*?)</is',$this->getit('http://www.newocr.com/',$data)));
}

}
if($_GET['id']){
$c=new ocr();
$s=$c->ocr_run(base64_decode($_GET['id']));
echo( $s[0]);
}
?>