<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="qc:admins" content="7233223307653331676375" />
<title>工作台</title>
<link href="__PUBLIC__/projects/oa/css/calendar.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/themes/default/css/otherweb.css" rel="stylesheet" type="text/css">
<script src="__PUBLIC__/themes/default/js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/calendar/McssCalendar.js"></script>
<script type="text/javascript" src='__PUBLIC__/js/popup.js'></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/MCSSTable.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/autoform.js"></script>
<script src="__PUBLIC__/jusaas/js/dom.js"></script>
<script src="__PUBLIC__/plugins/dialog/lhgdialog.min.js?skin=blue"></script>
<script type="text/javascript" src='__PUBLIC__/plugins/poshytip/jquery.poshytip.js'></script>
<script type="text/javascript">
	var form1;
	var mcssTable0,mcssTable1;
	var mymccalendar=new Array();
	$(function(){
		mccalendar=new McssCalendar({id:'calendar_navigator',onclick:onclick,afterShowCalendarBody:loadlist,calendartype:'selectdate'});
		mccalendar.run();
		var mcssTable2=new MCSSTable({tableid:"mcss_table_project_my",modelid:"oa_project_my",style:"simple",useActionIcon:true,toolbar:'edit,del',useActionIcon:false});
		mcssTable2.run();
		var field_dealing=[{'no':showdetail}];
		var mcssTable=new MCSSTable({tableid:"mcss_table_opportunity",modelid:"biz_opportunity_my2",style:"simple",useActionIcon:true,toolbar:'edit,del',useActionIcon:false,special_field_show:field_dealing});
		mcssTable.run();
		/*showSharedInfo();*/
		showSharedInfo2();
		
		$(".WorksMenu ul li").click(function()
		{
			$(this).addClass("nav").siblings().removeClass("nav");
			var index=$(".WorksMenu ul li").index($(this));
			$(".WorksContent > .xxinfo").eq(index).show().siblings().hide();
		});
	})
	
	
	
	function showdetail(fieldvalue,record){
		//alert(record.id);
		return  "<a href='__APP__/Biz/Business/businessedite/id/"+record.id+"'>"+fieldvalue+"</a>";
	}
/*function showSharedInfo()
{
	newArtical="isNew";
	var mcssTable_info=new MCSSTable({tableid:"sharedinfo",modelid:"oa_info_lasted",afterLoadRows:afterLoadRows_info});
	mcssTable_info.run();
}*/
function showSharedInfo2()
{
	newArtical="";
	var mcssTable_info=new MCSSTable({tableid:"sharedinfo2",modelid:"oa_info_lasted2",afterLoadRows:afterLoadRows_info2,pagerows:6});
	mcssTable_info.run();
}
function afterLoadRows_info(mcsstable)
{	
	var data=mcsstable.data;
	var li="";
	var name="";
	var count="";
	var len=10;
	
	for(var i=0;i<data.length;i++)
	{
 		name=data[i]["name"];
		count=data[i]["count"];
		li+="<li><a href='javascript:void(0)' onclick='openInfo(this)' id='"+data[i]["id"]+"'>"+name+"</a> <span>"+data[i]["SYS_ADDTIME"]+"</span></li>";
	}
	$("#mysharedinfo").html(li);
}
function afterLoadRows_info2(mcsstable)
{	
	var data=mcsstable.data;
	var li="";
	var name="";
	var count="";
	var len=10;
	
	for(var i=0;i<data.length;i++)
	{
 		name=data[i]["name"];
		count=data[i]["count"];
		li+="<li><a href='javascript:void(0)' onclick='openInfo(this)' id='"+data[i]["id"]+"'>"+name+"</a> <span>"+data[i]["SYS_ADDTIME"]+"</span></li>";
	}
	$("#deptsharedinfo").html(li);
}	
function openInfo(e)
{
	var url="__APP__/Oa/Index/viewRecord/model/oa_info_view/id/"+e.id;
	//ShowIframe(url, "700","500","信息分享");
	var params = {title:"信息分享",lock:'true',width:'960px',height:'500px'};
	mcdom.showIframe(url,params);
}
function loadlist(mccalendar,onlyrefreshData)
{
	//mymccalendar=mccalendar;
	var date=mccalendar.currentdate;
	var day=mccalendar.getGoodDate(date);
	mymccalendar.day=mccalendar.getGoodDate(date);
	var beginweek=mccalendar.getWeekStartDate();
	mymccalendar.beginweek=mccalendar.getWeekStartDate();
	var endweek=mccalendar.getWeekEndDate();
	mymccalendar.endweek=mccalendar.getWeekEndDate();
	var beginmonth=mccalendar.getMonthStartDate();
	var endmonth=mccalendar.getMonthEndDate();	

	var executerid=getCookie("mcss_loginstaffid");
	var filter0="begindate='"+day+"' and enddate='"+day+"' and executerid='"+executerid+"' and tag=2 and cat='日报'";
	var field_dealing=[{'name':showname}];
	
	var filter1="((begindate<=<yh>"+beginweek+"<yh> and enddate>=<yh>"+beginweek+"<yh>) or  (begindate>=<yh>"+beginweek+"<yh> and enddate<=<yh>"+endweek+"<yh>) or (begindate<=<yh>"+endweek+"<yh> and enddate>=<yh>"+endweek+"<yh>) or (begindate<=<yh>"+beginweek+"<yh> and enddate>=<yh>"+endweek+"<yh>)) and (executerid="+executerid+" or SYS_ADDUSER=<yh>"+getCookie('mcss_loginuser')+"<yh>)";
		var field_dealing=[{'name':showname,'notes':shownotes}];
		
		
	if(onlyrefreshData)
	{
		mcssTable0.filter=filter0;
		mcssTable0.reload();
		mcssTable1.filter=filter1;
		mcssTable1.reload();
	}else
	{	
		
		//日工作
		 mcssTable0=new MCSSTable({tableid:"mcss_table0",filter:filter0,modelid:"oa_task_add_day",defaultValue:"begindate:"+day+",enddate:"+day,style:"simple",special_field_show:field_dealing,useActionIcon:true,toolbar:'edit,del',useActionIcon:false,showSaveAndAddButton:true,showSaveAndAddButton:true,afterLoadRows:day_afterLoadRows,pagerows:5});
		mcssTable0.run();
		
		//周工作
		
		var week_filter=getWeekDataSql(beginweek,endweek);
		var field_dealing=[{'name':showname,'notes':shownotes}];		
		mcssTable1=new MCSSTable({tableid:"mcss_table1",filter:week_filter,modelid:"oa_task_week_detail",showfirst:false,defaultValue:"begindate:"+beginweek+",enddate:"+endweek,special_field_show:field_dealing,useActionIcon:true,style:'simple',hideLastTd:false,toolbar:'del',useActionIcon:false,afterLoadRows:week_afterLoadRows,pagerows:5},null,last_td_func);
		mcssTable1.run();
		if($("#showallWeek").size()==0)
			$("#mcss_table1").after("<div id='WeekToobar' class='bottom_toolbar'><a id='showallWeek' class='showallWeek_btn' title='显示更多' style='display:none'></a></div>");
		
		if($("#showallday").size()==0)
			$("#mcss_table0").after("<div id='DayToobar' class='bottom_toolbar'><a id='showallday' class='showallWeek_btn' title='显示更多' style='display:none'> </a></div>");
		$.post("__APP__/Oa/Index/getDayAndWeekTotal",{weekfilter:week_filter,dayfilter:filter0},function(data){
			var arr = data.split('|');
			$("#showallWeek").toggle(function(){
				mcssTable1.pagerows=500;
				$(this).addClass('hideallWeek_btn').attr('title','收起');
				mcsstable_loaddatarows("mcss_table1");
				},function(){
					mcssTable1.pagerows=5;
					$(this).removeClass('hideallWeek_btn').attr('title','显示更多');
					mcsstable_loaddatarows("mcss_table1");
				});

			$("#showallday").toggle(function(){
				mcssTable0.pagerows=500;
				$(this).addClass('hideallWeek_btn').attr('title','收起');
				mcsstable_loaddatarows("mcss_table0");
			},function(){
				mcssTable0.pagerows=5;
				$(this).removeClass('hideallWeek_btn').attr('title','显示更多');
				mcsstable_loaddatarows("mcss_table0");
			});
		})
	}
		
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


function dosomemonth(){
	var begin=mccalendar.getMonthStartDate();
	var end=mccalendar.getMonthEndDate();
	form4.setFieldValue('begindate',begin);
	form4.setFieldValue('enddate',end);
}

function afterrunmonth(table,mccalendar)
{
	if($("#mcss_table2 tr").length<7)
	{
		$("#showallcount").unbind();
		$("#showallcount").click(function(){
			MCDom.prototype.alert("没有更多的信息！","提示","info");
		});
	}
	$("#mcsstablemcss_table2_add").parent().hide();
	if($("#CountToobar").find("#mcsstablemcss_table2_add").html()==null)
		$("#mcsstablemcss_table2_add").appendTo($("#CountToobar"));
	mcsstable=table;
	$("#mcsstable"+mcsstable.tableid+"_add").attr('onclick','');
	$("#mcsstable"+mcsstable.tableid+"_add").click(function()
	{
		var h="<div id='"+mycalendar.id+"_week'></div>"+"<div class='fuceng_buttom'><a id='savemonth' class='btn btn-green' title='保存'>保存</a></div>";
		mcpopup=mcdom.showPopup(this,h,null,null,null,400,400,"新增");
		
		form4=new Autoform(mycalendar.id+"_week",{modelid:'oa_task_month_add_detail'});   
		form4.run(dosomemonth);
		
		$("#savemonth").click(function()
		{				
			if(begindate==enddate)
				form4.save(false,afterinsert,false);
			else
			{
				form4.save(false,aftersave1,false);
				//$(mcpopup).remove();
				mcsstable_loaddatarows("mcss_table1");
			}
		})
	});
	$("#mcsstable"+mcsstable.tableid+"_add").after("<a class='showbg addZongjie' onclick='openMonthReport(this)'><img src='__PUBLIC__/themes/default/images/yuezongjie.gif' title='月总结'/></a>")
}
function shownotes(fieldvalue,record)
{
	if(fieldvalue=="")
		return "<a href='#' title='无评价'><img src='__PUBLIC__/themes/default/images/s.gif' class='noEvaluation'/></a>";
	else
		return "<a href='#' title='"+fieldvalue+"' class='tipsimple'><img src='__PUBLIC__/themes/default/images/s.gif' class='evaluation'/></a>";
}
function day_afterLoadRows(table)
{
	mcsstable=table;
	$.post("__APP__/Oa/Index/getDayAndWeekTotal",{dayfilter:mcsstable.params.filter},function(data){
		var arr = data.split('|');
		if(arr[1] > 5)
			$("#showallday").show();
		else
			$("#showallday").hide();
	})
	$("#mcsstablemcss_table0_add").attr('onclick','');
	$("#mcsstablemcss_table0_add").click(function()
	{
		var day=mymccalendar.day;
		var h="<div id='add_day_task'></div>"+"<div class='fuceng_buttom'><a onclick='saveWeekPlan()' id='saveweek'class='btn btn-green' title='保存'>保存</a><a onclick='saveWeekPlan(true)' id='mcsstablemcss_table_month_addweek_saveandnew' title='保存并新增' class='btn'>保存并新增</a></div>";
		mcpopup=mcdom.showPopup(this,h,null,null,null,420,390,"新增");
		form3=new Autoform("add_day_task",{modelid:'oa_task_add_day1',defaultValue:"begindate:"+day+",enddate:"+day});   //韩丽芳
		form3.run();
	});
	$("#mcsstablemcss_table0_add").parent().hide();
	if($("#DayToobar").find("#mcsstablemcss_table0_add").html()==null)
	{
		$("#mcsstablemcss_table0_add").appendTo($("#DayToobar"));
	}
}

//保存自定义的日报
function saveWeekPlan(ifNewAfterSave){
	form3.save();
	if(ifNewAfterSave)	
		form3.reload();
	else
	{
		mcsstable_loaddatarows("mcss_table0")
		$(mcpopup).remove();
	}
	mcsstable_loaddatarows("mcss_table0")
	
	
}

function last_td_func(id)
{ 
	return "<a class='smallbut mcsstable_record_edit' onclick='oa_task_week_detail_edit("+id+",this)' style='cursor:pointer;color:blue;' title='修改'>修改</a><a class='smallbut mcsstable_record_del' style='cursor:pointer;color:blue;' onclick='mcsstable_deleterows(this,"+'"mcss_table1"'+","+id+")'>删除</a>";
}
function oa_task_week_detail_edit(id,obj){
	var h="<div id='mcsstablemcss_table1_addweek'></div>"+"<div class='fuceng_buttom'><a id='mcsstablemcss_table1_week_save' href='javascript:void(0)' onclick='saveweekplan(false)' class='btn btn-green'>保存</a></div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,400,400,"编辑");
	form1=new Autoform("mcsstablemcss_table1_addweek",{modelid:"oa_task_week_detail",recordid:id});
	form1.run(setWeekday);
}

//在开始结束日期后面显示星期几
function setWeekday(autoform){
	var begindate = autoform.getFieldValue('begindate');
	countDayOfDate(begindate,"begin");
	var enddate = autoform.getFieldValue('enddate');
	countDayOfDate(enddate,"end");
	var isleader=false;
	//if(autoform.getFieldValue("id"))
		//$("#mcsstablemcss_table1_addweek_notes").parent().parent().show();
	$("#"+autoform.getFieldID("begindate")).blur(function(){
		begindate =autoform.getFieldValue('begindate');
		countDayOfDate(begindate,"begin");
	});
	$("#"+autoform.getFieldID("enddate")).blur(function(){
		enddate =autoform.getFieldValue('enddate');
		countDayOfDate(enddate,"end");
	});
	
	$("#tr_mcsstablemcss_table1_addweek_projectid").hide();

}
//根据日期算出星期几
function countDayOfDate(begindate,field){
	if(field=="begin")
		$('#beginday').remove();
	if(field=="end")
		$('#endday').remove();
	
	var arr1 = begindate.split('-');
	
	var date1 = new Date(arr1[0],arr1[1]-1,arr1[2]).getDay();
	
	if(date1=='0')
		date1='星期日';
	else if(date1=='1')
		date1='星期一';
	else if(date1=='2')
		date1='星期二';
	else if(date1=='3')
		date1='星期三';
	else if(date1=='4')
		date1='星期四';
	else if(date1=='5')
		date1='星期五';
	else if(date1=='6')
		date1='星期六';
	if(field=="begin")
	{
		$("#mcsstablemcss_table1_addweek_begindate").css('width','200px');	
		$("#mcsstablemcss_table1_addweek_begindate").after(" <span id='beginday'><font face='微软雅黑'>"+date1+"</font></span>");
	}else
	{
		$("#mcsstablemcss_table1_addweek_enddate").css('width','200px');	
		$("#mcsstablemcss_table1_addweek_enddate").after(" <span id='endday'><font face='微软雅黑'>"+date1+"</font></span>");
	}
}
var mcpopup='';
function week_afterLoadRows(mcsstable){
	$.post("__APP__/Oa/Index/getDayAndWeekTotal",{weekfilter:mcsstable.params.filter},function(data){
		var arr = data.split('|');
		if(arr[0] >　5)
			$("#showallWeek").show();
		else
			$("#showallWeek").hide();
	})
	$("#mcss_table1").find(".mcsstable_record_del:even").hide();
	$("#mcsstablemcss_table1_add").parent().hide();
	if($("#WeekToobar").find("#mcsstablemcss_table1_add").html()==null)
		$("#mcsstablemcss_table1_add").appendTo($("#WeekToobar"));	
	$("#mcsstablemcss_table1_add").attr("onclick","");
	$("#mcsstablemcss_table1_add").click(function(){
		var h="<div id='mcsstablemcss_table1_addweek'></div>"+"<div class='fuceng_buttom'><a id='mcsstablemcss_table1_week_save' href='javascript:void(0)' onclick='saveweekplan(false)' class='btn btn-green'>保存</a><a onclick='saveweekplan(true)' id='mcsstablemcss_table1_addweek_saveandnew' class='btn'>保存并新增</a></div>";
		 mcpopup=mcdom.showPopup(this,h,null,null,null,400,400,"新增");
		form1=new Autoform("mcsstablemcss_table1_addweek",{modelid:"oa_task_week_detail",defaultValue:"begindate:"+mymccalendar.beginweek+",enddate:"+mymccalendar.endweek});
		form1.run(setWeekday);
	});
	//加载title样式
	$('.tipsimple').poshytip({
		className: 'tip-green',
		alignX: 'right',
		alignY: 'bottom',
		offsetX: -7,
		offsetY: 16,
	});	
}
function saveweekplan(isadd)
{
	if(!isadd)
		form1.save(false,aftersave1,false);
	else
		form1.save(false,aftersave,false);
}
function aftersave()
{
	form1.run();
	mcsstable_loaddatarows("mcss_table1");
}
function aftersave1()
{
	$(mcpopup).remove();
	mcsstable_loaddatarows("mcss_table1");
}
function showname(fieldvalue,record)
{
	if(fieldvalue)
	{
		if((get_length(fieldvalue))>25)
		{
			//var newfieldvalue = fieldvalue.substring(0,25);
			var newfieldvalue = cut_str(fieldvalue,25);			
			return "<p title='"+record.name+"'>"+newfieldvalue+"...</p>";
		}else{
			return "<p title='"+record.name+"'>"+fieldvalue+"</p>";
		}
	}
}
//一个汉字相当于2个字符
   function get_length(s){
        var char_length = 0;
        for (var i = 0; i < s.length; i++){
            var son_char = s.charAt(i);
            encodeURI(son_char).length > 2 ? char_length += 1 : char_length += 0.5;
        }
        return char_length;
    }
    function cut_str(str, len){
        var char_length = 0;
        for (var i = 0; i < str.length; i++){
            var son_str = str.charAt(i);
            encodeURI(son_str).length > 2 ? char_length += 1 : char_length += 0.5;
            if (char_length >= len){
                var sub_len = char_length == len ? i+1 : i;
                return str.substr(0, sub_len);
                break;
            }
        }
    }

	
function onclick(title,cal)
{	
	if(cal.currentdate-cal.today!=0)
		$("td[title='"+cal.getGoodDate(cal.currentdate)+"']").removeClass('ontoday_day');
	cal.currentdate=cal.newDate(title);
	$("#"+cal.id+"_currentdate").val(title);
	if(cal.currentdate-cal.today!=0)
		$("td[title='"+title+"']").addClass('ontoday_day');
		
	var workstitle=title+" 的工作";
	$(".todayList h3").html(workstitle);
	loadlist(cal,true);
}

function motto()
{
	var motto=$("#motto").val();
	if(motto && motto != '编辑个性签名')
	{	
		var url="__APP__/Oa/Index/savemotto";
		$.post(url,{motto:motto,id:getCookie('mcss_loginuserid')},function(data){
		});
	}else
	{	
		var url="__APP__/Oa/Index/delmotto";
		$.post(url,{id:getCookie('mcss_loginuserid')},function(data){
			$("#motto").attr('value','编辑个性签名');
		});
	}
	
}
</script>
</head>

<body class="padding">
<div class="homeLeft">
	<!--今日工作 Start-->
    <div class="todayWork">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr><td width="242px" valign="top">
    	<div class="rili">
        	<div id='calendar_navigator'></div>
        </div>
		</td><td valign="top">
        	<div class="todayList" id="landz_genjin_others">
				<div class="WorksMenu">
					<ul>
					<li class="nav">日工作</li>
					<li>周工作</li>
					</ul>
				</div>
				<!--基本信息 start-->
				<div class="WorksContent">
					<div id="mcss0" class="xxinfo">
						<table id="mcss_table0" width="100%" cellpadding="0" cellspacing="0"></table>
					</div>
					<div id="mcss1" class="xxinfo" style='display:none'>
						<table id="mcss_table1" width="100%" cellpadding="0" cellspacing="0"></table>
					</div>
				</div>
			</div>
		</td></tr></table>
    </div>
	
    <!--我要跟踪商机 Start-->
    <div class="shangji">
    	<div class="worksTab" id="landz_genjin_others">
			<div class="WorksMenu">
				<ul>
				<li class="nav">我的商机</li>
				<li>我的项目</li>
				</ul>
			</div>
			<!--基本信息 start-->
			<div class="WorksContent">
				<div id="mcss0" class="xxinfo">
					<table id="mcss_table_opportunity" width="100%" cellpadding="0" cellspacing="0"></table>
				</div>
				<div id="mcss1" class="xxinfo" style='display:none'>
					<table id="mcss_table_project_my" width="100%" cellpadding="0" cellspacing="0"></table>
				</div>
			</div>
		</div> 
    </div>
    <!--公告 帖子中心 Start-->
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top">
			<div class="todayList">
				<div class="WorksMenu newListTitle">
				<span>公告</span><a href="__APP__/Oa/Info/index">MORE</a>
				</div>
				<!--基本信息 start-->
				<div class="">
					
				</div>
			</div>
		</td>
		<td width="1%"> </td>
		<td valign="top">
			<div class="todayList">
				<div class="WorksMenu newListTitle">
				<span>互动中心</span><a href="__APP__/Oa/Info/index">MORE</a>
				</div>
				<!--基本信息 start-->
				<table id='sharedinfo2' width="100%" cellpadding="0" cellspacing="0" style='display:none'></table>
            	<div class="enJoinCon">
            		<ul id='deptsharedinfo'>
                    
                    </ul>
            	</div>
			</div>
		</td>
	</tr>
	</table>
</div>
</body>
</html>