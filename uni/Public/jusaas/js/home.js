/*
登录后的首页框架公共代码
*/

//个人设置
function home_mysetting()
{
   	$("#mainiframe").attr("src",getHomeUrl()+"/Home/Index/login_pwd");
}

//获得网站跟路径,不包括域名
function getHomeUrl()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	return postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1)+"/index.php";
}

//这个好像没什么作用
function home_checkLogin()
{
	var urlpath=getHomeUrl()+"/Home/Index/checkLogin";	

	$.ajax({
	   type: "POST",
	   url: urlpath,
    	success:function(result)
	   {
	   	if (result=="")
		{
			window.location =".";
		}
	   }			   
	}); 
}

//自动调整iframe的宽度
function openNew(a) {
  if (a.checked && iframeurl!="")
  {
	window.open(iframeurl);
  }
}

$(document).ready(function()
{	
	if (document.getElementById("mcss_menu"))
	{
		menu_createMenu("mcss_menu");
	}
	if (document.getElementById("McssMenu")){
		first_createMenu("McssMenu");
	}
	if (document.getElementById("tongxunlu")){
		createTongxunlu();
	}
	
	
});

var hideheader=false;
var iframeurl="";
function openiframe(a)
{
	iframeurl=a.id;
	setCookie("mcss_iframeurl",iframeurl);
	setCookie("iframeurl",iframeurl);//这个以后不用，保留是为了兼容以前的
	if ($("#hideheader").attr("checked")==true)
	{
		window.open(a.id);
	}
	else
	{
	$("#mainiframe").attr("src",a.id);
	}
}



var tongxunlu;//组织结构树
//生成通讯录
function createTongxunlu()
{
 	$("#tongxunlu").empty();
 	var url=getrooturl()+"/index.php/Mcss/Model/getTree";
 	$.getJSON(url,{table:"sys_deptstaff_bydeptpositionstff_v",id:"id",pid:"parentid",name:"name",type:"type",otherfield:"objid",filter:'orgid=orgid()'},function(data){
		var orgdata=getTreeJSON(data,'DEPT_0','id','parentid');
		tongxunlu=new MCTree("tongxunlu",orgdata,{mouseover:opencard,mouseleave:closecard,field_pid:"parentid",srcData:data,loadstyle:'loadallshowone'});
		tongxunlu.run();

	})
}
//鼠标滑过人员出现人员名片夹
function opencard(obj)
{
	$("ul.line li span").removeClass("ztreeHover");
	$("#staffcard").remove();
	
	obj.className='ztreeHover';
	var left=obj.offsetLeft+obj.offsetWidth-4;
	var top=obj.offsetTop-12;
	var condition = obj.id.split('_');
	if(condition[0]=='STAF'){
		var url= getrooturl()+"/index.php/Mcss/Model/getStaffByNodeid";
		$.getJSON(url,{id:condition[1]},function(data){
			var phtotUrl=getrooturl()+"/Public/uploadfile/"+data[0]['photos'];
			if(!data[0]['photos']){
				phtotUrl=getrooturl()+"/Public/themes/default/images/staffPhoto.jpg";
				}
			if(!data[0]['mobile']){
				mobile="手机未填写";
				}else{
				mobile=data[0]['mobile'];
			}
			if(!data[0]['email']){
				email="邮箱未填写";
				}else{
				email=data[0]['email'];
			}
			var h = "<div id='staffcard' class='staffcard_box' style='left:"+left+"px;top:"+top+"px;' onmouseleave='closecard(this)'><div class='starrInfos'><span class='staffphoto'><img src='"+phtotUrl+"' width='59px' height='60px'/></span><div class='infoRight'><p><span>"+data[0]['name']+"</span>&nbsp;&nbsp;&nbsp;"+data[0]['post']+"</p><p><span>"+data[0]['deptid']+"</span></p><p style='height:25px;'><a href='javascript:void(0)' class='icohovershow' title='给他发短消息'><img src='"+getrooturl()+"/Public/themes/default/images/message.gif'/></a> <a href='javascript:void(0)' class='icohovershow' title='查看它的工作日志' onclick='showcalendar(\""+data[0]['username']+"\","+condition[1]+")'><img src='"+getrooturl()+"/Public/themes/default/images/worksList.gif'/></a></p></div></div>";
			h+="<div style='clear:both'><img src='"+getrooturl()+"/Public/themes/default/images/telphoneico.gif'/><a id='staff_mobile' href='javascript:sendSMS("+data[0]['id']+",\""+mobile+"\")'>"+mobile+"</a><img src='"+getrooturl()+"/Public/themes/default/images/emailico.gif'/><a id='staff_mail' href='javascript:sendMail("+data[0]['id']+",\""+email+"\")'>"+email+"</a></div></div>";
		$("#"+obj.id).after(h);
		
		})
	}
}

//查看日历的按钮
function showcalendar(username,id)
{	
	var url = getrooturl()+"/index.php/Oa/Index/getroleid";
	$.post(url,{username:username},function(data)
	{
		if(getCookie('mcss_loginuserroleid') == data)
		{
			var iframeurl=getrooturl()+"/index.php/Oa/Task/mycalendar/username/"+username+"/executerid/"+id;
			$("#mainiframe").attr("src",iframeurl);
		}else if(getCookie('mcss_loginuser') == 'admin')
		{
			var iframeurl=getrooturl()+"/index.php/Oa/Task/mycalendar/username/"+username+"/executerid/"+id;
			$("#mainiframe").attr("src",iframeurl);
		}else
		{
			alert('没有权限');
		}
	});
}

//发送邮件的按钮
function sendMail(cid,mail)
{
	var iframeurl=getrooturl()+"/index.php/Mcss/Model/sendMail/email/"+mail+"/cid/"+cid;
	$("#mainiframe").attr("src",iframeurl);
}
//发送短信的按钮
function sendSMS(cid,mobile)
{
	var iframeurl=getrooturl()+"/index.php/Mcss/Model/sendSMS/mobile/"+mobile+"/cid/"+cid;
	$("#mainiframe").attr("src",iframeurl);
}

//离开名片夹时关闭
function closecard(obj)
{
	$("#staffcard").remove();
	//$(obj).remove();
}
//搜索通讯录
function searchInOrg()
{
	var s=$("#searchOrg").val();
	tongxunlu.searchNode(s);
}
//搜索通讯录
function gotoSearchOrg()
{
	if (event.keyCode==13){
		searchInOrg();
	}
}
function onFocusSearchtext()
{
	var s=$("#searchOrg").val();
	if (s=="搜索员工")
		$("#searchOrg").val("");
}
