<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>聚亿企Mcssing赢盘软件--企业的平台，员工的舞台！</title>
<link href="__PUBLIC__/themes/default/css/global.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/themes/default/css/tipstyle.css" rel="stylesheet" type="text/css">
<link href='__PUBLIC__/plugins/poshytip/tip-yellowsimple/tip-yellowsimple.css' rel='stylesheet' type='text/css'/>
<script src="__PUBLIC__/themes/default/js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/themes/default/js/css.js" ></script>
<script src="__PUBLIC__/jusaas/js/common.js" ></script>
<script src="__PUBLIC__/jusaas/js/home.js" ></script>
<script src="__PUBLIC__/jusaas/js/menu.js" ></script>
<script src="__PUBLIC__/jusaas/js/dom.js"></script>
<script src="__PUBLIC__/jusaas/js/login.js"></script>
<script src='__PUBLIC__/js/popup.js'></script>
<script src="__PUBLIC__/jusaas/js/autoform.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/MCSSTable.js"></script>
<script src="__PUBLIC__/jusaas/mctree/MCTree.js"></script>
<script type="text/javascript" src='__PUBLIC__/plugins/poshytip/jquery.poshytip.js'></script>
<script type="text/javascript" src="__PUBLIC__/plugins/uiscroller/jquery.nanoscroller.js"></script>
</head>
<body class="indexbg" onload="on_resize()" style="overflow-y:hidden">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td style="width:250px" valign="top" id="leftTd">
		<!--左侧导航功能区 Start-->
		<div class="leftBox" id="bodyLeft">
			<!--主导航区 Start-->
			<div class="firstMenu">
				<!--<a id='menuviewstyle' title="菜单显示模式:图标" onclick="imgViewStyle()" class='edit_userInfo' href='javascript:void(0)'> </a><br />-->			
			<ul>
			<li class="firstMenu_borderbg"><a id="mj_setting" onclick='openDesktop();' title="工作台" class="tipsimple"><span class="menuIconsbg gongzuotaiico"></span></a></li>
			<span id="mcss_top_menu"></span>
			</ul>
			<ul class="fixbottom">
			<li><a href='javascript:' onclick="outprompt();" title="安全退出" class="tipsimple"><span class="menuIconsbg exitico"></span></a></li>
			</ul>
			</div>
			<!--主导航区 End-->
			<!--次导航区域 Start-->
			<div class="secondMenu">
				<div class="sysLogo">
					<img src="__PUBLIC__/themes/default/images/Logo.jpg" width="207" height="58" alt="MCSS" />
				</div>
				<div id='desktop' class="workpad" style="display:none">
					<div class="managerhead">
					<img src="__PUBLIC__/themes/default/images/managerbg.jpg" width="66px" height="69px" class="headpic"  onerror='changeDefault(this)'/>
					<span class="managernameinfo">
						<?php echo ($_SESSION['loginuser']); ?>
						<a title="修改个人信息" onclick="editUser()" class='edit_userInfo'> </a>
						<textarea class="xinqing" readonly='readonly' placeholder="每日心情"></textarea>
					</span>
					</div>
					<!--快捷菜单 End-->
					<!--昨日之最 Start-->
					<div class="kuaijieMenu daymost">
						<h3><span>昨日之最</span></h3>
						<ul>
							<li>最早来的人：<?php echo ($zuizao); ?></li>
							<li>工作最长的人：<?php echo ($zuichang); ?></li>
							<li>我昨日工作多久：<?php echo ($duojiu); ?></li>
						</ul>
					</div>
					<!--快捷菜单 Start-->
					<div class="kuaijieMenu" id="kuaijieMenu">
						<h3><span>快捷菜单</span>
						<a href="#" class="kuaijie_btn" onclick="showcollect(this)" title="收藏当前页面"></a>
						</h3>
						<ul id='shoucang' ></ul>
					</div>
					<!--昨日之最 End-->
					<div class="kuaijieIco">
						<ul>
							<li><a href="#" class="shortcutbg noteico"  onclick="shownote()"  title="笔记本"></a>笔记</li>
							<li><a href="#" class="shortcutbg dakaico" onclick="markattendance(this)" title="考勤打卡"></a>打卡</li>
							<li>
							<a href="#" class="shortcutbg wenjianico" onclick="openMenuMy('Oa/File/file','文档共享','work_share')" title="文件中心"></a>文件
							</li>
							<li><a href="#" class="shortcutbg baoxiaoico"  title="报销"></a>报销</li>
							<li><a href="#" class="shortcutbg qingjiaico" onclick="openMenuMy('Oa/Other/qingjia','请假','work_qingjia')"  title="请假"></a>请假</li>
						</ul>
					</div>
					<!--快捷菜单 End-->
				</div>
				<div class="menuList" style="display:none">
					<div class='nano has-scrollbar'>
						<div class='secondMenuConned content'>
						<div id='mcss_secondmenu'></div>
						</div>
					</div>
				</div>
			</div>
			<!--次导航区域 End-->
		</div>
	<!--左侧导航功能区 end-->
	</td>
	<td width="5px">
		<span class="showbutton" id='showhideleftbar'> </span>
	</td>
	<td valign="top" id="bodyRight">
		<div class='RightTop'>
			<!--管理全景 start-->
			<div class="managerAll">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td>
					<span id='alert720' class="managerbutton"></span>
					<div class="managerConbg">
						<ul>
						<li onclick="openMenuMy('Oa/Task/mycalendar','我的日历','oa_calendar_my')" id='1' >日工作 <span><?php echo ($daywork); ?></span>
						<div class="Manager_dialog">
							<table cellpadding="0" cellspacing="0" border="none" class="mcsstable" id="mcss_table1" width="100%"></table>
						</div>
						</li>
						<li id='2' >周工作 <span></span>
						<div class="Manager_dialog">
							<table cellpadding="0" cellspacing="0" border="none" class="mcsstable" id="mcss_table2" width="100%"></table>
						</div>
						</li>
						<!--
						<li>协作 <span>3</span></li>
						<li>邮件 <span>3</span></li>
						<li>短消息 <span>30</span></li>
						-->
						<li onclick="openMenuMy('Home/Index/openMenuUrl/func_code/mj_media_all','我的项目','oa_project_my')" id='3' >项目 <span><?php echo ($myproject); ?></span>
						<div class="Manager_dialog">
							<table cellpadding="0" cellspacing="0" border="none" class="mcsstable" id="mcss_table3" width="100%"></table>
						</div>
						</li>
						<li onclick="openMenuMy('Biz/Business/businesslist/modelid/biz_opportunity_my','我的商机','biz_opportunity_my')" id='4' >商机 <span><?php echo ($bizopportunity); ?></span>
						<div class="Manager_dialog">
							<table cellpadding="0" cellspacing="0" border="none" class="mcsstable" id="mcss_table4" width="100%"></table>
						</div>
						</li>
						<li id='5'>消息 <span><?php echo ($message); ?></span>
						<div class="Manager_dialog">
							<table cellpadding="0" cellspacing="0" border="none" class="mcsstable" id="mcss_table5" width="100%"></table>
						</div>
						</li>
						</ul>
					</div>
					</td>
					<td width="117px">
					<span class="menuListall"></span>
					</td>
				</tr>
				</table>
			</div>
			<!--管理全景 End-->
		</div>
		<div class="topHideShow"><a title="收起全景管理菜单" class="clickTop"></a></div>
		<!--主要内容 Start-->
		<div class="MainContentpadding">
			<div class="MainContent">
                <div class="subTab_Con">
					<div id="subwinheader" class="subTab"></div>
					<span class="menu_huadong_left"></span>
					<span class="menu_huadong_right"></span>	
				</div>
				<div class="tableCon">
				<div id='subwindow' style="background-color:#FFF;width:100%;"></div>
				</div>
			</div>
		</div>
	</td>
</tr>
</table>
<!--底部区域 start-->
 	<div class="footBox">
    <img src="__PUBLIC__/themes/default/images/footcopyright.png"/>
    </div> 
<!--底部区域 end-->
<div class='browser_style'></div>
<script type="text/javascript">
var id;//存储iframe的id
var mcpopup;
var mcssTable1,mcssTable2,mcssTable3,mcssTable4,mcssTable5
$(document).ready(function()
{
	getmailinfo();
	loadData();
	on_resize();//页面加载时执行
	getAllMenu();
	var iframeurl =getCookie("mcss_iframeurl");
	var app=getCookie("mcss_app");
	if (iframeurl==undefined || iframeurl=='null' || iframeurl=="") 
		openDesktop();
	else
		openDefaultPage(iframeurl);	
	loadShortcut(); 
	//第一次登录弹出打卡
	var staffname=getCookie('mcss_loginstaffname');
	if (staffname && staffname!="null")
	{
		var url = "__APP__/Oa/Index/needDaka";
			$.post(url,function(data){
			if(data==1)
			{
				//markattendance(this);
			}
		});
	}
	getUserImg();
	getUserPlan();
	getLogo();
	//720度 点击展开
	$(".managerbutton").toggle(function(){
		$('.managerConbg').css({"padding-left":"0",overflow:"hidden"}).animate({width:"0px"},300);
	},function(){
		var iwidht=($('.managerConbg ul li').width()+1)*($('.managerConbg ul li').size());
		$('.managerConbg').css({"padding-left":'10px',"overflow":"visible"}).animate({width:iwidht+"px"},300);
	});
	$('.managerConbg ul li').hover(function(){
		$(this).children(".Manager_dialog").show();
	},function(){
		$(this).children(".Manager_dialog").hide();
	});
	
	//验证浏览器版本
	var browser_styleLeft=($('body').width()-$('.browser_style').width())/2;
	if($.browser.msie){
        var i=$.browser.version;
		if(i<8){
		var browserHtml="你的浏览器版本过低：IE"+i+",建议升级浏览器或使用其他浏览器（谷歌、火狐）进行操作，效果更佳！<a href='#' class='ikonw'>我知道了</a>";
		$(".browser_style").css('left',browser_styleLeft+'px').animate({top:"0px"}).html(browserHtml);
		$(".ikonw").click(function(){
			$(this).parent().animate({top:"-32px"});
		});
		}
	}
	
	setTimeout("hideAlert720();",10000);
	
});

//隐藏720度
function hideAlert720()
{
$("#alert720").click();
}


//隐藏左侧
function showhideLeftBar()
{
$("#showhideleftbar").click();
}

function loadData(){
	var executerid=getCookie("mcss_loginstaffid");
	var d = new Date()
	var vYear = d.getFullYear()
	var vMon = d.getMonth() + 1
	if(vMon<10)
		vMon="0"+vMon;
	var vDay = d.getDate();
	if(vDay<10)
		vDay="0"+vDay;
	var day=vYear+"-"+vMon+"-"+vDay;
	var filter0="begindate='"+day+"' and enddate='"+day+"' and executerid='"+executerid+"' and tag=2 and cat='日报'";
	var field_dealing=[{'name':showname,'notes':shownotes}];
	mcssTable1=new MCSSTable({tableid:"mcss_table1",filter:filter0,modelid:"oa_task_day_list",defaultValue:"begindate:"+day+",enddate:"+day,special_field_show:field_dealing,useActionIcon:false,showSaveAndAddButton:true,pagerows:20,toolbar:""});
	mcssTable1.run();
	var cha=d.getDate()-d.getDay();
	if(cha<10)
		cha="0"+cha;
	var cha1=d.getDate() + (6 - d.getDay());
	if(cha1<10)
		cha1="0"+cha1;
	var beginweek=vYear+"-"+vMon+"-"+cha; 
	var endweek = vYear+"-"+vMon+"-"+cha1; 
	//alert(weekStartDate+"----"+weekEndDate);
	var week_filter=getWeekDataSql(beginweek,endweek);
	var field_dealing1=[{'name':showname,'notes':shownotes}];		
	mcssTable2=new MCSSTable({tableid:"mcss_table2",filter:week_filter,modelid:"oa_task_week_list",showfirst:false,defaultValue:"begindate:"+beginweek+",enddate:"+endweek,special_field_show:field_dealing1,useActionIcon:false,toolbar:"",hideLastTd:false,pagerows:20,afterLoadRows:afterrun_week});
	mcssTable2.run();
	mcssTable3=new MCSSTable({tableid:"mcss_table3",modelid:"oa_project_my_list",showSaveAndAddButton:true,pagerows:20,toolbar:"",afterLoadRows:afterrunPro});
	mcssTable3.run();
	mcssTable4=new MCSSTable({tableid:"mcss_table4",modelid:"biz_opportunity_my_list",useActionIcon:false,special_field_show:field_dealing,pagerows:20,toolbar:"edit",last_td_name:'操作'},null,last_td);
	mcssTable4.run(); 
	var field_dealingmessage = [{'title':showtitle}];
	var filter = "reciever_id = "+getCookie("mcss_loginstaffid")+" and statue = 0";
	mcssTable5=new MCSSTable({tableid:"mcss_table5",modelid:"sys_message",special_field_show:field_dealingmessage,pagerows:5,filter:filter});
	mcssTable5.run(); 
}
function showtitle(fieldValue,record){
	if(record.open_url)
		return "<span style='width:30px;background:url(__PUBLIC__/projects/mj/images/messageico.png) no-repeat;cursor:default'></span> <a href='javascript:void(0)' title='"+record.content+"' onclick='updateMessage("+record.id+",this),goMenu(\""+record.open_url+"\",\"消息链接\"),showContent(\""+record.content+"\",this)'>"+fieldValue+"</a>";
	else
		return "<span style='width:30px;background:url(__PUBLIC__/projects/mj/images/messageico.png) no-repeat;cursor:default'></span> <a href='javascript:void(0)' title='"+record.content+"' onclick='updateMessage("+record.id+",this),showContent(\""+record.content+"\",this)'>"+fieldValue+"</a>";
}
function updateMessage(id,obj){
	$.post("__APP__/Oa/Index/updateMessageStatue",{id:id},function(){
		$(obj).prev().css("background","url(__PUBLIC__/projects/mj/images/messageopen.gif) no-repeat");
	})
}
function showContent(content,obj){
	var h = content;
	mcdom.showPopup(obj,h,null,null,null,250,336,"消息内容");
}
function afterrun_week(mcsstable){
	//alert(mcsstable.recordCount);
	//alert($("#2").find("span").html());
	$("#2").find("span").html(mcsstable.recordCount);
}
function last_td(id)
{
	return "<a class='smallbut mcsstable_record_open' onClick='find_url("+id+")' title='查看' style='cursor:pointer;color:blue;'>查看</a><a class='smallbut mcsstable_record_edit' onClick='find_url2("+id+")' title='编辑' style='cursor:pointer;color:blue;'>编辑</a>";

}
function afterrunPro(){
	$("#mcss_table3_caption").hide();
}
function getWeekDataSql(beginweek,endweek)
{
		var currentCalendarUserStaffId=getCookie("mcss_loginstaffid");

		var filter="((begindate<=<yh>"+beginweek+"<yh> and enddate>=<yh>"+beginweek+"<yh>) or  (begindate>=<yh>"+beginweek+"<yh> and enddate<=<yh>"+endweek+"<yh>) or (begindate<=<yh>"+endweek+"<yh> and enddate>=<yh>"+endweek+"<yh>) or (begindate<=<yh>"+beginweek+"<yh> and enddate>=<yh>"+endweek+"<yh>))"
		+" and (executerid="+currentCalendarUserStaffId+" or SYS_ADDUSER=<yh>"+getCookie('currentCalendarUserLoginUser')+"<yh>) ";
		if ($("#checkbox_showProject").attr("checked")==true)
			filter+=" and ((cat='周报' and tag=2) or cat='项目')";
		else
			filter+=" and (cat='周报' and tag=2)";
	return filter;
}
function shownotes(fieldvalue,record)
{
	if(fieldvalue=="")
		return "<a href='#' title='无评价'><img src='__PUBLIC__/themes/default/images/s.gif' class='noEvaluation'/></a>";
	else
		return "<a href='#' title='"+fieldvalue+"' class='tipsimple'><img src='__PUBLIC__/themes/default/images/s.gif' class='evaluation'/></a>";
}

function showname(fieldvalue,record)
{
	if(fieldvalue)
	{
		var newfieldvalue = fieldvalue.substring(0,100);
		return "<p title='"+record.name+"'>"+newfieldvalue+"...</p>";
	}
}
//得到公司LOGO
function getLogo()
{
	$.get("__APP__/Oa/Index/getLogoByOrgid",{},function(logo){
		if(logo && logo!="null")
			$(".sysLogo").find("img").attr("src","__PUBLIC__/uploadfile/"+logo);});
}
//得到用户的头像
function getUserImg()
{
	$.get("__APP__/Oa/Index/getStaffImg",{},function(img){
		if(img&&img!="null")
			$(".managerhead").find("img").attr("src","__PUBLIC__/uploadfile/"+img);});
}
//得到用户的心情
function getUserPlan()
{
	$.get("__APP__/Oa/Index/getLoginUserPlan",{},function(data){
		if(data.length>21)
			data=data.substr(0,21)+"...";
		$(".xinqing").html(data);
		$("#popup_fuceng_Box").remove();});
	
}
function changeDefault(e)
{
	$(e).attr('src','__PUBLIC__/themes/default/images/managerbg.jpg');
}
function markattendance(obj)
{
	var url="__APP__/Oa/Task/getAttendanceId";
	$.post(url,function(data){	
		data=data.split("<>");
		var id=data[0];
		var begintime=new Date(data[1]+" 12:00:00");
		var endtime=new Date(data[1]+" 18:00:00");
		var time=new Date();
		if(id)
		{	
			var url = "__APP__/Oa/Index/markattendance/id/"+id;				
			ShowIframe(url,700,400,'下班打卡');
						
		}else{
			var url = "__APP__/Oa/Index/markattendance";
			ShowIframe(url,700,400,'上班打卡');		
			
		}
	})
}
function on_resize()
{ 
	//window.onresize = null; 
	var height = document.documentElement.clientHeight;
	var width = document.documentElement.clientWidth;
	var screenWidth=window.screen.width;var screenHeight=window.screen.height;
	var bodyLeftW=$("#bodyLeft").width();
	//如果是IE浏览器
	if($.browser.msie) {
		if(width==screenWidth){
			var i=$.browser.version;
			//当IE全屏的时候
			var bodyRightW=screenWidth-bodyLeftW-4;
			if(i==9.0){
				var bodyLeftH=height-22;
				}else{
				var bodyLeftH=height-22;
				}
		}
		else{
			var width = screenWidth-4;
			var bodyRightW=width-bodyLeftW-5;
			var bodyLeftH=height-22;
		} 
	}
	else{
		var bodyRightW=screenWidth-bodyLeftW-5;
		var bodyLeftH=height-22;
	}
	
	$("#bodyLeft .firstMenu").height(bodyLeftH);
	$("#bodyRight").width(bodyRightW).height(bodyLeftH);
	
	var iframeheight=bodyLeftH-$(".RightTop").height()-$(".subTab").height()-$(".topHideShow").height()-2;
	$(".mainIframe").height(iframeheight);
	$("#subwindow").height(iframeheight);
	//tab滚动
	var marquee_width=$('.subTab_Con').width()-40;
	$("#subwinheader").width(marquee_width);
	
} 

    
function getAndCreateTopMenu(mcss_menu_id)
{
	var appurl=getHomeUrl();
	var app=getCookie("mcss_app");
	var menu=$(document).data('mcss_menu');
	if (menu.length>0)
	{
		createTopMenu(mcss_menu_id,menu);
	}
	else
	{
	$.getJSON(appurl+"/Home/Index/getLoginUserFunc",{},function(data){
		g_allmenu=data;
		createTopMenu(mcss_menu_id,data);});
	}	
}

function createTopMenu(mcss_menu_id,data)
{
		var h="";
		var s="";
		for(var i=0;i<data.length;i++) //一级菜单
		{	
			if (!data[i].groupno)
				h+="<li><a onclick='showSubMenu(this)' id='"+data[i]['no']+"' class='tipsimple' title='"+data[i]['name']+"' name='"+data[i]['name']+"'><span class='menuIconsbg "+data[i]['no']+"'"+data[i]['no']+"'></span></a></li>";
		}
		$("#"+mcss_menu_id).append(h);
		tips();
		//菜单容器高度
		var nanoH=$("#bodyLeft").height()-58;
		$(".nano").height(nanoH);
		//加载滚动条样式
		$('.nano').nanoScroller({
		preventPageScrolling: true
		});
		//设置按钮-添加分界线,跟上边的图标区分开
		$("#system").parent().addClass('lastMenu_borderbg');
		var firstmenu=getCookie("mcss_first_level_menu");
		if(firstmenu && firstmenu!='null')
		{	
			$("#"+firstmenu+"").click();
			$("#"+firstmenu+"").addClass("highlight");
			var secondmenu=getCookie("mcss_second_level_menu");
			if(secondmenu && secondmenu!='null')
			{
				$("#"+secondmenu+"").click();
				$("#"+secondmenu+"").addClass("highlight");
				//$("#"+secondmenu+"").parent().addClass("highlight");
				var thirdmenu=getCookie("mcss_third_level_menu");
				if(thirdmenu && thirdmenu!='null')
				{
					$("#"+thirdmenu+"").click();
					$("#"+thirdmenu+"").addClass("highlight");
					var fourthmenu=getCookie("mcss_fourth_level_menu");
					if(fourthmenu && fourthmenu!='null')
					{
						$("#"+fourthmenu+"").click();						
						$("#"+fourthmenu+"").addClass("highlight");
					}
				}
			}else
			{
				$(".menuList").hide();
				$("#desktop").show();
			}
		}		

	if (getCookie(getCookie("mcss_loginuser")+"_topmenuview")=="word")
	{
		$("#menuviewstyle").click();
	}		

}
//标题提示
function tips(){
	$('.tipsimple').poshytip({
		className: 'tip-yellowsimple',
		alignTo: 'target',
		alignX: 'right',
		alignY: 'center',
		offsetX: 2
	});	
}

function getAllMenu()
{	
	$.getJSON("__APP__/Home/Index/getLoginUserFunc",{},function(data){
		$(document).data('mcss_menu',data);
		getAndCreateTopMenu("mcss_top_menu");})
}	

function showSubMenu(a)
{
	//如果二级菜单区域隐藏了，则显示
	if ($("#showhideleftbar").attr("className").indexOf("hidebutton")>-1)
	{
		$("#showhideleftbar").click();
	}
	$(".firstMenu").find('a').removeClass("highlight");
	$(a).addClass("highlight");
	$("#desktop").hide();
	$(".menuList").show();
	createSubmenu(a.id);
	$("#mcss_secondmenu").show();
}
 
function createSubmenu(parentcode){
	$("#mcss_secondmenu").html("");
	var data=$(document).data('mcss_menu');
	if (!data) return;
	var h="";
	for(var i=0;i<data.length;i++) //一级菜单
	{
		if (data[i].groupno==parentcode)
		{
			h+="<div class='submenu_con'>";
			if(menu_has_sub_menu(data,data[i].no)){
				h+="<h3><a id='"+data[i].no+"'><img src='__PUBLIC__/themes/default/images/s.gif'  class='elbow-plus'/>"+data[i].name+"</a></h3>";
				h+="<ul class='sub'>";
				for(var k=0;k<data.length;k++){
					if (data[k].groupno==data[i].no) 
					{
						if(menu_has_sub_menu(data,data[k].no)){
							h+="<li class='top'>";
							h+="<a class='toggle_top_link'><img src='__PUBLIC__/themes/default/images/s.gif' class='elbow-plus'/><span id='"+data[k].no+"'>"+data[k].name+"</span></a>";
							h+="<ul class='sub_three'>";
							for(var j=0;j<data.length;j++){
								if(data[j].groupno==data[k].no){
									h+="<li><a onclick='openThisMenu(this)' id='"+data[j].no+"'>"+data[j].name+"</a></li>";
								}
							}
							h+="</ul>";
							h+="</li>";
						}else{
							h+="<li><a onclick='openThisMenu(this)' id='"+data[k].no+"' class='top_link_nomenu'>"+data[k].name+"</a></li>";
						}
					}
				}	
				h+="</ul>";			
			}else{
				h+="<h3 style='padding-left:32px'><a onclick='openThisMenu(this)' id='"+data[i].no+"'>"+data[i].name+"</a></h3>";	
			}
			h+="</div>";
		}
	}
	$("#mcss_secondmenu").html(h);
	$(".submenu_con h3").toggle(function(){
		$(this).addClass("dropdown").next('.sub').slideDown();
		
	},function(){
		$(this).removeClass("dropdown").next('.sub').slideUp();
	});
	$("a.toggle_top_link").toggle(function(){
		$(this).addClass("lightheight").next('.sub_three').slideDown();
	},function(){
		$(this).removeClass("lightheight").next('.sub_three').slideUp();
	});
}

function openThisMenu(e)
{
	setCookie("mediaUrl",getHomeUrl()+"/Home/Index/openMenuUrl/func_code/mj_media_all");
	setCookie("mcss_mj_currentMenu",e.id);
	
	$("#mcss_secondmenu").find('a').removeClass("highlight");
	$(e.parentNode).children('a').addClass("highlight");
	openMenu(e);
}

//退出提示
function outprompt()
{
	mcdom.comfirm("确定要退出吗？",logout);
}
//显示自定义收藏页面
function showcollect(obj){
	$("#setcut").remove();
	var test=$("#subwinheader .li_hover").attr("id");
	//id=test.substr(test.length-1,1);
	var t=test.split("_");
	id=t[1];
	var name = $("#mainiframe_"+id).html();
	if(!name || name=='工作台')
	{
		name=$("#winheader_"+id).text();
	}
	var h = "<div id='setcut' class='collect'><span>菜单名称&nbsp;</span><input id='collname' value='"+name+"' type='text' /></div><div class='fuceng_buttom'><a id='saveshoucangbutton' class='mcssingbutton btn btn-green' style='cursor:pointer' onclick='savePage()'>确定</a> <a class='mcssingbutton btn' style='cursor:pointer' onclick='resetPage()'>取消</a></div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,250,336,"收藏菜单");
	$("#collname").focus().keyup(function(){
		if (event.keyCode==13)
			$("#saveshoucangbutton").click();
	});
}

function savePage(){
	var app=getCookie("mcss_app");
	//var url = $("#mainiframe_"+id).attr("src");
	var url = $("#mainiframe_"+id).get(0).contentDocument.URL;
	var urlpath = "__APP__/Home/Index/saveFavorite";
	var name = $("#collname").val();
	$.post(urlpath,{url:url,func:name,project:app},function(data){
		if(data==2){
			$("#shorcut").html('');
			$("#"+name).remove();
			var a ="<li id=\""+name+"\" onmouseover='closeboxshow(this)' onmouseout='closeboxhide(this)'><a href='#' onclick='goMenu("+'"'+url+'","'+name+'"'+")'>"+name+"</a><a href=\"javascript:delCollect('"+name+"')\" id='closebox' title='取消该常用菜单' style='display:none;'><img style='float:right; margin-right:2px; margin-top:5px;' src='__PUBLIC__/themes/default/images/delCollect.gif'/></a></li>";
			$("#kuaijieMenu ul").append(a);
			$(mcpopup).remove();
		}else
		if(data==1){
			$("#shorcut").html('');
			var a ="<li id=\""+name+"\" onmouseover='closeboxshow(this)' onmouseout='closeboxhide(this)'><a href='#' onclick='goMenu("+'"'+url+'","'+name+'"'+")'>"+name+"</a><a href=\"javascript:delCollect('"+name+"')\" id='closebox' title='取消该常用菜单' style='display:none;'><img style='float:right; margin-right:2px; margin-top:5px;' src='__PUBLIC__/themes/default/images/delCollect.gif'/></a></li>";
			$("#kuaijieMenu ul").append(a);
			$(mcpopup).remove();
		}else{
			MCDom.prototype.alert("收藏失败","警告","fail","fadeout");
		}
			var e=document.getElementById("kuaijieMenu");
			e.scrollTop=e.scrollHeight;			
		
		setShoucangheight();		
	});
}

function goMenu(url,name)
{		
	var arr= new Array(); //定义一数组
	arr=url.split("/"); //字符分割   
	var obj = new Object();
	obj.id=arr[arr.length-1];
	obj.url=url;
	obj.innerHTML=name;
	openMenu(obj);
	//createSubWin(obj);
}

//取消收藏当前页面
function resetPage(){
	$(mcpopup).remove();
}

//加载收藏夹
function loadShortcut()
{	
		var urlpath="__APP__/SYS/Admin/getShortcutUrls";
		var app=getCookie("mcss_app");
		$.getJSON(urlpath,{project:app},function(rows) {
		    var v;
		    for(var i=0;i<rows.length;i++)
		    {
		    v=rows[i];
			var url=v.url;
			var a ="<li id=\""+v.name+"\" onmouseover='closeboxshow(this)' onmouseout='closeboxhide(this)'><a href='#' onclick='goMenu("+'"'+url+'","'+v.name+'"'+")'>"+v.name+"</a><a href=\"javascript:delCollect('"+v.name+"')\" id='closebox' title='取消该常用菜单' style='display:none;'><img style='float:right; margin-right:2px; margin-top:5px;' src='__PUBLIC__/themes/default/images/delCollect.gif'/></a></li>";
			
			$("#shoucang").append(a);
			}
			setShoucangheight();

		});
}

//自动调整收藏夹的高度
function setShoucangheight()
{
	var n=$("#shoucang").children().size();
	if (n<6)
		$("#kuaijieMenu").css('height',(35+n*33)+'px');
}
function closeboxshow(obj)
{
		$(obj).find("#closebox").show();
}
function closeboxhide(obj)
{
		$(obj).find("#closebox").hide();
}

//删除收藏页面的方法
function delCollect(func){
	//var project="Meimeng";
	var app=getCookie("mcss_app");
	var urlpath = "__APP__/Home/Index/delFavorite";
	$.post(urlpath,{func:func,project:app},function(data){
		if(data > 0){
			$("#shorcut").html('');		
			$("#"+func+"").remove();
		}else{
			MCDom.prototype.alert("取消失败","警告","fail","fadeout");
		}
	});
}

//便签
function shownote(obj)
{
	var url = "__APP__/Oa/Index/addnote";
	ShowIframe(url,730,300,'笔记本');
}

function openDesktop()
{
	$(".firstMenu").find('a').removeClass("highlight");
	$("#mj_setting").addClass("highlight");
	$(".menuList").hide();
	$("#desktop").show();
	goMenu(g_homeurl+"/oa/Index/home","工作台");
	on_resize();
}
//个人中心
function editUser()
{
	var iframeurl="__APP__/Oa/Person/usermanage/id/"+getCookie("mcss_loginstaffid");
	setCookie("mcss_iframeurl",iframeurl);
	setCookie("mcss_iframename","个人中心");	
	var a=new Object();
	a.id ='mcss_mysetting';
	createSubWin(a);
	on_resize();
}
//图标显示切换 
var viewstyle = "text";
function imgViewStyle(){
	$("#mj_setting").html("");
	for(var i = 0;i < $("#mcss_top_menu").children('li').length;i++){
		$("#mcss_top_menu").children('li').eq(i).children('a').eq(0).html('');
	}
	if(viewstyle=="icon"){
		$("#mj_setting").html("<span class='menuIconsbg gongzuotaiico'></span>");
		for(var i = 0;i < $("#mcss_top_menu").children('li').length;i++){
			var menuobj = $("#mcss_top_menu").children('li').eq(i).children('a').eq(0);
			menuobj.html("<span class='menuIconsbg "+menuobj.attr("id")+"'></span>");
		}
		viewstyle = "text";
		$("#menuviewstyle").attr('title','菜单显示模式:图标');
		setCookie(getCookie("mcss_loginuser")+"_topmenuview","icon");		
	}else{
		$("#mj_setting").html("<font style='cursor:pointer;color:white'>首页</font>");
		for(var i = 0;i < $("#mcss_top_menu").children('li').length;i++){
			var menuobj = $("#mcss_top_menu").children('li').eq(i).children('a').eq(0);
			var text = menuobj.attr("title");
			if(menuobj.attr("title")=="工作中心")
				text='工作';
			else if(menuobj.attr("title")=="客户共享")
				text='共享'
			else if(menuobj.attr("title")=="系统设置")
				text='系统';
			else if(menuobj.attr("title")=="BI")
				text='B I'
			menuobj.html("<font style='cursor:pointer;color:white'>"+text+"</font>");
		}
		viewstyle = "icon";
		$("#menuviewstyle").attr('title','菜单显示模式:文字');
		setCookie(getCookie("mcss_loginuser")+"_topmenuview","word");
	}
}

function openMenuMy(url,name,id)
{
	
	var iframeurl="__APP__/"+url;	
	if(url.indexOf("http"))
		iframeurl=url;
	var a=new Object();
	a.id =id;
	a.innerHTML=name;
	
	setCookie("mcss_iframeurl",iframeurl);
	setCookie("mcss_iframename",name);	
	setCookie("mediaUrl",iframeurl);
	setCookie("mcss_mj_currentMenu",a.id);
	
	openMenu(a);
}

//获取邮件的信息
function getmailinfo(){
	$.getJSON("__APP__/Sys/Setting/getMailInfo",function(data){
		if(data){
			setCookie("mcss_usermail",data[0]["email"]);
			setCookie("mcss_usermailpass",data[0]["password"]);
		}
	})
}
</script>
</body>
</html>