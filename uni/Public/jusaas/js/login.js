/*这是公共登录代码，不允许把项目代码写到这里
以mcss_开头的cookie在单击“退出”按钮时会被清除
每个用户名的组织列表也存在cookie中，退出时不会清除
*/
var homeUrl=getHomeUrl();
	
$(document).ready(function(){
	
	$.getJSON(homeUrl+"/Mcss/PublicModel/getConfig",function(cfg)
	{
		if (cfg.mcss_needverifycode)
		{
			$("#verifycode").show();
			$(".loginform").css("margin-top","-20px");
		}		
	})
	if (getCookie("mcss_rememberusername"))
	{
		$("#rememberusername").attr("checked","checked");
		$("#username").val(getCookie("mcss_loginuser"));
		getUserOrglist();
	}
	if(getCookie("mcss_autologin")=="true")
		autologin();
})

var g_fromurl=getParam(location.href,'fromurl');
if (g_fromurl!='')
{
	g_fromurl=g_fromurl.replace(/.xigan./gi,"/");
	if (g_fromurl.indexOf("http")==-1)
	g_fromurl="http://"+g_fromurl;
}

function login_clearCookie()
{
	var cookies=document.cookie.split("; "); 
	var name;
	for(var i=0;i<cookies.length;i++)
	{
		name=cookies[i].split("=")[0];
		if (cookies[i].indexOf("mcss_")==0 && name!='mcss_loginuser')
		{
			setCookie(name,null);
		}
	}
	//退出时清空密码
	setCookie("password",null);
}

function autologin()
{
		if (getCookie("mcss_loginuser") && getCookie("mcss_ckj"))
		{
		$('#username').val(getCookie("mcss_loginuser"));
		$('#password').val(getCookie("mcss_ckj"));
		getLoginuserOrgList();
		$("#orglist").val(getCookie("mcss_orgid"));
		$("#autologin").attr("checked","true");
		login_login("","autologin");
		}
}
function login_setLoginCookie(logininfo)
{

	var time=36000*24;
	if (logininfo.loginuser)
		setCookie("mcss_loginuser",logininfo.loginuser,time);
	if (logininfo.loginuserid)
		setCookie("mcss_loginuserid",logininfo.loginuserid,time);
	if (logininfo.loginuserstaffname)
		setCookie("mcss_loginstaffname",logininfo.loginuserstaffname,time);
	if (logininfo.loginuserstaffid)
		setCookie("mcss_loginstaffid",logininfo.loginuserstaffid,time);
		
	if (logininfo.loginuser_dept_staff_ids)
		setCookie("mcss_loginuser_dept_staff_ids",logininfo.loginuser_dept_staff_ids,time);
	if (logininfo.loginuserrole)
		setCookie("mcss_loginuserrole",logininfo.loginuserrole,time);
	if (logininfo.loginuserroleid)
		setCookie("mcss_loginuserroleid",logininfo.loginuserroleid,time);
	if (logininfo.mcss_theme)
		setCookie("mcss_theme",logininfo.mcss_theme,time);
	if (logininfo.mcss_app)
		setCookie("mcss_app",logininfo.mcss_app,time);
	if (logininfo.mcss_orgid)
		setCookie("mcss_orgid",logininfo.mcss_orgid,time);
	if (logininfo.mcss_saas)
		setCookie("mcss_saas",logininfo.mcss_saas,time);
	if (logininfo.loginuserroleextension)
		setCookie("mcss_loginuser_role_extension",logininfo.loginuserroleextension,time);
	if (logininfo.password)
		setCookie("mcss_ckj",logininfo.password,time);
	if($("#autologin").attr("checked")==true)
	{
		time=36000*1000;
		setCookie("mcss_autologin","true",time);
		setCookie("mcss_ckj",logininfo.password,36000*24);
//刘凯		$.post(homeUrl+"/Home/Index/setsavepassword",{pass:$('#password').val()},function(data){
//			setCookie("mcss_ckj",data,36000*24);
//		})
	}
	if($("#rememberusername").attr("checked"))
	{
		setCookie("mcss_rememberusername","true");
	}
}

function check_orgcode(gotourl,logintype){
	var orgcode = $("#orglist").val();//组织代码
	if(orgcode){
		login_login(gotourl,logintype);
	}
}
//检查表单输入的数据
//gotourl:如果不为空，登录后跳转到该网址
function login_login(gotourl,logintype)
{	
	$("#login_btn").val('正在登录…');
	$("#login_btn").attr("disabled",true);
    var username = $('#username').val();
    if (!username)
    	return;
    var password = $('#password').val();
    var verifyImg = $('#verifytext').val();
	var orgcode = $("#mcss_orgcode").val();//组织代码
	if (!orgcode)
		orgcode="";
	var orgid="";
	if (getCookie("mcss_saas") &&  $("#orglist").size()>0)
	{	
		
		var orgid = document.getElementById('orglist').value;
		/*
		if (!orgid)
		{
			$("#org_tr").show();
			alert("请选择组织！");
			$("#login_btn").val('登录');
			$("#login_btn").attr("disabled",false);
			return;
		}
		*/		
	}
	var urlpath=homeUrl+"/Home/Index/gotoLogin/";
	$.post(urlpath,{username:username,password:password,verifyImg:verifyImg,orgid:orgid,orgcode:orgcode,logintype:logintype},function(msg){
		checkpass(msg,gotourl);
    }); 
}
	

//获得网站跟路径,不包括域名
function getHomeUrl()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	return postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1)+"/index.php";
}
function saveUsername(){
	if($("#rememberusername").attr("checked")=="checked" || $("#rememberusername").attr("checked")==true){
		var username = $("#username").val();
		setCookie('username',username);
		var password=$('#password').val();
		setCookie('password',password);
	}else{
		setCookie('username','');
		setCookie('password','');
	}
}
function checkpass(msg,gotourl){
	if (msg=='1') {
		//saveUsername();
		var urlpath=homeUrl+"/Home/Index/getLoginInfo";
		$.getJSON(urlpath,function(info){
			if (info.length>0)
			{
				login_setLoginCookie(info[0]);
			}
			
			
			if (window.afterLogin)
				afterLogin(homeUrl,g_fromurl,info);//某些项目自定义的在登陆后执行的方法
			else
				jumpurl(homeUrl,g_fromurl,info,gotourl);
			
		}); 
	}
	else
	{
		 if (msg=='0') {
			alert('用户名或密码错误!');
			document.getElementById("password").value='';
			document.getElementById("password").focus();
		} else if (msg=='2') {
			alert('验证码错误！');
		} else if (msg=='3') {
			alert('请选择组织！');
		} else if (msg=='4'){
			alert('此账号未激活,请激活后登录!');
		}else if(msg=='5'){
			alert("没有开通密码登录功能！请输入用户名！");
		}
		$("#login_btn").val('登录');
		$("#login_btn").attr("disabled",false);
	}
	
}


function jumpurl(homeUrl,g_fromurl,info,gotourl)
{
//三个链接的优先顺序是：gotourl>gotourl>mcss_home_url
	if (g_fromurl!='')
	{
		//window.parent.location =g_fromurl;
		window.location =g_fromurl;
		//setTimeout("window.parent.location ='"+g_fromurl+"'",800)
	}
	else
	if (gotourl)
	{
		window.location =gotourl;
	}
	else 
	{
		var app=info[0]["mcss_home_url"];
		var mainurl=homeUrl+"/"+app;
		if (window.parent!=undefined && window.parent!=null) 
		{
			window.parent.location =mainurl; 
		}
		else
		{					
			window.location =mainurl;
		}
	}
}

//回车将出发登录按钮
function gotologin(event,gotourl)
{
	var obj = event.srcElement ? event.srcElement : event.target;
	if (event.keyCode==13){
	
		if (obj.id=='username')
		{	
			if (document.getElementById("password").value=='')
			{
				document.getElementById("password").focus();
			}else{
				if(document.getElementById("username").value)
					login_login(gotourl);
			}
		}else{
			if(!$("#username").val())
			{	
				$("#username").focus();
				setTimeout('login_login('+gotourl+');',2000);
			}else
				login_login(gotourl);
		}		
	}
}

function getParam(url,param_name)
{
	if (url!=null)
	{
		var idx=url.indexOf(param_name);
		if (idx>-1)
		{
			return url.substring(idx+param_name.length+1);
		}
	}
	return "";

}

function freshVerify() {  
 	var u=getHomeUrl()+'/Home/Index/verify';
	document.getElementById('verifyImg').src=u;  
} 

function login_reset()
{
	$('#username').val("");
	$('#password').val("");
	$('#email').val("");
	$('#repassword').val("");
}

function getLoginuserOrgList()
{
	if(getCookie("mcss_saas")){
		getUserOrglist();
	}else{
		var urlpath=homeUrl+"/Home/Index/getLoginInfo";
		$.getJSON(urlpath,function(info){
			if (info.length>0)
			{
				login_setLoginCookie(info[0]);
				if (getCookie("mcss_saas") && $("#orglist").size()>0)
				{
					getUserOrglist();
				}
			}
		})
	}
}

function getUserOrglist(){
	var username = $('#username').val();
	if(!username)
	{
		$.post(homeUrl+"/Home/Index/getUserNameByPwd",{"pwd":$('#password').val()},function(name){
			if (name)
			{
				$('#username').val(name);
				getUserOrglistInfo(name);			
			}
		});
	}else{
		getUserOrglistInfo(username);	
	}	
}
//根据用户名得到组织信息,组织列表存到cookie中
function getUserOrglistInfo(username)
{
	var orgs=getCookie('orglist_'+username);
	if (orgs)
	{
		var arr=orgs.split(">");
		if (arr.length==2 && arr[0]==username && arr[1])
		{
			$("#mcss_orginfo").show();
			addToOrgList(arr[1]);
		}
		else
			reloadOrgListFromServer(username);
	}
	else
	{
		reloadOrgListFromServer(username);
	}
}

//根据用户名从服务器获取跟用户的组织列表
function reloadOrgListFromServer(username)
{
		$.post(homeUrl+"/Home/Index/getUserOrgs",{"user":username},function(orgs){
			if (orgs)
			{
				setCookie('orglist_'+username,username+">"+orgs);
				$("#mcss_orginfo").show();
				addToOrgList(orgs);
			}else
				$("#mcss_orginfo").hide();
		});
}
function addToOrgList(orgs)
{
		var list=orgs.split(",");
		$("#orglist").html(createSelect(orgs)); 
		$("#orgtishi").html('');
		$("#org_tr").show();
		if (list.length>1)
		{
			$("#org_tr").show();
		}
		else if (list.length==1)
		{
			$("#orglist").val(list[0]);
			$("#org_tr").hide();
		}

}
//data:"1:选项1,2:选项2"
//createSelect("1:选项1,2:选项2");
function createSelect(data)
{
	var arr=data;
	if (typeof(data)=="string")
	{
		arr=data.split(",");
	}else return "错误。data参数必须是sring类型！";
	var option="";
	for(i in arr)
	{
		var arr1=arr[i].split(":");
		var v=arr1[0];
		var n=arr1[0];
		if (arr1.length==2)
			n=arr1[1];
		option+="<option value='"+v+"'>"+n+"</option>";
	}
	return option;
}

function logout(gotourl)
{
	//取到所有有关智能选择的cookie
	var cookies=document.cookie.split("; "); 
	var strKey = "",strValue = "";
	for(var i=0;i<cookies.length;i++)
	{
		var cookie=cookies[i].split("=");
		if (cookies[i].indexOf("mcformsmart_")==0)
		{
			if(strKey)
				strKey+='|';
			strKey+=cookie[0];
			
			if(strValue)
				strValue+='|';
			strValue+=getCookie(cookie[0]);
		}
	}
	$.post(g_homeurl+"/Home/Index/saveSmartCookie",{cookiekey:strKey,cookievalue:strValue},function(){
		$.post(g_homeurl+"/Home/Index/logout",function(url){
			if (gotourl)
				url=gotourl;
			login_clearCookie();
			window.location =url;
		})
	})
}