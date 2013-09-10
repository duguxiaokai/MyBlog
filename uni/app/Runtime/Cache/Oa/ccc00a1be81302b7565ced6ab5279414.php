<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//CN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="qc:admins" content="7233223307653331676375" />
<title>聚亿企SMESing赢盘软件--企业的平台，员工的舞台！</title>
<link href="__PUBLIC__/themes/default/css/login.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/themes/default/images/favicon.ico" rel="shortcut icon" />
<script src="__PUBLIC__/themes/default/js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/jusaas/js/common.js"></script>
<script src='__PUBLIC__/js/popup.js'></script>
<script src='__PUBLIC__/jusaas/js/login.js'></script>

<script type="text/javascript">	

$(function() {
	$("#username").focus();
	var sWidth = $("#focus").width(); //获取焦点图的宽度（显示面积）
	var len = $("#focus ul li").length; //获取焦点图个数
	var index = 0;
	var picTimer;
	
	//以下代码添加数字按钮和按钮后的半透明条，还有上一页、下一页两个按钮
	var btn = "<div class='btn_dian'>";
	for(var i=0; i < len; i++) {
		btn += "<span></span>";
	}
	btn += "</div>";
	$("#focus").append(btn);
	//$("#focus .btnBg").css("opacity",0.5);

	//为小按钮添加鼠标滑入事件，以显示相应的内容
	$("#focus .btn_dian span").mouseenter(function() {
		index = $("#focus .btn_dian span").index(this);
		showPics(index);
	}).eq(0).trigger("mouseenter");

	//本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
	$("#focus ul").css("width",sWidth * (len));
	
	//鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
	$("#focus").hover(function() {
		clearInterval(picTimer);
	},function() {
		picTimer = setInterval(function() {
			showPics(index);
			index++;
			if(index == len) {index = 0;}
		},4000); //此4000代表自动播放的间隔，单位：毫秒
	}).trigger("mouseleave");
	
	//显示图片函数，根据接收的index值显示相应的内容
	function showPics(index) { //普通切换
		var nowLeft = -index*sWidth; //根据index值计算ul元素的left值
		$("#focus ul").stop(true,false).animate({"left":nowLeft},300); //通过animate()调整ul元素滚动到计算出的position
		//$("#focus .btn_dian span").removeClass("on").eq(index).addClass("on"); //为当前的按钮切换到选中的效果
		$("#focus .btn_dian span").stop(true,false).removeClass("on").eq(index).stop(true,false).addClass("on"); //为当前的按钮切换到选中的效果
	}
});

</script>

</head>
<body class="loginbg">
<!--头部 start-->
    <div class="loginTopbg">
    	<div class="loginTopCon">
        	<div class="maginLogintop">
            <img src="__PUBLIC__/themes/default/images/logo.png" width="291" height="45" class="loginlogo" alt="聚亿企Mcssing赢盘软件--企业的平台，员工的舞台！"/>
            <div class="loginsysHelp">
            	<a href="shortcuts.php" class="sethome tipsimple" title="设为桌面快捷方式"></a>
            </div>
            </div>
        </div>
    </div>
    <!--中间 start-->
    <div class="loginMid">
    	<!--图片轮播 start-->
        <div class="loginImage">
        	<div id="focus">
        	<ul>
			<li><img src="__PUBLIC__/themes/default/images/loginimage1.jpg" width="644" height="489"/></li>
			<li><img src="__PUBLIC__/themes/default/images/loginimage2.jpg" width="644" height="489"/></li>
			<li><img src="__PUBLIC__/themes/default/images/loginimage3.jpg" width="644" height="489"/></li>
			</ul>
            </div>
        </div>
        <!--图片轮播 End-->
        <!--登陆框 start-->
        <div class="loginBox">
        	<div class="logintitle">请登录</div>
            <p>用户名：</p>
            <input type="text" class="inputStyles login_user" name="username" id="username" style="ime-mode:disabled " onKeyup="gotologin(event);"  onblur="getLoginuserOrgList();if (value ==''){value='请输入用户名或邮箱'}" value="请输入用户名或邮箱" onfocus="if (value =='请输入用户名或邮箱'){value =''}" />
            <p>密&nbsp;&nbsp;码：</p>
            <input type="password" class="inputStyles login_pwd" id="password" name="password" onKeyup="gotologin(event);" onblur="getLoginuserOrgList();if (value ==''){value='请输入密码'}" value="请输入密码" onfocus="if (value =='请输入密码'){value =''}" />
			<p>
			<div id='mcss_orginfo'>
			<p><span class="inputName">组&nbsp;&nbsp;织：</span></p><select id='orglist'  value="" style='height:30px; width:255px; margin-top:10px;'></select></p>
			</div>
             <div class="remember" id="chklist" >
             <!--input type="checkbox" id="rememberusername" /><label style="float:left; margin-right:100px">记住用户名</label-->
              <span style="float:left; margin-right:50px; font-family:'微软雅黑';" id='checkboxspan'>
			 <input type="checkbox" id="autologin" style="vertical-align:middle"/><label for="autologin"> 记住我(公共场所请勿勾选)</label></span>
			 <!--<span id="qqLoginBtn"></span>-->
			 <script type="text/javascript">
				/*var cbLoginFun = function(){};
				QC.Login.signOut();
				QC.Login({btnId:"qqLoginBtn"},function(oInfo,oOpts){
					QC.Login.getMe(function(openId,accessToken){
						alert(123);
						 $.post("__APP__/Home/Index/getUserByOid",{openId:openId},function(data){
							  var arr = data.split('|');
							  if(arr[0]>0){
								 $("#username").val(arr[1]);
								 $("#password").val(arr[2]);
								 $("#username").blur();
								setInterval("check_orgcode('','qq')",50);
							  }else{
								 var nickname = oInfo.nickname;
								 var h = "<div class='WorksMenu'><ul><li class='nav'>快速注册</li><li>绑定帐号</li></ul></div>"+
								 "<div class='WorksContent'><div id='mcss0' class='xxinfo'>新帐号:<input id='myusername'> <br> 密码:<input id='mypass' /><br> <input value='登录' type='button' onclick='createUser(\""+openId+"\",\""+nickname+"\")'></div><div id='mcss1' class='xxinfo' style='display:none'>已有帐号:<input id='hasusername'><br>密码:<input type='password' id='haspass' /><br><input type='button' onclick='clockUser(\""+openId+"\")' value='绑定并登录'></div></div>";
								 ShowHtmlString(h,"QQ登录");
								 $("#myusername").val('qq'+Math.round(Math.random()*1000000000));
								 $("#mypass").val('123');
								 $(".WorksMenu ul li").click(function()
								{
									$(this).addClass("nav").siblings().removeClass("nav");
									var index=$(".WorksMenu ul li").index($(this));
									$(".WorksContent > .xxinfo").eq(index).show().siblings().hide();
								})
							  }
						 });
					});
				});
					//创建用户
					function createUser(openId,nickname){
						var username = $("#myusername").val();
						var pass = $("#mypass").val();
						if(!username){
							alert('请输入用户名');return;
						}else if(!pass){
							alert('请输入密码');return;
						}
						$.post("__APP__/Home/Index/createUserByOid",{openId:openId,username:username,psw:pass,nickname:nickname},function(data){
							if(data==1){
								alert('您的用户名已经有人注册了,请重新填写');return;
							}
							var arr = data.split('|');
							 $("#username").val(arr[0]);
							 $("#password").val(arr[1]);
							 $("#username").blur();
							 setInterval("check_orgcode('','qq')",50);
						})
					}
					$.post("__APP__/Home/Index/createUserByOid",{openId:openId,username:username,psw:pass,nickname:nickname},function(data){
						if(data==1){
							alert('您的用户名已经有人注册了,请重新填写');return;
						}
						var arr = data.split('|');
						 $("#username").val(arr[0]);
						 $("#password").val(arr[1]);
						 setInterval("check_orgcode('','qq')",50);
					})
				}
				function clockUser(openId){
					var username = $("#hasusername").val();
					var pass = $("#haspass").val();
					$.post("__APP__/Home/Index/clockUser",{openId:openId,username:username,psw:pass},function(data){
						if(data==1){
							alert('请确定用户名是否正确或密码是否正确!');return;
						}
						var arr = data.split('|');
						 $("#username").val(arr[0]);
						 $("#password").val(arr[1]);
						 setInterval("check_orgcode('','qq')",50);
					})
				}*/
			</script>
			<a id='forgetPassword' target="_blank" class="wangjimima" href="__APP__/Home/Index/forgetPassword">忘记密码</a>   	
             </div>

            <!--<span class="checkbox" id='checkboxspan'><input type="checkbox" id="rememberusername"/>记住用户名</span>-->
			

            <div class="loginbutton"><input type="hidden" name="cookietime" value="0" />
	    <input type="hidden" name="forward" value="?">
	   <input type="submit" value="登录" class="submitLogin" onClick="login_login()" id="login_btn"/></div>
        </div>
    </div>
    <!--底部 start-->
    <div class="loginFoot">
    	<img src="__PUBLIC__/themes/default/images/login_footcopyright.png" />
    </div>
    <!--底部 End-->
</body>
</html>