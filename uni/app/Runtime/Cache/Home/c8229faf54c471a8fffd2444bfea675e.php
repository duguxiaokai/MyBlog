<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<link href="__PUBLIC__/themes/default/css/common.css" rel="stylesheet" type="text/css">

<script src='__PUBLIC__/js/jquery.js'></script>
<script src='__PUBLIC__/jusaas/js/common.js'></script>
<script src="__PUBLIC__/jusaas/js/autoform.js"></script>
<script src="__PUBLIC__/jusaas/js/dom.js"></script>
<script src='__PUBLIC__/jusaas/js/login.js'></script>
<script src="__PUBLIC__/jusaas/js/utils.js"></script>
<script language="JavaScript">

var mcss_lang=common_getParam("lang");
if (!mcss_lang)
	mcss_lang=getCookie("mcss_lang");
if (!mcss_lang) mcss_lang='cn';

if (mcss_lang && mcss_lang!='cn')
	$("#login_div").hide();
	
$(document).ready(function() 
{
	initLogin();
});

function initLogin()
{
	/*登陆框的位置*/
	var left = (document.documentElement.clientWidth-$(".smallloginBox").width())/2;   
	var top =(document.documentElement.clientHeight-$(".smallloginBox").height())/2;
	$(".smallloginBox").css("left",left+"px").animate({top:top+"px"},1000);
	var saas='<?php echo ($mcss_saas); ?>';
	setCookie('mcss_saas',saas);
	$("#org_tr").hide();
	return;
}
	setTimeout('setLanguage()',1000);	


//把html页面上汉子转化为对应的语言
function setLanguage()
{
	var dom_id_arr="login_btn,username_span,password_span,pleaselogin";
	uitls_setLanguage(dom_id_arr);
	$("#login_div").show();	
}

function gotoHomepage()
{
	location.href=g_homeurl;
}


</script>
<style type="text/css">
#backgroundPopup{   
display:none;   
position:fixed;   
_position:absolute;   
height:100%;   
width:100%;   
top:0;   
left:0;   
background:#000000;   
border:1px solid #cecece;   
z-index:1;   
}   
#popupContact{   
display:none;   
position:fixed;   
_position:absolute;   
height:250px;   
width:350px;   
background:#FFFFFF;   
border:2px solid #cecece;   
z-index:2;   
padding:12px;   
font-size:13px;   
}   
#popupContact h1{   
text-align:left;   
color:#A35B20;   
font-size:12px;   
font-weight:700;   
border-bottom:1px dotted #A35B20;   
padding-bottom:2px;   
margin-bottom:10px;   
}   
#popupContactClose{   
font-size:14px;   
line-height:14px;   
right:6px;   
top:4px;   
position:absolute;   
color:#A35B20;  
border:#A35B20 1px solid; 
font-weight:700;   
display:block; padding:0 2px;
cursor:pointer;
} 
#contactArea{ line-height:20px;}
label{color:#AF7715}
label.error{ color:red}

.findpwdinput{background-color: #FFFFFF;
    border: 1px solid #D6BC8B;
    height: 16px;
    width: 130px;}
	#contactArea h2{ margin-top:10px;}
	.findSub{ width:50px; height:25px; background:#C60; color:#FFF; border:none; font-weight:bold}
	.smallloginBox input.smallInputbg{ height:45px; line-height:45px;}
</style>
</head>
<body class="smalllogoBg">

	<div class="smallloginBox" id='login_div' style='display123:none;'>
    	<div class="smallloginBoxBotbg">
    	<div class="smallloginPadding">
    	<table>
    	<tr>
		<td>
		<img onclick="gotoHomepage();" src="__PUBLIC__/themes/default/images/logo.png" style="padding-left:0px;width:80px;height:30px;cursor:pointer;" alt="doPP logo" />
    	</td>
    	<td>
    	<h3 id='pleaselogin'>登录</h3>
    	</td>
    	<tr>
    	<td>
        <p><span id='username_span' style='width:80px'>用户名</span>
        </td>
        <td><input type="text" class="smallInputbg123" name="username" id="username" onKeyup="gotologin(event);"  onBlur="getLoginuserOrgList()"/></p>
        </td>
        </tr>
        <tr>
        <td>
        <p><span id='password_span'style='width:80px'>密码</span>
        </td>
        <td>
        <input type="password" class="smallInputbg123" id="password" name="password" onKeyup="gotologin(event);"/></p>
        </td>
        </tr>
        <tr>
        <td></td>
        <td>
        	<input type="button" class="btn btn-green" value="登录" onClick="login_login()" id="login_btn" />
        </td>
        </tr>
        </table>
   		<p id='org_tr'><span class="inputName">组　织：</span><select id='orglist'  value="" ></select></p>
        
        </div>
        </div>
    </div>
</body>
</html>