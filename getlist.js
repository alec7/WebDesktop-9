var csrf=document.body.innerHTML.split('web_csrf_token" value="')[1].split('"')[0];
var hosts="http://127.0.0.1/wby/";
var page=1;
var sum=Math.floor(document.getElementById('pages').innerText.split('\共')[1].split('\页')[0]);
function   GetCookie(sName)
        {
              var   aCookie   =   document.cookie.split("; "); 
              for   (var   i=0;   i   <   aCookie.length;   i++)
              {
                  var   aCrumb   =   aCookie[i].split("="); 
                  if   (sName   ==   aCrumb[0])
                      return   unescape(aCrumb[1]);
              }
              return   null;
        }
function run(){
document.body.innerHTML="";
document.writeln('<iframe style="display:none;" src="'+hosts+'getlist.php?checkcsrf=1"></iframe><div style="position:fixed;left:0;float:left;width:100%;padding:10px;bottom:0;border:1px solid #ccc">状态：<span id="zt"></span><span id="zt"></span><span style="float:right;"><iframe srolling="no" width="530px" height="18px" src="'+hosts+'getlist.php?sid='+GetCookie('PHPSESSID')+'" frameborder="0"></iframe></span><span style="float:right;">客户端Sid:&nbsp;'+GetCookie('PHPSESSID')+'&nbsp;&nbsp;</span></div>');
}
$.ajax({
            url : 'http://qudao.weiboyi.com/auth/neworder',
            type : 'get',
            success : function (data){
			 run();
			  
            },
            error: function (){
			run();
            }
        });	
function getpage(){
document.writeln('<input type="hidden" name="web_csrf_token" value="'+csrf+'" id="web_csrf_token" />');
$.ajax({
            "url" : "http://qudao.weiboyi.com/bgtask/index/tasklist",
            'type' : 'post',
            'data' : "page="+page+"&web_csrf_token="+csrf,
            success : function (data){
			  document.writeln('<div style="float:left;width:300px;padding:10px;margin:5px;border:1px solid #ccc"><form id="ssd'+page+'" target="iix'+page+'" action="'+hosts+'getlist.php" method="post"><br/><input style="display:none;" value="'+(sum-1)+'" name="sum"/><input readonly="true" style="width:300px" id="num'+page+'" name="num"/><br/><textarea readonly="true" name="data" style="width:300px;height:300px" id="data'+page+'"></textarea><br/><iframe frameborder="0" id="iix'+page+'" src="about:blank"></iframe><br/><input type="submit" style="width:300px" /></form></div>');
			  document.getElementById('num'+page).value=page;
			  document.getElementById('data'+page).value=data;
			  document.getElementById('ssd'+page).submit();
			  document.getElementById('zt').innerHTML="已加载第"+page+"页的采集！";
			  page+=1;
			  if(page<sum){
			  getpage();
			  }else{
			  //run();
			  document.getElementById('zt').innerHTML='已加载所有页的采集！！！&nbsp;<span><a href="javascript:void();" onclick="page=1;run();getpage();">重新采集</a>&nbsp;<a href="javascript:void();" onclick="window.location.reload();">刷新页面</a></span>';
			  }
            },
            error: function (){
			
            }
        });
}	

getpage();