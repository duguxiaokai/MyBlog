/*
几乎所有的tpl目录下html也要都要引用该文件，用来处理初始化代码，提供最常用的方法
不要轻易修改这个文件，影响面非常广
作者：陈坤极
*/
//alert("common.js");

var MCSSTables=new Array;//多个MCSSTable对象管理,用于MCSSTable.js中
var Autoforms=new Array;//多个Autoform对象管理,用于Autoforms.js中
var g_homeurl=getHomeUrl();///index.php路径,不包括域名
var g_domain=getdomain();//域名部分，如 http://localhost:8888/  http://www.baidu.com/
var g_rooturl=getrooturl();//去掉index.php后剩下的路径
var g_systempath="/System/Model";//系统模型处理类的路径，很多地方用到
//获得网站跟路径,不包括域名
function getHomeUrl()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	return postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1)+"/index.php";
}
//获得网站跟路径url
function getrooturl()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	var prePath=strFullPath.substring(0,pos);
	var postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1);
	var r=prePath+postPath;
	if (r.indexOf('index.php')>-1)
	{
		r=r.substr(0,r.length-9);
	}
 	return r;	
}

//获得网站域名
function getdomain()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	return prePath=strFullPath.substring(0,pos);
}

//这是最简单的取得utl中的参数值的方法。url的结构必须是/参数名1/参数值1/参数名2/参数值2/
function getParam(param_name)
{   
	var url=document.location.href;
	if (url)
		url=decodeURI(url);	
	return common_getParamValue(url,param_name);
}

//这是最简单的取得utl中的参数值的方法。url的结构必须是/参数名1/参数值1/参数名2/参数值2/
function common_getParam(param_name)
{   
	var url=document.location.href;
	if (url)
		url=decodeURI(url);	
	return common_getParamValue(url,param_name);
}
function getParamValue(url,param_name)
{
	return common_getParamValue(url,param_name);
}
function common_getParamValue(url,param_name)
{   
	if (url)
	{
		if (url.indexOf("#")==url.length-1)
			url=url.substr(0,url.length-1);	
		var params=url.split("/");
		for(var i=0;i<params.length;i++)
		{   
			if (params[i]==param_name && i<params.length)
				{return params[i+1];}
		}
	}
	return "";

}

/*
获得这类url的指定参数的值：
http://www.test.test/index.php/Order/list_orderproduct_manage/param:id=2&name=陈
这是比较旧的方法，新的应用应该用common_getParam
*/
function  geturlparam(name){
	r = "" ;
	var url = document.location.href;
    args = String(url).split('param:');
	if (name==undefined || name=="") {
		if(args[1]){
			r=args[1];
		}
    }else {
		if (args[1]){
           pp = args[1].split("&");
           for (i=0;i<pp.length;i++){
              var pp3 =pp[i].split("/");
              if (pp3.length >1 && pp3[0] == name ){
                 r = pp3[1];
              }
           }
        }
	}
	return r;
}

function setCookie(name,value,expiredays)//两个参数，一个是cookie的名子，一个是值
{
	if (!expiredays)
		expiredays=24*3600*1000;
    var exp  = new Date(); 
    exp.setTime(exp.getTime() + expiredays*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+";path=/";
}
function getCookie(name)//取cookies函数
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
     if(arr != null) return unescape(arr[2]); return null;

}
function delCookie(name)//删除cookie
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

function GE(a){return document.getElementById(a);}



String.prototype.trim=function(){
return trim(this);
}

function trim(str)
{
    //去掉空字符
	if (!str)
		return str;
	var r="";	
	var code=0;
	var i=0;
	if (!str[0])
	{
		str=str.substr(3);
		return str;
	}

	while(i<str.length)
	{
		if (str[i])
		{
			code=str[i].charCodeAt();
			if (code!=239 && code!=187 && code!=191 && code!=65279)
				break;
		}
		i++;
	}
	var r= str.substr(i);
	return r;	
}
function getUTF8Str(str)
{
	return trim(str);
}

 
//这个方法不应该放在这里，不知道有被调用
function getMoney(money){
	//过滤数字
	money=money.replace(/\b(0+)/gi,"");
	money=money/100;
	return money;
}

var t1 = new Date().getTime();
window.onload = function()
{
 windowonload();
}

function windowonload() 
{ 
	//显示“正则加载”的动画效果
	var t2=new Date().getTime()-t1; 
	setTimeout(function(){
		var ajaxbg=$("#background,#progressBar");		
		ajaxbg.hide();
		$(document).ajaxStart(function(){
		ajaxbg.show();
		}).ajaxStop(function(){
		ajaxbg.hide();});},t2);
	
	createPosition();//当前位置，不提倡用
	createPagebar();//创建打开新窗口标记
	
}

//当前位置，不提倡用
function createPosition()
{
	if ($("#positionCon").size()>0)
	{
		var texts=$("#menuHight > ul > li",parent.document).children("a.youhighclass").text();
		var ids=$("#menuHight > ul > li",parent.document).children("a.youhighclass").attr('id');
		var texts2=$("#menuHight > ul > li",parent.document).children("ul").children("li").children("a.youhighclass").text();
		var ids2=$("#menuHight > ul > li",parent.document).children("ul").children("li").children("a.youhighclass").attr('id');
		if(texts!=""){
		var content="当前位置：<a id='/jusaas/index.php/Openurl/openurl/landz_gongzuotai' onclick='javascript:window.parent.openiframe(this);' href='javascript:void(0)'>工作台</a>&nbsp;&gt;&nbsp;<a id=\""+ids+"\" onclick='javascript:window.parent.openiframe(this);' href='javascript:void(0)'>"+texts+"</a>";}
		if(texts2!=""){
		var contents="当前位置：<a id='/jusaas/index.php/Openurl/openurl/landz_gongzuotai' onclick='javascript:window.parent.openiframe(this);' href='javascript:void(0)'>工作台</a>&nbsp;&gt;&nbsp;<a id=\""+ids+"\" onclick='javascript:window.parent.openiframe(this);' href='javascript:void(0)'>"+texts+"</a>&nbsp;&gt;&nbsp;<a id=\""+ids+"\" onclick='javascript:window.parent.openiframe(this);' href='javascript:void(0)'>"+texts2+"</a>";}
		var heightdiv="<div style='height:26px; width:100%;clear:both'></div>"
		if (contents)
			$("#positionCon").html(contents);$("#positionCon").after(heightdiv);		 
	}
}

//创建页面顶部栏
function createPagebar()
{
$("#mcss_pagebar").css("float","right");
$("#mcss_pagebar").html("<a href='#' onclick='window.open(window.location)' title='在新窗口打开本页面'><img src='"+getrooturl()+"/Public/themes/default/images/newwindow.png' /></a>");
}

var g_dinamic_cssjs=new Array();//动态加载的css和js文件都记录在这里避免重复加载
//下面默认导出的文件要根据项目情况修改，对于项目管理，下列文件已经在项目文件中加载，因此标注已加载，避免重复自动加载

//动态加载js文件
function mcss_importJS(filename)
{
	for(var i=0;i<g_dinamic_cssjs.length;i++)
	{
		if (g_dinamic_cssjs[i]==filename)
		{
			return; 
		}
	}
	g_dinamic_cssjs.push(filename);

	var oHead = document.getElementsByTagName('HEAD').item(0);
	var oScript= document.createElement("script");
	oScript.type = "text/javascript";
	oScript.id=filename;
	var js=getrooturl()+"/Public/"+filename;
	oScript.src=js;
	oHead.appendChild( oScript);
}

var theme=getCookie("mcss_theme");
if (!theme || theme!='null')
	theme='default';

if(theme!='null'){
	mcss_importCss("themes/"+theme+"/css/common.css");//所有引用common.js的页面都加载common.css样式文件
	mcss_importJS("themes/"+theme+"/js/theme.js");//所有引用common.js的页面都加载common.css样式文件
}
var mcss_lang=getCookie("mcss_lang");
if(!mcss_lang || mcss_lang=='null')
	mcss_importJS("lang/cn/language.js");
else
	mcss_importJS("lang/"+mcss_lang+"/language.js");


//动态加载css文件
function mcss_importCss(filename)
{
	for(var i=0;i<g_dinamic_cssjs.length;i++)
	{
		if (g_dinamic_cssjs[i]==filename)
		{
			return; 
		}
	}
	g_dinamic_cssjs.push(filename);

	var js=getrooturl()+"/Public/"+filename;
	var fileref=document.createElement("link") 
	fileref.setAttribute("rel", "stylesheet") 
	fileref.setAttribute("type", "text/css")  
	fileref.setAttribute("href", js);
	if (typeof(fileref)!="undefined") 
		document.getElementsByTagName("head")[0].appendChild(fileref);
}
//检查后台返回的结果是否错误信心并处理
function common_getAccessError(data,params)
{
	if (data.length==1)
	{
		if (data[0].errcode=='loginexpire')
		{
			var url=document.location.href.replace(/\//g,'.xigan.');
			var errstr="登录已过期，请<a href='"+g_homeurl+"/Home/Index/login/fromurl/"+url+"'><span style='color:blue'>重新登录</span></a>";
			var returnstr="无法获取数据";
			mcss_importJS("jusaas/js/dom.js");
			
			if (params && params.gotologin==true)
			{
				window.location =g_homeurl+"/Home/Index/login/fromurl/"+url;
				return "";
			}
			else
			{
				//先尝试重新登录，如果通不过则提示重新登录
				var urlpath=g_homeurl+"/Home/Index/gotoLogin/";
				var username=getCookie("mcss_loginuser");
				var password=getCookie("mcss_ckj");
				if (!password || password=='null')
				{
					if(!$("#popup_fuceng_Box").html())
						mcpopup=mcdom.alert(errstr,"无法访问",'fail');	
					return returnstr;				
				}
				var orgid=getCookie("mcss_orgid");
				var orgcode=getCookie("mcss_app");
				$.post(urlpath,{username:username,password:password,orgid:orgid,orgcode:orgcode,logintype:'autologin'},function(msg){
					if (msg=='1')
						window.location.href=document.location.href;
					else
					{
						//登录过期设计：一个页面有时显示多个“登录过期”，体验不好。可以改为：把原来的显示“登录过期”的字样改为“无法获取数据”。另外在屏幕中央弹出一个带关闭按钮的小浮层，显示原来显示的“登录过期，请重新登录”的字样。当前页面如果已经显示了这个浮层，则不要再显示了
						if(!$("#popup_fuceng_Box").html())
							mcpopup=mcdom.showPopup(obj,errstr,null,null,null,320,360,"信息");	
						return returnstr;						
					}
			    });				
			}
		}
		else
		if (data[0].errcode)
		{
			return data[0].err;
		}
	}
	return "";
}

