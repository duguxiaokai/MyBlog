<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<link rel='stylesheet' type='text/css' href='__PUBLIC__/projects/home/css/global.css'>
<link rel='stylesheet' type='text/css' href='__PUBLIC__/projects/home/css/login.css'>
<title>找回密码</title>
<script type="text/javascript" src='__PUBLIC__/js/jquery.js' ></script>
<script type="text/javascript" src='__PUBLIC__/jusaas/js/common.js'></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/dom.js" ></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/login.js" ></script>
<script language="JavaScript">
function register(e)
{
	var emailcheck=$("#email").val();
	var name=$("#user").val();
	if($("#checkuser").attr("checked") && !name){
		alert('请输入您的用户名!');return;
	}else if($("#checkemail").attr("checked") && !emailcheck){
		alert('请输入您的邮箱!');return;
	}else if(!$("#checkuser").attr("checked") && !$("#checkemail").attr("checked")){
		alert("请选择一个选项输入!");return;
	}
	var url="";
	if(name && $("#checkuser").attr("checked")==true)
		url="__APP__/Home/Index/checkedname/user/"+name;
	else if(emailcheck && $("#checkemail").attr("checked")==true)
		url="__APP__/Home/Index/checkedemail/email/"+emailcheck;
	$.post(url,function(email){
		if(email == '0')
			alert('帐号不存在!');
		else
			sendemail(email,e);
	});
}
function sendemail(email,e)
{
	var zj=/^\w+([-\.]\w+)*@\w+([\.-]\w+)*\.\w{2,4}$/; 
	if(zj.test(email)){	
		$.post("__APP__/Home/Index/password_send_email_echo",{'email':email},function(ee){
			if(ee == 0){
				alert('邮件发送失败,请重新发送!');
			}else if(ee == 1){
				alert('邮件已发送到您的注册邮箱，请立即查收并修改您的密码！');
			}
		});
	}else{
		alert('邮箱格式不正确！');
	}
}
function check(obj)
{
	$(".findpwdinput").attr("readonly",true);
	$(obj).children(":text").attr("readonly",false);
	$(obj).children(":radio").attr("checked",true);
}
</script>
</head>
<body class="loginbg">
<!--头部 start-->
   <!--中间 start-->
    <div class="forgetloginMid">
    	<div class="logintitle">找回密码</div>
        <h3>忘记了密码？您可以通过您的帐号或者Email重新获取密码。</h3>
        <div class="forgetLoginbox">
			 <p onclick="check(this)"><input type="radio" name="repsw" id="checkuser" /><span>　用户名：</span><input name='user' id='user' type="text" class='inputStyles'/>请输入账号找回密码</p>
			
			<p onclick="check(this)"><input type="radio" name="repsw" id="checkemail" /><span>注册邮箱：</span><input type='text' name='email' id='email' class='inputStyles'/>请输入正确的邮箱获取密码</p>
			<p style="padding-left:65px;"><input type="button" value=' '  onClick='register(this)' class='tijiaobutton'/><input type="button" value=' ' onClick="window.history.back()" class='quxiaobutton'/></p> 
        </div>
    </div>
    <!--底部 start
    <div class="loginFoot">
    	© Copyright 2012 Infoteck.cn - All rights reserved. Design and code by Infoteck Team
    </div>
   底部 End-->
</body>
</html>