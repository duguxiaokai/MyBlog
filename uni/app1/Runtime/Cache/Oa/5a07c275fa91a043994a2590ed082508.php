<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>工作日历</title>
<link href="__PUBLIC__/projects/oa/css/calendar.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/themes/default/css/otherweb.css" rel="stylesheet" type="text/css">
<link href='__PUBLIC__/plugins/poshytip/tip-green/tip-green.css' rel='stylesheet' type='text/css'/>

<script src="__PUBLIC__/themes/default/js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src='__PUBLIC__/js/popup.js'></script>
<script src="__PUBLIC__/jusaas/js/common.js"></script>
<script src="__PUBLIC__/jusaas/js/MCSSTable.js"></script>
<script src="__PUBLIC__/jusaas/js/MCDateTime.js"></script>
<script src="__PUBLIC__/jusaas/js/autoform.js"></script>
<script src="__PUBLIC__/jusaas/js/dom.js"></script>
<script src="__PUBLIC__/jusaas/calendar/McssCalendar.js"></script>
<script src="__PUBLIC__/plugins/dialog/lhgdialog.min.js?skin=blue"></script>
<script src='__PUBLIC__/plugins/poshytip/jquery.poshytip.js'></script>
<script src='__PUBLIC__/plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js'></script>
	
</head>

<body class="padding">
<!--今日工作 Start-->
    <div class="todayWork">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr><td width="242px">
    	<div class="rili">
        	<div id='calendar_navigator'></div>
        </div>
		</td><td>
        	<div class="todayList" id="landz_genjin_others">
				<div class="WorksMenu">
					<ul>
					<li class="nav">周工作</li>
					<li id='li_month'>月工作</li>
					<li>工作统计</li>
					<!--li>选项</li-->
					</ul>
				</div>
				<!--基本信息 start-->
				<div class="WorksContent">
					<div id="mcss0" class="xxinfo">
						<table id="mcss_table_week" width="100%" cellpadding="0" cellspacing="0"></table>
					</div>
					<div id="mcss1" class="xxinfo" style='display:none'>
						<table id="mcss_table_month" width="100%" cellpadding="0" cellspacing="0"></table>
					</div>
					<div id="	" class="xxinfo" style='display:none'  >
						<table id="mcss_table2" width="800" border="1" class="mcsstable">
							<tr>
								<th scope="col">完成数/计划数</th>
								<th scope="col">周日</th>
								<th scope="col">周一</th>
								<th scope="col">周二</th>
								<th scope="col">周三</th>
								<th scope="col">周四</th>
								<th scope="col">周五</th>
								<th scope="col">周六</th>								
							</tr>
							<tr>
								<td>日工作</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>周工作</td>
								<td colspan="7">&nbsp;</td>
							</tr>
							<tr>
								<td>月工作</td>
								<td colspan="7">&nbsp;</td>
							</tr>
							<tr>
								<td style="height:50px;">本月累计</td>
								<td colspan="7">&nbsp;</td>
							</tr>
						</table>
						<a  onclick='refreshCount()' class="refreh_btn" title="刷新"> </a>
					</div>
					<!--div id="mcss3" class="xxinfo" style='display:none'  >
						<input type='checkbox' id='checkbox_showProject' onclick="checkShowProject(this);" /><label for='checkbox_showProject'>把项目任务也显示到周报中>
					</div-->					
				</div>
				

			</div>
		</td></tr></table>
    </div>	
<!--今日工作End-->

<!--日历展示 Start-->
	<div class="todayList calendarAll">
		<div class="WorksMenu calendarHead">
			<span id="yuangong" class="staffName">
			选择下属员工：<select id='mysubstaff'></select>
			</span>
			<div class="calendarButton">
				<span id='xinshituqiehuan'></span>
				<a class="systermico printico" title="打印" onClick="printCalendar()">打印</a>
				<a class="systermico allscreenico" onClick="allscreen(this)" title="全屏显示">全屏显示</a>
			</div>
		</div>
		<!--基本信息 start-->
		<div class="calendarContent">
			<table id="mcss_table" width="100%"  border='0'></table>
			<div id='weekinmonth' class="calendarDisplay"  style='display:none; margin-bottom:10px;' ></div>	
			<div id='calendar' class="calendarDisplay"></div>	
		</div>
	</div>
<!--日历展示 End-->	
</body>
</html>

<script type="text/javascript">
var currentCalendarUserStaffId='';
var currentCalendarUserLoginUser='';

var mcssTable;
var mccalendar;//左上角选择日期的那个日历
var defaultDate="";
var markform;
var moretaskform
var calendar,mycalendar,monthweeklycalendar;
var form1,form3,form4;
var firstDate;
var mcssTable_week,mcssTable_month;
var editTaskPar1,editTaskPar2,editTaskPar3,editTaskPar4,editTaskPar5,editTaskPar6,editTaskPar7;
var orgid=getCookie("mcss_orgid");
var mcpopup;// 浮层
var zongjiedata;//周总结
$(document).ready(function(){

	$('#mcss2').hide();
	if (getCookie("currentCalendarUserStaffId") && getCookie("currentCalendarUserStaffId") != '')
	{	
		currentCalendarUserStaffId=getCookie("currentCalendarUserStaffId");
	}
	else
	{
		setCookie('currentCalendarUserLoginUser',getCookie('mcss_loginuser'));
		currentCalendarUserStaffId=getCookie("mcss_loginstaffid");
	}
	loadSubStaffList();
	
	$("#mcss_table").hide();
	mccalendar=new McssCalendar({id:'calendar_navigator',onclick:clickCalendarDate,afterShowCalendarBody:afterChangeDate,calendartype:'selectdate',showCalendartype:false});
	mccalendar.run();
	
	$(".WorksMenu ul li").click(function()
	{
		$(this).addClass("nav").siblings().removeClass("nav");
		var index=$(".WorksMenu ul li").index($(this));
		$(".WorksContent > .xxinfo").eq(index).show().siblings().hide();
		if (this.id=='li_month')
		{
			$("#weekinmonth").show();	
			if ($("#calendar_view_monthweek").attr("className").indexOf('shitubg_fouce')==-1)
				$("#calendar_view_monthweek").click();
		}
		else
			$("#weekinmonth").hide();
	})	
	
	
	if(getParamValue(location.href,'view'))
		$(".todayWork").hide();	

	/*if (parent.showhideLeftBar)
	parent.showhideLeftBar();*/
});


function afterChangeDate(calendar)
{	
	if (!mcssTable_week)
		createTablesAndCalleder(mccalendar);
	else
	{
		reloadTablesAndCalleder(calendar);	//已经创建则是刷新数据即可
	}
}

function last_td_func_week(id)
{ 
	return "<a class='smallbut mcsstable_record_edit' onclick='oa_task_week_detail_edit("+id+",this)' style='cursor:pointer;color:blue;' title='修改'>修改</a><a class='smallbut mcsstable_record_del' style='cursor:pointer;color:blue;' onclick='mcsstable_deleterows(this,"+'"mcss_table_week"'+","+id+")'>删除</a>";
}

function oa_task_week_detail_edit(id,obj){
	var h="<div id='mcsstablemcss_table_month_addweek'></div>"+"<div class='fuceng_buttom'><a id='mcsstablemcss_table_month_week_save' href='javascript:void(0)' onclick='saveWeekPlan()' class='btn btn-green'>保存</a></div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,400,400,"编辑");
	form3=new Autoform("mcsstablemcss_table_month_addweek",{modelid:'oa_task_week_detail',recordid:id});   
	form3.run(dosomeweek);
}

//单击周报的保存并新增
function afterSaveAndNewWeekPlan()
{
	form3.reload();
}

//单击周报的保存
function afterSaveWeekPlan()
{
	$(mcpopup).remove();
}

//加载下属员工列表
function loadSubStaffList()
{
	if (!getCookie('mcss_currentCalendarUserStaffName') || getCookie('mcss_currentCalendarUserStaffName')=='null')
	{
		setCookie("mcss_currentCalendarUserStaffName",getCookie('mcss_loginstaffname'),10);
	}
	var h="";
	var name="";
	var url ="__APP__/Oa/Task/mysubstaff";
	if (getCookie("mcss_loginuserrole")=='admin')
	{
		url ="__APP__/Oa/Task/AllStaff";
	}
	$.getJSON(url,{},function(data)
	{
		if (data &&data.length>0)
		{
			var list="<option value='staffid__"+getCookie('mcss_loginstaffid')+"__"+getCookie('mcss_loginuser')+"'>(我自己)</option>";
			for(var i=0;i<data.length;i++)
			{
				name=data[i]["name"];
				if (name==getCookie('mcss_currentCalendarUserStaffName'))
					h+="<strong>"+name+"</strong>";
				else
					h+=name;
				list+="<option value='staffid__"+data[i]['id']+"__"+data[i]['username']+"'";
				if (name==getCookie('mcss_currentCalendarUserStaffName'))
					list+="selected=true";
				list+=">"+name+"</option>";
				$("#mysubstaff").html(list);}
			$("#mysubstaff").change(function(e){
				var arr=this.value.split("__");
				openstaffInfo(this,arr[1],arr[2],getSelectedText(this));
			})
		}
		else
		{
			$("#yuangong").hide();
		}
		
	});
}

function getSelectedText(obj){ 
	for(i=0;i<obj.options.length;i++){   
	   if(obj.options[i].selected==true){   
		return obj.options[i].innerHTML; 
	   }   
	}   
}
//weekStat
function weekStat(beginweek,endweek){

	$.getJSON("__APP__/Oa/Task/weekStat",{'beginweek':beginweek,'endweek':endweek},function(data){
		totel=Number(data[0]['total']);
		done=data[1]['done'];
		$("#mcss_table2").find('tr:eq(2)').find("td:eq(1)").html(done+"/"+totel);
	});
}

function workStat(beginweek,endweek){

	$.getJSON("__APP__/Oa/Task/workStat",{'beginweek':beginweek,'endweek':endweek},function(data){
		var h;
		var temp=1;
		var totel=0;
		var done=0;
		for(var i=0;i<data.length;i++){
			if(i%2==0){
				h=data[i+1]['done']+"/"+data[i]['total'];
				$("#mcss_table2").find('tr:eq(1)').find("td:eq("+temp+")").html(h);	
				temp++;
				//alert(typeof(int(data[i]['total'])));
				totel+=Number(data[i]['total']);
				done+=Number(data[i+1]['done']);
			}
		}
		//$("#mcss_table2").find('tr:eq(2)').find("td:eq(1)").html(done+"/"+totel);
	});
}

function getMonthText(beginmonth,endmonth){
	$.getJSON("__APP__/Oa/Task/monthStat",{'beginmonth':beginmonth,'endmonth':endmonth},function(data){
		h=data[1]['done']+"/"+data[0]['total'];
		$("#mcss_table2").find('tr:eq(3)').find("td:eq(1)").html(h);
		h="<div class='tongji_leibie'><span class='finished'><b>"+data[1]['done']+"</b></br>已完成</span><span class='nofinished'><b>"+data[2]['nodone']+"</b></br>未完成</span><span class='giveup'><b>"+data[3]['fangqi']+"</b></br>已放弃</span></div>";
		$("#mcss_table2").find('tr:eq(4)').find("td:eq(1)").html(h);

	});
}
function openstaffInfo(e,id,user,name)
{
	var executerid = id;
	setCookie("mcss_currentCalendarUserStaffName",name,10);
	setCookie("currentCalendarUserLoginUser",user,10);
	setCookie("currentCalendarUserStaffId",executerid,10);
	currentCalendarUserStaffId=executerid;
	currentCalendarUserLoginUser=user;
	reloadTablesAndCalleder(mccalendar);
}

//创建各种表格和日历,onlyrefreshData表示是刷新数据
function createTablesAndCalleder(mccalendar)
{	
	var date=mccalendar.currentdate;
	var day=mccalendar.getGoodDate(date);
	var beginweek=mccalendar.getWeekStartDate();
	var endweek=mccalendar.getWeekEndDate();
	var beginmonth=mccalendar.getMonthStartDate();
	var endmonth=mccalendar.getMonthEndDate();	

		var week_filter=getWeekDataSql(beginweek,endweek);
		var field_dealing=[{'name':showname,'notes':shownotes}];
		mcssTable_week=new MCSSTable({tableid:"mcss_table_week",filter:week_filter,modelid:"oa_task_week_detail",showfirst:false,defaultValue:"begindate:"+beginweek+",enddate:"+endweek,special_field_show:field_dealing,afterLoadRows:afterrunweek,style:'simple',hideLastTd:false,toolbar:'del'},null,last_td_func_week);
		mcssTable_week.run();
		if($("#showallWeek").size()==0)
			$("#mcss_table_week").after("<div id='WeekToobar' class='bottom_toolbar'><a id='showallWeek' class='showallWeek_btn' title='显示更多' style='display:none'> </a></div>");
			
		$.post("__APP__/Oa/Index/getDayAndWeekTotal",{weekfilter:week_filter},function(data){
			var arr = data.split('|');
			$("#showallWeek").toggle(function(){
				mcssTable_week.pagerows=500;
				$(this).addClass('hideallWeek_btn').attr('title','收起');
				mcsstable_loaddatarows("mcss_table_week");
			},function(){
				mcssTable_week.pagerows=5;
				$(this).removeClass('hideallWeek_btn').attr('title','显示更多');
				mcsstable_loaddatarows("mcss_table_week");
			});
		})
		
		
	
		var filter1="((begindate<=<yh>"+beginmonth+"<yh> and enddate>=<yh>"+beginmonth+"<yh>) or (begindate>=<yh>"+beginmonth+"<yh> and enddate<=<yh>"+endmonth+"') or (begindate<=<yh>"+endmonth+"<yh> and enddate>=<yh>"+endmonth+"<yh>) or (begindate<=<yh>"+beginmonth+"<yh> and enddate>=<yh>"+endmonth+"<yh>)) and (executerid="+currentCalendarUserStaffId+" or SYS_ADDUSER=<yh>"+getCookie('currentCalendarUserLoginUser')+"<yh>) and SYS_ORGID='"+orgid+"'";
		mcssTable_month=new MCSSTable({tableid:"mcss_table_month",filter:filter1,modelid:"oa_task_month_detail",showfirst:false,defaultValue:"begindate:"+beginmonth+",enddate:"+endmonth,special_field_show:field_dealing,afterLoadRows:afterrunmonth,style:'simple',toolbar:'edit,del'});
		mcssTable_month.run();
		
		if($("#MonthToobar").size()==0)
			$("#mcss_table_month").after("<div id='MonthToobar' class='bottom_toolbar'><a id='showallMonth' class='showallWeek_btn'  title='显示更多' style='display:none'> </a></div>");		
		$("#showallMonth").toggle(function(){
			if(mcssTable_month.recordCount>5)
			{
				mcssTable_month.pagerows=500;
				$(this).addClass('hideallWeek_btn').attr('title','收起');
				mcsstable_loaddatarows("mcss_table_month");
			}
		},function(){
			mcssTable_month.pagerows=5;
			$(this).removeClass('hideallWeek_btn').attr('title','显示更多');
			mcsstable_loaddatarows("mcss_table_month");
		});
		
		workStat(beginweek,endweek);
		weekStat(beginweek,endweek);
		getMonthText(beginmonth,endmonth);
		createDayCalendar();
		
}

function checkShowProject(e)
{
	var beginweek=mccalendar.getWeekStartDate();
	var endweek=mccalendar.getWeekEndDate();

	mcssTable_week.filter=getWeekDataSql(beginweek,endweek);
	mcssTable_week.reload();
}

function getWeekDataSql(begindate,enddate)
{
		var filter="((begindate<='"+begindate+"' and enddate>='"+begindate+"') or  (begindate>='"+begindate+"' and enddate<='"+enddate+"') or (begindate<='"+enddate+"' and enddate>='"+enddate+"') or (begindate<='"+begindate+"' and enddate>='"+enddate+"'))"
		+" and (executerid="+currentCalendarUserStaffId+" or (cat='周报' and SYS_ADDUSER='"+getCookie('currentCalendarUserLoginUser')+"')) ";
//		if ($("#checkbox_showProject").attr("checked")==true)
			filter+=" and ((cat='周报' and tag=2) or cat='项目')";
//		else
//			filter+=" and (cat='周报' and tag=2)";
	filter=replaceWithYinhao(filter);
	return filter;
}
//获得某月所属的所有周的第一天和最后一天	
function getMonthWeekFirstEndDate(anyDate)
{

	var MonthFirstDay=new  Date(anyDate.getFullYear(),anyDate.getMonth(),1);

	var oToday=MonthFirstDay;
	var currentDay=MonthFirstDay.getDay();
	if(currentDay==0){currentDay=7}
	var mondayTime=MonthFirstDay.getTime()-(currentDay)*24*60*60*1000;
	var pubegindate=mccalendar.getGoodDate(new Date(mondayTime));

	var year=(oToday.getFullYear());
	var month=(oToday.getMonth()+1);
	var lastday=getDaysOfMonth(year,month);
	oToday=new Date(year,(month-1),lastday);
	currentDay=oToday.getDay();
	var sundayTime=oToday.getTime()+(7-currentDay)*23*60*60*1000;
	var puenddate=mccalendar.getGoodDate(new Date(sundayTime));
	return {firstDate:pubegindate,lastDate:puenddate};
//	var sql="select * from oa_task where"+getWeekDataSql(pubegindate,puenddate);
}

//根据最新数据刷新周\月\日报，不重新创建
function reloadTablesAndCalleder(mccalendar)
{
	var date=mccalendar.currentdate;
	var day=mccalendar.getGoodDate(date);
	var beginweek=mccalendar.getWeekStartDate();
	var endweek=mccalendar.getWeekEndDate();
	var beginmonth=mccalendar.getMonthStartDate();
	var endmonth=mccalendar.getMonthEndDate();	

		var filter0=getWeekDataSql(beginweek,endweek);//"((begindate<=<yh>"+beginweek+"<yh> and enddate>=<yh>"+beginweek+"<yh>) or  (begindate>=<yh>"+beginweek+"<yh> and enddate<=<yh>"+endweek+"<yh>) or (begindate<=<yh>"+endweek+"<yh> and enddate>=<yh>"+endweek+"<yh>) or (begindate<=<yh>"+beginweek+"<yh> and enddate>=<yh>"+endweek+"<yh>)) and (executerid="+currentCalendarUserStaffId+" or SYS_ADDUSER=<yh>"+getCookie('currentCalendarUserLoginUser')+"<yh>) and SYS_ORGID='"+orgid+"'";
		var filter1="((begindate<=<yh>"+beginmonth+"<yh> and enddate>=<yh>"+beginmonth+"<yh>) or  (begindate>=<yh>"+beginmonth+"<yh> and enddate<=<yh>"+endmonth+"<yh>) or (begindate<=<yh>"+endmonth+"<yh> and enddate>=<yh>"+endmonth+"<yh>) or (begindate<=<yh>"+beginmonth+"<yh> and enddate>=<yh>"+endmonth+"<yh>)) and (executerid="+currentCalendarUserStaffId+" or SYS_ADDUSER=<yh>"+getCookie('currentCalendarUserLoginUser')+"<yh>) and SYS_ORGID='"+orgid+"'";
		
		var firsdate=new Date(beginmonth);

		mcssTable_week.filter=filter0;
		mcssTable_week.reload();
		
		mcssTable_month.filter=filter1;
		mcssTable_month.reload();
		
		workStat(beginweek,endweek);
		weekStat(beginweek,endweek);
		getMonthText(beginmonth,endmonth);

	fetchDataAndRefreshAlldays();	
}

//标题提示
function tips(){
	$('.tipsimple').poshytip({
		className: 'tip-green',
		alignX: 'right',
		alignY: 'bottom',
		offsetX: -7,
		offsetY: 16,
		//allowTipHover: false
	});	
}
function last_td(id)
{
	return "<a class='smallbut mcsstable_record_edit' onClick='find_url(this,"+id+")' title='编辑'>编辑</a>";
}

function find_url(obj,id)
{	
	var h="<div id='"+mycalendar.id+"_week'></div>"+"<div class='fuceng_buttom'><a id='saveweek' class='btn btn-green' title='保存'>保存</a></div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,400,400,"编辑");
	form4=new Autoform(mycalendar.id+"_week",{modelid:'oa_task_week_detail',recordid:id});   
	form4.run();
	
	$("#saveweek").click(function(){
		if(form4.getFieldValue('enddate') < form4.getFieldValue('begindate'))
		{
			form4.setFieldValue('enddate',form4.getFieldValue('begindate'));
			form4.save(false,null,false);
			$(mcpopup).remove();
			mcsstable_loaddatarows(mcsstable.tableid);
		}
	})
}

function afterrunweek(table,mccalendar)
{
	$("#mcsstablemcss_table_week_add").parent().hide();
	if($("#WeekToobar").find("#mcsstablemcss_table_week_add").html()==null)
		$("#mcsstablemcss_table_week_add").appendTo($("#WeekToobar"));
	$("#mcss_table_week").find(".mcsstable_record_del:even").hide();
	mcsstable=table;
	$.post("__APP__/Oa/Index/getDayAndWeekTotal",{weekfilter:mcsstable.params.filter},function(data){
		var arr = data.split('|');
		if(arr[0] >　5)
			$("#showallWeek").show();
		else
			$("#showallWeek").hide();
	})
	$("#mcsstable"+mcsstable.tableid+"_add").attr('onclick','');
	$("#mcsstable"+mcsstable.tableid+"_add").click(function()
	{
		var h="<div id='"+mycalendar.id+"_week'></div>"+"<div class='fuceng_buttom'><a onclick='saveWeekPlan()' id='saveweek'class='btn btn-green' title='保存'>保存</a><a onclick='saveWeekPlan(true)' id='mcsstablemcss_table_month_addweek_saveandnew' title='保存并新增' class='btn'>保存并新增</a></div>";
		mcpopup=mcdom.showPopup(this,h,null,null,null,420,390,"新增");
		$('#tr_mcsstablemcss_table_month_addweek_notes').hide();
		form3=new Autoform(mycalendar.id+"_week",{modelid:'oa_task_week_detail'});   
		form3.run(dosomeweek);
	});
	$("#mcsstable"+mcsstable.tableid+"_add").after("<a class='showbg addZongjie' onclick='openWeekReport(this)'><img src='__PUBLIC__/themes/default/images/zhouzongjie.gif' title='周总结'/></a>")
	tips();
}

//保存自定义的周报
function saveWeekPlan(ifNewAfterSave){
	var begindate = form3.getFieldValue('begindate');
	var enddate = form3.getFieldValue('enddate');
	if(begindate == enddate)
	{
		form3.save(false,copyOneDayPlan,false);
	}else if(enddate < begindate )
	{
		alert("结束日期不能小于开始日期");
		return;
	}else
	{
		var f=null;
		if (ifNewAfterSave)
		{
			form3.save(false,afterSaveAndNewWeekPlan,false);
		}
		else
			form3.save(false,afterSaveWeekPlan,false);
		mcsstable_loaddatarows(mcssTable_week.tableid);
		beforeShowData1();
	}
	
}

function afterrunmonth(table,mccalendar)
{
	$("#mcsstablemcss_table_month_add").parent().hide();
	if($("#MonthToobar").find("#mcsstablemcss_table_month_add").html()==null)
		$("#mcsstablemcss_table_month_add").appendTo($("#MonthToobar"));
	mcsstable=table;
	if(mcsstable.recordCount > 5)
		$("#showallMonth").show();
	else
		$("#showallMonth").hide();
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
				form4.save(false,removePopup,false);
			else
			{
				form4.save(false,removePopup,false);
				mcsstable_loaddatarows("mcss_table_month");
			}
		
			
		})
	});
	$("#mcsstable"+mcsstable.tableid+"_add").after("<a class='showbg addZongjie' onclick='openMonthReport(this)'><img src='__PUBLIC__/themes/default/images/zhouzongjie.gif' title='月总结'/></a>")
}

function removePopup(){
	$(mcpopup).remove();
}

function dosomeweek(autoform){
	var begin=mccalendar.getWeekStartDate();
	var end=mccalendar.getWeekEndDate();
	if(!autoform.getFieldValue('begindate'))
		autoform.setFieldValue('begindate',begin);
	if(!autoform.getFieldValue('enddate'))
		autoform.setFieldValue('enddate',end);
	countWeekDayOfDate(begin,"begindate",autoform);
	countWeekDayOfDate(end,"enddate",autoform);
	var loginstaffid=autoform.getFieldValue("executerid");
	if(autoform.getFieldValue("notes"))
		autoform.setFieldVisible('notes',true);
	var a="__APP__/Sys/System/isMySubStaff/staffid/"+loginstaffid;
	$.get("__APP__/Sys/System/isMySubStaff/staffid/"+loginstaffid,function(data){
		if(!data)
		{
			if($("#"+autoform.getFieldID('notes')).val()=="")
				$("#"+autoform.getFieldID('notes')).parent().parent().hide();
			else
				$("#"+autoform.getFieldID('notes')).attr("readonly","readonly");
		}
	});	
	if (!autoform.getFieldValue("projectid"))
		$("#"+autoform.getFieldID('projectid')).parent().parent().parent().hide();
	$("#"+autoform.getFieldID('begindate')).blur(function(){
		begindate =autoform.getFieldValue('begindate');
		countWeekDayOfDate(begindate,"begindate",autoform);
	});
	$("#"+autoform.getFieldID('enddate')).blur(function(){
		enddate =autoform.getFieldValue('enddate');
		countWeekDayOfDate(enddate,"enddate",autoform);
	});
	$("#tr_calendar_week_finishper").hide();
	if (autoform.getFieldValue('SYS_ADDUSER') && autoform.getFieldValue('SYS_ADDUSER')!=getCookie("mcss_loginuser"))
		autoform.setFieldVisible('SYS_ADDUSER',true);

}

function shownotes(fieldvalue,record)
{
	if(fieldvalue=="")
		return "<a href='#' title='无评价' onclick='addnote(this,"+record.id+","+record.executerid+")'><img src='__PUBLIC__/themes/default/images/s.gif' class='noEvaluation'/></a>";
	else
		return "<a href='#' title='"+fieldvalue+"' class='tipsimple'><img src='__PUBLIC__/themes/default/images/s.gif' class='evaluation'/></a>";
}

function addnote(obj,id,executerid)
{
	$.get("__APP__/Sys/System/isMySubStaff/staffid/"+executerid,function(data){
		if(data)
		{
			var h="<div id='"+executerid+"_addnote'></div><div class='fuceng_buttom'><a id='addnote_save' class='btn btn-green' title='保存'>保存</a></div>";
			 mcpopup=mcdom.showPopup(obj,h,null,null,null,320,360,"新增");
			var form5=new Autoform(executerid+"_addnote",{modelid:'oa_task_week_detail',recordid:id});
			form5.run(hide_addnote);
			$("#addnote_save").click(function(){
				form5.save();
				mcsstable_loaddatarows("mcss_table_week");
				$(mcpopup).remove();
			});
		}		
	});
	
}

function hide_addnote()
{
	$("#formobj4_addnote").find("input,textarea,select").parent().parent().hide();
	$("#4_addnote_notes").parent().parent().show();
}

function dosomemonth(){
	var begin=mccalendar.getMonthStartDate();
	var end=mccalendar.getMonthEndDate();
	form4.setFieldValue('begindate',begin);
	form4.setFieldValue('enddate',end);
}
//周报的开始日期和结束日期为同一天,则自动增加一条日报
function copyOneDayPlan(recordid,hint,mcform)
{	
	mcform.canClickSave=true;
	mcform.setFieldValue("cat","日报");
	mcform.setFieldValue("priority","2");
	var begindate=form3.getFieldValue('begindate');
	var enddate=form3.getFieldValue('enddate');
	var finishper=form3.getFieldValue('finishper');
	var name=form3.getFieldValue('name');
	var executerid=form3.getFieldValue('executerid');
	var notes=form3.getFieldValue('notes');
	var ADDUSER=form3.getFieldValue('SYS_ADDUSER');
	var ADDTIME=form3.getFieldValue('SYS_ADDTIME');
	var EDITUSER=form3.getFieldValue('SYS_EDITUSER');
	var EDITTIME=form3.getFieldValue('SYS_EDITTIME');
	if(begindate == enddate && begindate != '' && enddate != '')
	{	
		$.post("__APP__/Oa/Task/insertday",{begindate:begindate,enddate:enddate,finishper:finishper,name:name,executerid:executerid,notes:notes,ADDUSER:ADDUSER,ADDTIME:ADDTIME,EDITUSER:EDITUSER,EDITTIME:EDITTIME},function(data){
			$(mcpopup).remove();
		});
		mcsstable_loaddatarows("mcss_table_week");
		refreshAllday();
	}
	
	//$(mcpopup).remove();
}

function showname(fieldvalue,record)
{
	if(fieldvalue)
	{	
		var newfieldvalue;
		if (fieldvalue.length>50)
			newfieldvalue = fieldvalue.substring(0,50)+"...";
		else
			newfieldvalue = fieldvalue;
		
		if(record.projectname)
			newfieldvalue="["+record.projectname+"] "+newfieldvalue;
		if(record.finishper == 1)
		{
			return "<p title='"+record.name+"' style='cursor:pointer; color:green;' onclick=\"oa_task_week_detail_edit("+record.id+",this)\">"+newfieldvalue+"</p>";
		}else
		{
			return "<p style='cursor:pointer; ' title='"+record.name+"' onclick=\"oa_task_week_detail_edit("+record.id+",this)\">"+newfieldvalue+"</p>";
		}
	}else
	{
		return "<p style='cursor:pointer; ' title='"+fieldvalue+"' onclick=\"oa_task_week_detail_edit("+record.id+",this)\">"+fieldvalue+"</p>";
	}
	
}

function createDayCalendar(mctable)
{	
	if (mccalendar)
	{
	
			var d=mccalendar.currentdate;
			var params={id:'calendar',defaultDate:d,mostRows:6,mostWords:8,onShowDayData:showMyWorkData,showAdd:true,
			onAdd:addTask,onAddMore:addMoreTask,calendartype:'week',refObject:mctable,showCalendartype:true,beforeShowData:beforeShowData,
			hidebutton:true,hideYearMonth:true,afterShowCalendarBody:myAfterShowCalendarBody,showCalendartype:true};
			mycalendar=new McssCalendar(params);
			mycalendar.run();
			
			var d=mccalendar.currentdate;
			var params={id:'weekinmonth',calendartype:'weekinmonth',showAdd:true,onShowDayData:showMyWorkData_week,
			beforeShowData:beforeShowData1,mostRows:10,
			
			afterShowCalendarBody:myAfterShowCalendarBody,mostWords:8};
			monthweeklycalendar=new McssCalendar(params);
			monthweeklycalendar.run();
	}
}

function myAfterShowCalendarBody(){
	$( ".taskitem" ).draggable();
    $( ".date_td" ).droppable({
      drop: function( event, ui ) {
      	updateDate(this,event, ui);
      }
    });
    
    $(".calendarHead").droppable({
      drop: function( event, ui ) {
      	deleteTaskByDrag(this,event, ui);
      }
    });

	bindTaskMenu();
	
	$("#xinshituqiehuan").html($("#calendar_shituqiehuan").html());
	$("#xinshituqiehuan").children(".shitubg").click(function(){
		$("#calendar_shituqiehuan").children("#"+$(this).attr('id')+"").click();
		});
	
}
//时间戳变为日期
function js_date_time(unixtime) {
    var timestr = new Date(parseInt(unixtime) * 1000);
    var datetime = timestr.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
    return datetime;
}
//日期变为时间戳
function js_strto_time(str_time){
    var arr = str_time.split("-");
    var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2]));
    return strtotime = datum.getTime()/1000;
}

//更新移动后的时间
function updateDate(e,event, ui){
	$(".showshortList").remove();
	var begindate = $(e).attr('title');	
	var bd=($(ui.draggable[0]).attr("date"));;
	var ed=($(ui.draggable[0]).attr("enddate"));
	if(ed)
	{
		//计算两个日期的差
		var first = new Date(bd.replace(/-/g,"/"));
		var second = new Date(ed.replace(/-/g,"/"));
		//两个日期差几天
		var days=((second-first)/(24*60*60*1000));
		var dt=js_strto_time(begindate);		
		var enddate1=(dt + (days*24*60*60)); 		
		var enddate=(js_date_time(enddate1));	
	}else{
		var enddate=(js_date_time(enddate1));
	}	
	
	var id = $(ui.draggable[0]).children().children().children('img').attr('recordid');
	$.post("__APP__/Oa/Task/updatemovedate",{begindate:begindate,enddate:enddate,id:id},function(data){
		if (data==0)
		{
			mcdom.alert("移动无效!",'','fail','fadeout');
		}
		else
		{
			refreshAllday();
		}
	})
}

//通过移动删除任务
function deleteTaskByDrag(e,event, ui){
	$(".showshortList").remove();
	var id = $(ui.draggable[0]).children().children().children('img').attr('recordid');
	url="__APP__/Oa/Task/deleteOneTask";
	$.post(url,{id:id},function(data)
	{
		if(data==1)
		{
			mcdom.alert("删除成功!",'删除结果','success','fadeout');
			$(ui.draggable[0]).remove();
		}
	});
	
}


//鼠标经过显示菜单
function bindTaskMenu()
{
	$(".dragsort li").hover(function(){
		var shorMenuhtml="<div class='showshortList'><span>"
		+"<a onclick='clickEditMenu(this);' class='menu_edit' title='编辑'>编辑</a></span>"
		+"<a onclick='clickIsDelMenu(this)' class='menu_del' title='删除'>删除</a>"
		+"<span class='mini-separator'></span>"
		+"<a class='menu_nofinish' onclick='clickEditStatus(this,1,event)' title='把状态改为‘已完成’'>已完成</a>"
		+"<a class='menu_giveup' onclick='clickEditStatus(this,2,event)'  title='把状态改为‘已取消’'>取消</a>"
		+"<a class='menu_finish' onclick='clickEditStatus(this,0,event)'  title='把状态改为‘未完成’'>未完成</a>"
		+"</div>";
		$(this).append(shorMenuhtml);
		$(this).addClass("hover-list").children(".showshortList").show();
	},function(){
		$(this).removeClass("hover-list").children(".showshortList").remove();
	});
}

function clickEditMenu(e)
{
	var li=e.parentNode.parentNode.parentNode;
	editTask(e,$(li).attr("date"),$(li).attr("recordid"),$(li).attr("calendarid"),$(li).attr("cat"),$(li).attr("ADDUSER"));			
	$(".showshortList").remove();
}
var delobj;
function clickIsDelMenu(e)
{
	delobj=e.parentNode.parentNode;
	mcdom.comfirm("确认要删除吗？",clickDelMenu);
}
function clickDelMenu(){
	var recordid=$(delobj).attr("recordid");
	url="__APP__/Oa/Task/deleteOneTask";
	$.post(url,{id:recordid},function(data)
	{
		if(data==1)
			mcdom.alert("删除成功!",'删除结果','success','fadeout');
		$(mcpopup).remove();
	});
	$(".showshortList").remove();	
	refreshAllday();
	fetchData1(showAllDaysData_weekinmonth);
}
//修改记录的状态
function clickEditStatus(e,finishper,event){
	var li=e.parentNode.parentNode;
	editTaskStatus($(li).attr("recordid"),finishper,$(li).find("img").get(0),event);
}

//onshowMyWholePeriodWorkData:showMyWholePeriodWorkData,--全周期调用方法
function clickCalendarDate(title,cal)
{
	if(cal.currentdate-cal.today!=0)
		$("td[title='"+cal.getGoodDate(cal.currentdate)+"']").removeClass('ontoday_day');
	cal.currentdate=cal.newDate(title);
	$("#"+cal.id+"_currentdate").val(title);
	if(cal.currentdate-cal.today!=0)
		$("td[title='"+title+"']").addClass('ontoday_day');
	reloadTablesAndCalleder(cal);	
}

//周报
function openWeekReport(obj,begindate,enddate,info)
{
	var form1;
	var form2;
	var formid=mycalendar.id+"_week_summary";
	var h="<table id='"+mycalendar.id+"week'></table><div id='"+formid+"'></div>"+"<div class='fuceng_buttom'><a id='saveweek' class='btn btn-green' title='保存'>保存</a></div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,200,460,"周总结");
	if(!begindate)
		var begindate=mccalendar.getWeekStartDate();
	if(!enddate)
		var enddate=mccalendar.getWeekEndDate();
	var url="__APP__/Oa/Task/getThisWeekReportId";

	$.post(url,{date:begindate,executerid:currentCalendarUserStaffId},function(id)
	{
		if(id)
		{	
			form2=new Autoform(formid,{modelid:'oa_task_week_summary',recordid:id});   
			form2.run(afterweekreport);
		}
		else
		{	
			form2=new Autoform(formid,{modelid:'oa_task_week_summary',defaultValue:"begindate:"+begindate+",enddate:"+enddate});   
			form2.run(afterweekreport);
		}
	})
	$("#saveweek").click(function()
	{
		form2.save(false,aftersave3,false);		
	});	
}
//周报后执行
function afterweekreport(autoform){
	//隐藏开始时间
	$("#tr_"+autoform.id+"_begindate").hide();
	//隐藏结束时间
	$("#tr_calendar_week_summary_enddate").hide();
	var loginstaffid=autoform.getFieldValue("executerid");
	if(autoform.getFieldValue("id"))
		$("#calendar_week_summary_notes").parent().parent().show();
	$.get("__APP__/Sys/System/isMySubStaff/staffid/"+loginstaffid,function(data){
		if(!data)
		{
			if($("#calendar_week_summary_notes").val()=="")
				$("#calendar_week_summary_notes").parent().parent().hide();
			else
				$("#calendar_week_summary_notes").attr("readonly","readonly");
		}
	});			
}
function aftersave3()
{
	$(mcpopup).remove();
	fetchData1(showAllDaysData_weekinmonth);	
}


//月报
function openMonthReport(obj)
{
	var form1;
	var form2;
	var h="<table id='"+mycalendar.id+"month'></table><div id='"+mycalendar.id+"_month_summary'></div>"+"<div class='fuceng_buttom'><a id='savemonth' class='btn btn-green' title='保存'>保存</a></div>";
	var mcpopup=mcdom.showPopup(obj,h,null,null,null,400,450,"月总结");	//直接调用mcdom.showPopup（定义在dom.js中）可以把html内容以浮层显示出来
	
	var begindate=mycalendar.getMonthStartDate();
	var enddate=mycalendar.getMonthEndDate();
	var url="__APP__/Oa/Task/getThisMonthReportId";
	
	$.post(url,{date:begindate,executerid:currentCalendarUserStaffId},function(id)
	{	
		if(id)
		{
			form2=new Autoform(mycalendar.id+"_month_summary",{modelid:'oa_task_month_summary',recordid:id});   
			form2.run();
		}
		else
		{	
			form2=new Autoform(mycalendar.id+"_month_summary",{modelid:'oa_task_month_summary',defaultValue:"begindate:"+begindate+",enddate:"+enddate});   
			form2.run();
		}
	})
 	var filter="((cat='月报' and tag=2 and executerid="+currentCalendarUserStaffId+") and ((begindate<='"+begindate+"' and enddate>='"+begindate+"') or  (begindate>='"+begindate+"' and enddate<='"+enddate+"') or (begindate<='"+enddate+"' and enddate>='"+enddate+"') or (begindate<='"+begindate+"' and enddate>='"+enddate+"')))";
	
	$("#savemonth").click(function()
	{
		form2.save(false,null,false);
		$(mcpopup).remove();
 	});
}
var mcpopup='';

//日报新增任务
function addTask(cal,date,obj)
{	
	var mctable=cal.params.refObject;
	cal.currentdate= cal.newDate(date);
	var h="<div id='"+cal.id+"_addTask'></div>"
	+"<div class='fuceng_buttom'><a id='"+cal.id+"_save' class='btn btn-green' title='保存'>保存</a>"
	+" <a id='"+cal.id+"_saveandnew' class='btn' title='保存并新增'>保存并新增</a></div>"
	mcpopup=mcdom.showPopup(obj,h,null,null,null,320,360,"新增");	//直接调用mcdom.showPopup（定义在dom.js中）可以把html内容以浮层显示出来
	var savebtn=$("#"+cal.id+"_save").get(0);
 	form1=new Autoform(cal.id+"_addTask",{modelid:'oa_task_add_day1',defaultValue:"begindate:"+date+",enddate:"+date,saveButton:savebtn});   
    form1.run(afterRunForm);
	
	$("#"+cal.id+"_save").click(function()
	{
		form1.setFieldValue("enddate",form1.getFieldValue("begindate"));
		form1.save(false,aftersave1,true);		
 	})
	$("#"+cal.id+"_saveandnew").click(function()
	{
		form1.setFieldValue("enddate",form1.getFieldValue("begindate"));
		form1.save(false,afterSaveAndNewDayTask,true);		
 	})
}


function afterSaveAndNewDayTask()
{
	refreshAllday();
	form1.recordid="";
	form1.reload();
}
function aftersave1()
{
	$(mcpopup).remove();
	refreshAllday();
}

//循环增加固定任务
function addMoreTask(cal,date,obj)
{	
	var mctable=cal.params.refObject;
	cal.currentdate= cal.newDate(date);
	var h="<div id='"+cal.id+"_addTask'></div>"
	+"<div class='fuceng_buttom'><a id='"+cal.id+"_save' class='btn btn-green' title='保存'>保存</a>"
	+" <a id='"+cal.id+"_saveandnew' class='btn' title='保存并新增'>保存并新增</a></div>"
	var mcpopup=mcdom.showPopup(obj,h,null,null,null,340,390,"新增");	//直接调用mcdom.showPopup（定义在dom.js中）可以把html内容以浮层显示出来

 	moretaskform=new Autoform(cal.id+"_addTask",{modelid:'oa_task_add_moreday',defaultValue:"begindate:"+date+",enddate:"});   
    moretaskform.run();
	
	$("#"+cal.id+"_save").click(function()
	{
		addOtherTask(moretaskform,true);
 	})
	$("#"+cal.id+"_saveandnew").click(function()
	{
		//moretaskform.setFieldValue("enddate",moretaskform.getFieldValue("begindate"));
		//moretaskform.save(false,null,true);
		//addMoreTask(cal,date,obj);
		addOtherTask(moretaskform,false);
 	})
}

function addOtherTask(form,bool)
{	
	var begindate=form.getFieldValue("begindate");
	begindate=new Date(begindate.replace(/-/g,"/"));
	var enddate=form.getFieldValue("enddate");
	enddate=new Date(enddate.replace(/-/g,"/"));
	var days=parseInt((enddate - begindate) / (1000*60*60*24));
	var num=parseInt(days/7);
	for(var i=0;i<num;i++)
	{	
		form.canClickSave=true;
		bd=new Date(begindate - 1 + 1 + i*7*24*60*60*1000);
		var begin=mccalendar.getGoodDate(bd);
		form.setFieldValue("begindate",begin);
		form.setFieldValue("enddate",begin);
		form.save(false,null,true);
	}
	refreshAllday();
	if(bool)
	{
		$("#_popup_div").remove();	
		$("#_fuceng_Box").remove();
	}else{
		form.run();
	}

}

//表单创建后进行一些处理，如隐藏某些字段、计算星期几
function afterRunForm(autoform){

	var begindate = autoform.getFieldValue('begindate');
	countWeekDayOfDate(begindate,"begindate",autoform);

	//如果项目为空则隐藏项目
	if($('#calendar_editTask_projectid').val()==''){
		$('#tr_calendar_editTask_projectid').hide();
	}
	if (autoform.modelid=='oa_task_add_day' || autoform.modelid=='oa_task_day')
	{
		$("#"+autoform.getFieldID("begindate")).change(function(){
			begindate = autoform.getFieldValue('begindate','begindate',autoform);
			countWeekDayOfDate(begindate,'begindate',autoform);
		})
		$("#"+autoform.getFieldID("begindate")).blur(function(){
			begindate = autoform.getFieldValue('begindate');
			countWeekDayOfDate(begindate,'begindate',autoform);
		})	
	}
}


//计算星期几
function countWeekDayOfDate(begindate,field,autoform){
	
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

	if(field=="begindate")
	{
		$('#beginday').remove();
		$("#"+autoform.getFieldID('begindate')).css('width','200px');	
		$("#"+autoform.getFieldID('begindate')).after(" <span id='beginday'><font face='微软雅黑'>"+date1+"</font></span>");
	}
	if(field=="enddate")
	{
		$('#endday').remove();		
		$("#"+autoform.getFieldID('enddate')).css('width','200px');	
		$("#"+autoform.getFieldID('enddate')).after(" <span id='endday'><font face='微软雅黑'>"+date1+"</font></span>");
	}
}

//编辑任务
function editTask(obj,date,recordid,calid,cat,SYS_ADDUSER)
{
	cal=McssCalendar_getMcssCalendar(calid);
	cal.currentdate= cal.newDate(date);
	if(getCookie('mcss_loginuserrole') == 'admin' || SYS_ADDUSER == getCookie('mcss_loginuser'))
	{
		var h="<div id='"+calid+"_editTask'></div>"+"<div class='fuceng_buttom'><a class='btn btn-green' id='"+calid+"_save' title='保存'>保存</a> "+" <a id='"+calid+"_delete' href='javascript:void(0)'  class='btn' title='重置'>重置</a></div>"
	}else
	{
		var h="<div id='"+calid+"_editTask'></div>"+"<div class='fuceng_buttom'><a id='"+calid+"_save' href='javascript:void(0)' class='btn btn-green'>保存</a></div>"
		
	}
	var mcpopup=mcdom.showPopup(obj,h,null,null,null,400,380,"编辑");//直接调用mcdom.showPopup（定义在dom.js中）可以把html内容以浮层显示出来
	
	var modelid="oa_task_day";
	if(cat=="日")
		modelid="oa_task_day";
	else
	if(cat=="月")
		modelid="oa_task_month_detail";
	if(cat=="周")
		modelid="oa_task_week_detail";
	else if(cat=="项")
		modelid="oa_task_week_detail";
		
 	form1=new Autoform(calid+"_editTask",{modelid:modelid,recordid:recordid,defaultValue:"begindate:"+date+",enddate:"+date,saveButton:$("#"+calid+"_save").get(0)});   
    form1.run(afterRunForm);
	
	$("#"+calid+"_save").click(function(){
		//form1.setFieldValue("enddate",form1.getFieldValue("begindate"));
		form1.save(false,refreshAllday,true);
		$(mcpopup).remove();
 	});
	$("#"+calid+"_delete").click(function(){
		$("#calendar_editTask_name,#calendar_editTask_notes").val("");
	});
}

//完与未完状态修改
function editTaskStatus(id,finishper,obj,event)
{
	if(!event)
		var event = event || window.event;
	event.cancelBubble=true;//对火狐有效,火狐是通过方法传参生成event的,引用地方写了event,这里漏了event
	if (finishper==0)
	{
		obj.className="work_notfinal";
		obj.title="该工作未完成";
	}
	else if (finishper==1)
	{
		obj.className="work_finish";
		obj.title="该工作已完成";
	}
	else if (finishper==2)
	{
		obj.className="work_giveup";
		obj.title="该工作已放弃";
	}
	var url="__APP__/Oa/Task/changeTaskStatus";
	
	$.post(url,{id:id,status:finishper},function(data)
	{
	});
	$(".showshortList").remove();
	
}

function GetAbsPoint(e)
{
	var oRect = e.getBoundingClientRect();
	return {
		top: oRect.top,
		left: oRect.left,
		width: e.offsetWidth,
		height: e.offsetHeight,
		bottom: oRect.bottom,
		right: oRect.right
		}
}
function refreshThisDay(calendar)
{
		var	date=calendar.currentdate;//当前日期js格式
		var date_str=calendar.getGoodDate(date);//把js格式转换成字符串
		var day=date.getDate();//得到今天是几号
		var h=showMyWorkData(calendar,date_str,day,calendar.params.refObject);
		$("#"+mycalendar.id+"_day_"+day).html(h);
		calendar.BindAdd();
		bindTaskMenu();
}

//刷新日报当前日期的格子
function refreshAday(date_str,day)
{
	fetchData(mycalendar,refreshThisDay);
}
//刷新统计工作
function refreshCount(){
	var beginweek=mycalendar.getWeekStartDate();
	var endweek=mycalendar.getWeekEndDate();
	var beginmonth=mycalendar.getMonthStartDate();
	var endmonth=mycalendar.getMonthEndDate();
	setTimeout(workStat(beginweek,endweek),5000);
	setTimeout(weekStat(beginweek,endweek),5000);
	setTimeout(getMonthText(beginmonth,endmonth),5000);
	
}
//刷新日报所有的格子
function refreshAllday()
{
	beforeShowData(mycalendar);
}
 //得到每周的第N天(当i=0时为周日，当i=1时为周1，当i=2时为周2，当i=3时为周3。。。。。)
function mousedownTaskDateOfWeek(theDate,i){
	var firstDateOfWeek;
	var dd=theDate.getDate()+i - theDate.getDay();
	theDate.setDate(theDate.getDate()+i - theDate.getDay()); 
	firstDateOfWeek = theDate;
	return firstDateOfWeek;	
}

//得到每月有多少天
function getDaysInMonth(year,month){
      month = parseInt(month,10)+1;
      var temp = new Date(year+"/"+month+"/0");
      return temp.getDate();
}
 //得到每周的第N天是多少号(当i=0时为周日是几号，当i=1时为周1是几号。。。。。)
function Ndays(theDate,i){
	var Nday;
	var Nday=theDate.getDate()+i - theDate.getDay();
	return Nday;	
}
//新增周计划
function addWeekPlan(e,wbegindate,wenddate)
{
	var h="<div id='12_week'></div>"+"<div class='fuceng_buttom'><a onclick='saveWeekPlan()' id='saveweek'class='btn btn-green' title='保存'>保存</a><a onclick='saveWeekPlan(true)' id='mcsstablemcss_table_month_addweek_saveandnew' title='保存并新增' class='btn'>保存并新增</a></div>";
	mcpopup=mcdom.showPopup(e,h,null,null,null,420,390,"新增");
	$('#tr_mcsstablemcss_table_month_addweek_notes').hide();
	form3=new Autoform("12_week",{modelid:'oa_task_week_detail',defaultValue:"begindate:"+wbegindate+",enddate:"+wenddate});   //,defaultValue:"begindate:"
	form3.run(dosomeweek);
}
//显示周计划
function showMyWorkData_week(calendar,date,day,refObject)
{	
	var data=monthweeklycalendar.params.data;
	var taskCount=0;
	var thisbegindate= new Date(Date.parse(date.replace(/-/g,"/")));  //本周的开始日期
	var thisenddate=thisbegindate.addDays(6);
	thisbegindate=calendar.getGoodDate(thisbegindate);
	thisenddate=calendar.getGoodDate(thisenddate);
	var j=1;
	
	day="<a style='cursor:pointer; padding-top:2px; float:right' class='plus showbg' title='添加周计划'>";
	day+="<img src='__PUBLIC__/themes/default/images/add.gif'/></a>";
	var h="<div class='addIco' onclick=\"addWeekPlan(this,'"+thisbegindate+"','"+thisenddate+"')\"><span style='text-align:right;'></span>"+day+"</div><ul class='dragsort' style='height:'>";
	for(var i=0;i<data.length;i++)
	{
		var begindate="";
		if (data[i].begindate && data[i].begindate !="0000-00-00")
			begindate=data[i].begindate;
		var enddate="";
		if (data[i].enddate && data[i].enddate !="0000-00-00")
			enddate=data[i].enddate;
		if ((enddate>=thisbegindate && enddate<=thisenddate)
		|| (begindate>=thisbegindate && begindate<=thisenddate)
		|| (begindate<=thisbegindate && enddate>=thisenddate)
		)
		{	
			if (taskCount<calendar.params.mostRows)
			{
				var temp=data[i].name;
				var newtemp = data[i].name;
				var cat=data[i].cat.substr(0,1);

				if (calendar.params.mostWords && data[i].name.length>calendar.params.mostWords)
				{
					if(get_length(data[i].name) < calendar.params.mostWords)
						temp = data[i].name;
					else
						temp=cut_str(data[i].name,calendar.params.mostWords)+"...";
				}
				var today=begindate;
				var title='';
				var css="";
				if(data[i].finishper==1)
				{
					title='该工作已完成';
					css='work_finish';
				}
				else
				if(data[i].finishper==2)
				{
					title='该工作已放弃';
					css='work_giveup';
				}
				else
				{
					title='该工作未完成';
					css='work_notfinal';
				}
				//ssss
				var attr=" date='"+today+"' enddate='"+enddate+"' recordid='"+data[i]['id']+"' calendarid='"+calendar.id+"' cat='"+cat+"' ADDUSER='"+data[i].SYS_ADDUSER+"'";
				h+="<li class='taskitem' "+attr+" >"
					+"<a class='labellink' title="+ newtemp + ">"
					+"<sapn><image src='__PUBLIC__/themes/default/images/s.gif' class='task_status "+css+"' "
					+" title='"+title+"' recordid='"+data[i].id+"' />"
					+"</span>"+ temp+"</a></li>";
				
				taskCount++;
				j++;
			}
			else
			{	
//				h+="<p style='text-align:right; padding:2px 3px;'><a id='morereport' href='javascript:void(0)' onclick=\"morereport('"+date+"',"+currentCalendarUserStaffId+")\" title='点击更多'><img src='__PUBLIC__/themes/default/images/more.gif'/></a></p>";
				break;
			}
		}
	}
	h+="</ul>";
	var zongjie=showWeekSummary(thisbegindate,thisenddate);
	h+=zongjie;
	return h;
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
//月里的周总结
function showWeekSummary(wbegindate,wenddate)
{
	var data=zongjiedata;	
	var thisdate=wbegindate;
	var thisenddate=wenddate;

	var w1="<p>总结<a onclick=\"openWeekReport(this,'"+thisdate+"','"+thisenddate+"')\" style='cursor:pointer; padding-top:3px; padding-right:3px; float:right' class='plus showbg' title='周总结'>";
	w1+="<img src='__PUBLIC__/themes/default/images/zhouzongjie.gif'/></a></p>";
	var h="<div class='yueshitu_zhouzongjie'>"+w1+"<div class='yueshitu_zhouzongjie_text'>";
	for(var i=0;i<data.length;i++)
	{
		var begindate="";
		if (data[i].begindate && data[i].begindate !="0000-00-00")
			begindate=data[i].begindate;
		var enddate="";
		if (data[i].enddate && data[i].enddate !="0000-00-00")
			enddate=data[i].enddate;
		if (thisdate == begindate && enddate == thisenddate)
		{	
			var temp=data[i].name;
			var newtemp = data[i].name;
			var cat=data[i].cat.substr(0,1);
			
			if(data[i].name.length < 50)
				temp = data[i].name;
			else
				temp=data[i].name.substr(0,50)+"...";
			
			var today=thisdate;
			h+=	temp;
		}
	}
	h+="</div></div>";
	return h;	
}

 //显示
function showMyWorkData(calendar,date,day,refObject)
{
	var data=calendar.params.data;
	day+="<a style='cursor:pointer; padding-top:2px; float:right' class='plus showbg' title='添加工作计划'>";
	day+="<img src='__PUBLIC__/themes/default/images/add.gif'/></a>";
	var h="<div class='addIco'><span style='text-align:right;'></span>"+day+"</div><ul class='dragsort'>";
	var taskCount=0;
	var thisdate= new Date(Date.parse(date.replace(/-/g,"/")));  
	var j=1;

	var periodBegin=calendar.firstDate;
	var periodEnd=calendar.lastDate;

	for(var i=0;i<data.length;i++)
	{		
		var begindate="";

		if (data[i].begindate && data[i].begindate !="0000-00-00")
			begindate=new Date(Date.parse(data[i].begindate.replace(/-/g,"/")));  
		var enddate="";
		if (data[i].enddate && data[i].enddate !="0000-00-00")
			enddate=new Date(Date.parse(data[i].enddate.replace(/-/g,"/")));  
		if ((begindate && enddate && (thisdate - begindate)>=0 && (thisdate - enddate) <=0)||(begindate && !enddate  && (thisdate - begindate)==0 )||(!begindate && enddate  && (thisdate - enddate)==0 ))
		{	
			if ((periodBegin - begindate)>=0 && (periodEnd - enddate) <=0)
				continue;
			if (taskCount<calendar.params.mostRows)
			{
				var temp=data[i].name;
				var newtemp = data[i].name;
				var cat=data[i].cat.substr(0,1);

				if (calendar.params.mostWords && data[i].name.length>calendar.params.mostWords)
				{
					if(data[i].name.length == 9)
						temp = data[i].name;
					else
						temp=data[i].name.substr(0,calendar.params.mostWords)+"...";
				}
				var today=calendar.getGoodDate(thisdate);
				var title='';
				var css="";
				if(data[i].finishper==0)
				{
					title='该工作未完成';
					css='work_notfinal';
				}
				else
				if(data[i].finishper==1)
				{
					title='该工作已完成';
					css='work_finish';
				}
				else
				if(data[i].finishper==2)
				{
					title='该工作已放弃';
					css='work_giveup';
				}
								
				var attr=" date='"+today+"' recordid='"+data[i]['id']+"' calendarid='"+calendar.id+"' cat='"+cat+"' ADDUSER='"+data[i].SYS_ADDUSER+"'";
				h+="<li class='taskitem' "+attr+" >"
					+"<a class='labellink' title="+ newtemp + ">"
					+"<sapn><image src='__PUBLIC__/themes/default/images/s.gif' class='task_status "+css+"' "
					+" title='"+title+"' recordid='"+data[i].id+"' />"
					+"</span>"+ temp+"</a></li>";
				

				taskCount++;
				j++;
			}
			else
			{	
				h+="<p style='text-align:right; padding:2px 3px;'><a id='morereport' href='javascript:void(0)' onclick=\"morereport('"+date+"',"+currentCalendarUserStaffId+")\" title='点击更多'><img src='__PUBLIC__/themes/default/images/more.gif'/></a></p>";
				break;
			}
		}
	}
	h+="</ul>";
	return h;
}


//获取没有有移动时的时间
function mousedownTask(obj){
	firstDate = $(obj).parent().parent().attr("title");
}
//显示整个周期的任务列表，例如周视图中，开始日期与结束日期覆盖了本周的任务就属于整个周期的任务，不用显示在单个日期格子中，而应该显示在日历下方的“整周任务”栏中
function showMyWholePeriodWorkData(calendar,refObject)
{
	var date="2012-03-03";
	var data=calendar.params.data;
 
	var h="";
	var taskCount=0;
	var thisdate= new Date(Date.parse(date.replace(/-/g,"/")));  
	
	var periodBegin=calendar.firstDate;
	var periodEnd=calendar.lastDate;	
	
	var j=1;
	var mcsstable=refObject;
	for(var i=0;i<data.length;i++)
	{		
		var begindate="";

		if (data[i].begindate && data[i].begindate !="0000-00-00")
			begindate=new Date(Date.parse(data[i].begindate.replace(/-/g,"/")));  
		var enddate="";
		if (data[i].enddate && data[i].enddate !="0000-00-00")
			enddate=new Date(Date.parse(data[i].enddate.replace(/-/g,"/")));
		//日期的开始日期小于等于periodBegin，且任务的结束日期大于等于periodEnd的才显示在“整周期任务列表”中

		if (begindate && enddate && (periodBegin - begindate)>=0 && (periodEnd - enddate) <=0)
		{	
			if (taskCount<calendar.params.mostRows)
			{
				var temp=data[i].name;
				var newtemp = data[i].name;
				var cat=data[i].cat.substr(0,1);
				if (calendar.params.mostWords && data[i].name.length>calendar.params.mostWords)
				{
					temp=data[i].name.substr(0,calendar.params.mostWords)+"...";
				}
				var today=calendar.getGoodDate(thisdate);

				if(data[i].finishper<1)
					h+="<a class='labellink' href='javascript:void(0)' onClick=\"editTask('"+mcsstable.params.homeUrl+"','"+today+"',this,'"+ data[i]['id']+"','"+calendar.id+"','"+cat+"','"+data[i].SYS_ADDUSER+"')\" title="+ newtemp + ">"+j+"."+ temp+"<font color='red'>["+cat+"]</font>"+"</a>";
				else
					h+="<a style='color:green' class='labellink' href='javascript:void(0)' onClick=\"editTask('"+mcsstable.params.homeUrl+"','"+today+"',this,'"+ data[i]['id']+"','"+calendar.id+"','"+cat+"','"+data[i].SYS_ADDUSER+"')\" title="+ newtemp + ">"+j+"."+ temp+"<font color='red'>["+cat+"]</font>"+"</a>";

				taskCount++;
				j++;
			}
			else
			{	
				var sbegin=mccalendar.getGoodDate(periodBegin);
				var send=mccalendar.getGoodDate(periodEnd);
				h+="<p style='text-align:right; padding:2px 3px;'><a id='morereport' href='javascript:void(0)' onclick=\"morereport1('"+sbegin+"','"+send+"',"+currentCalendarUserStaffId+")\"><img src='__PUBLIC__/themes/default/images/more.gif'/></a></p>";
				break;
			}
		}
	}
	h="<div style='text-align:left;height:130px;'>"+h+"</div>";
	return h;
}

var popp;
function ShowIframe(url, w, h, t) //显示有滚动条的iframe
{
    var pop = new Popup({
        contentType: 1,
        scrollType: 'no',
        isReloadOnClose: false,
        width: w,
        height: h
    });
    pop.setContent("contentUrl", url);
    pop.setContent("title", t);
    pop.build();
    pop.show();
    popp = pop;
}

function morereport(date,staffid)
{
	var url = "__APP__/Oa/Task/morereport/date/"+date+"/staffid/"+staffid;
	MCDom.prototype.showIframe(url);
	//var code="$.dialog({title:'日报',content: 'url:"+url+"',width:700,height:400,lock:true,background:'#FFF',opacity:0.5,})"
	//new Function(code).call(window);
}

function morereport1(periodBegin,periodEnd,staffid)
{	
	var url = "__APP__/Oa/Task/morereport/periodBegin/"+periodBegin+"/periodEnd/"+periodEnd+"/staffid/"+staffid;
	var code="$.dialog({title:'全周期任务',content: 'url:"+url+"',width:700,height:400,lock:true,background:'#FFF',opacity:0.5,})"
	new Function(code).call(window);
}

//获取所有数据并刷新所有日期
function beforeShowData(calendar)
{
	fetchData(showAllDaysData);
	fetchData1(showAllDaysData_weekinmonth);
}
//获取周计划所有数据并刷新所有日期
function beforeShowData1(calendar)
{
	if(calendar)
		$("#"+calendar.id+"_yearmonth").hide();
	fetchData1(showAllDaysData_weekinmonth);
}

//获取所有数据并刷新当前日期
function fetchDataAndRefreshAlldays()
{
	mycalendar.changeCurrentDate(mccalendar.currentdate);
	fetchData(showAllDaysData);
	
	//刷新本月的周报
	monthweeklycalendar.changeCurrentDate(mccalendar.currentdate);
	fetchData1(showAllDaysData_weekinmonth);
	
}
function showAllDaysData(calendar)
{
	calendar.showCalendarBody();
}
function showAllDaysData_weekinmonth(calendar)
{
	calendar.showCalendarBody();
}
var pubegindate,puenddate;
function fetchData1(show)
{
	var monthkeeks=getMonthWeekFirstEndDate(mccalendar.currentdate);
	var sql="select * from oa_task where"+getWeekDataSql(monthkeeks.firstDate,monthkeeks.lastDate);
	$.getJSON("__APP__/Sys/System/getJSONbysql",{sql:sql},function(data){ 
		monthweeklycalendar.params.data=data;
		var sql="SELECT * FROM `oa_task` WHERE executerid="+currentCalendarUserStaffId+" and SYS_ADDUSER=<yh>"+getCookie('currentCalendarUserLoginUser')+"<yh> and cat=<yh>周报<yh> and tag=<yh>3<yh> and begindate>=<yh>"+monthkeeks.firstDate+"<yh> and enddate<=<yh>"+monthkeeks.lastDate+"<yh>";
		$.getJSON("__APP__/Sys/System/getJSONbysql",{sql:sql},function(data){ 
			zongjiedata=data;
			if (show)
				show(monthweeklycalendar);	
			mcsstable_loaddatarows("mcss_table_week");
		})	
	});
}

//把引号转化下，到后台再转回来，否则传输错误
function replaceWithYinhao(s)
{
	return s.replace(/'/gi,"<yh>");
}
function fetchData(show)
{
	var calendar=mccalendar;
	$("#calendar_header").hide();
	var begindate;
	var enddate;
	if (mycalendar.params.calendartype=="day")
	{
		begindate=calendar.currentdate;
		enddate=begindate; 
		begindate=calendar.getGoodDate(begindate);
		enddate=calendar.getGoodDate(enddate);
	}
	else if (mycalendar.params.calendartype=="week")
	{
		var d1=calendar.currentdate;
		var weekday=d1.getDay();
		if (weekday!=0)//如果不是周日
			d1= d1.addDays(weekday*-1); 
		begindate=d1;
		enddate=d1.addDays(7); 		
		begindate=calendar.getGoodDate(begindate);
		enddate=calendar.getGoodDate(enddate);
	}
	else if (mycalendar.params.calendartype=="monthweek" || mycalendar.params.calendartype=="month" )
	{
		begindate=mycalendar.getMonthStartDate();
		enddate=mycalendar.getMonthEndDate();
	}
	var sql=getDailyWorkDataSql(begindate,enddate,'日报');
	$.getJSON("__APP__/Sys/System/getJSONbysql",{sql:sql},function(data){ 
		mycalendar.params.data=data;
		if (show)
			show(mycalendar);
	})
	
}

function getDailyWorkDataSql(begindate,enddate,type)
{
	var sql="select * from oa_task where ((executerid="+currentCalendarUserStaffId+" or SYS_ADDUSER=<yh>"+getCookie('currentCalendarUserLoginUser')+"<yh>) and cat=<yh>"+type+"<yh>) and ((begindate<=<yh>"+begindate+"<yh> and enddate>=<yh>"+begindate+"<yh>) or  (begindate>=<yh>"+begindate+"<yh> and enddate<=<yh>"+enddate+"<yh>) or (begindate<=<yh>"+enddate+"<yh> and enddate>=<yh>"+enddate+"<yh>) or (begindate<=<yh>"+begindate+"<yh> and enddate>=<yh>"+enddate+"<yh>)) and tag=2 and sys_orgid="+orgid+" order by cat asc";
	return sql; 
}
function allscreen(obj)
{
	obj.name='allscreen';	
	var screenW=screen.width-12;var screenH=screen.availHeight-70;
	window.open("__APP__/Oa/Task/mycalendar/view/calendar",'newwindow','Width='+screenW+',height='+screenH+', top=0,left=0, alwaysRaised=yes,z-look=yes');
}
function printCalendar(){
	var url=window.location.href; 
	var view=getParamValue(location.href,'view')
	if(view){
		window.print();
	}else{
		$(".todayWork").hide();	
		window.print();
		
	}
	if(!view){
		$(".todayWork").show();	
	}
}

</script>