//项目管理公共js代码
//alert("pm.js");
//mcss_importJS("jusaas/js/MCDateTime.js");	

var Gantetus=new Array;
function getGantetu(id){
	var r;
	for(var i=0;i<Gantetus.length;i++){
		if (Gantetus[i].id==id)
		{
			return Gantetus[i].data;			
		}
	}
	return r;
}

function McssGantetu(id,params)
{
	Gantetus.push({id:id,data:this});

	this.id=id;
	this.params=params;
	if (!this.params.timespan)
		this.params.timespan="month";//时间周期，月或周
	this.tableid="gantetu_"+this.id;	
	var _this=this;
	this.run=function()
	{
		mcss_importJS("jusaas/js/JSData.js");
		mcss_importJS("jusaas/js/MCDateTime.js");

		var _this=this;
		$("#"+id).html("");
		this.today=new Date();
		this.todaystr=MCDateTime.getGoodDate(this.today);
		if (!this.params.begindate)
		{
			var m=this.today.getMonth();
			m++;
			this.params.begindate=MCDateTime.newDate(this.today.getFullYear()+"-"+m+"-1");
			m++;
			this.params.enddate=MCDateTime.newDate(this.today.getFullYear()+"-"+m+"-1");
			this.params.enddate=this.params.enddate.addDays(-1);
			//this.params.enddate=this.params.enddate.addDays(300);//测试
		}
		if (!this.params.year)
		{
			this.params.year=this.today.getFullYear();
		}
		if (!this.params.month)
		{
			this.params.month=this.today.getMonth()+1;
		}

		this.createGantetu();
	},
	//点击时间线条来改变开始结束日期
	this.clickTimeLine=function(e)
	{
		if (this.mcsstable.view!="tableedit")
			return;
			
			var thisDate1=e.parentNode.parentNode.rows[0].cells[e.cellIndex].title;
			//处理thisDate1= "今天:2013-07-13"
			if (thisDate1.indexOf(":")>0)
			{
				thisDate1=thisDate1.substr(thisDate1.indexOf(":")+1);
			}
			if (!thisDate1)	
				return;
			
			var row=e.parentNode;
			thisDate=MCDateTime.newDate(thisDate1);
			var recordid=$(e.parentNode).attr("recordid");	
			var record=this.getRow(recordid);
			var begindate=record.begindate;
			if (begindate==null) begindate="";
			var enddate=record.enddate;
			if (enddate==null) enddate="";
			
			if (begindate=="" && enddate!="")
			{
				this.editField(recordid,"begindate",enddate);
			}
			if (begindate!="" && enddate=="")
			{
				this.editField(recordid,"enddate",begindate);
			}
			
			if ((begindate=="0000-00-00" || begindate=="" || begindate==null) && (enddate=="0000-00-00" || enddate=="" || enddate==null))
			{
				this.editField(recordid,"begindate",thisDate1);
				this.editField(recordid,"enddate",thisDate1);
			}

			else
			if (begindate=="" && enddate=="")
			{
				this.editField(recordid,"begindate",thisDate1);				
				this.editField(recordid,"enddate",thisDate1);				
			}


			else
			if (thisDate1<begindate)
			{
				this.editField(recordid,"begindate",thisDate1);				
			}

			else
			if (thisDate1>enddate)
			{
				this.editField(recordid,"enddate",thisDate1);				
			}

			else
			if (begindate!="" && enddate==""  && thisDate1<begindate)
			{
				this.editField(recordid,"begindate",thisDate1);				
			}

			else
			if (begindate!="" && enddate==""  && thisDate1>enddate)
			{
				this.editField(recordid,"enddate",thisDate1);				
			}


			else
			if (thisDate1<begindate)
			{
				this.editField(recordid,"begindate",thisDate1);				
			}

			else
			if (thisDate1>enddate)
			{
				this.editField(recordid,"enddate",thisDate1);				
			}

			else
			if (begindate!="" && thisDate1<begindate)
			{
				this.editField(recordid,"begindate",thisDate1);				
			}
			else
			if (enddate!="" && thisDate1>enddate)
			{
				this.editField(recordid,"enddate",thisDate1);				
			}

			else
			if (begindate=="" && enddate!="")
			{
				this.editField(recordid,"begindate",enddate);				
			}
			else
			if (begindate!="" && enddate=="")
//				enddate=begindate;
				this.editField(recordid,"enddate",begindate);				
			else
			{
			begindate=MCDateTime.newDate(begindate);
			enddate=MCDateTime.newDate(enddate);
			var diff=MCDateTime.diffDays(begindate,enddate);//任务的开始日期与第一个格子间的格子数量
			var middate=begindate.addDays(diff/2);//中间日期
			
			if (thisDate<begindate)
			{
				var newdate=MCDateTime.getGoodDate(thisDate);
				this.editField(recordid,"begindate",newdate);
			}
			else if (thisDate>enddate)
			{
				var newdate=MCDateTime.getGoodDate(thisDate);
				this.editField(recordid,"enddate",newdate);
			}
			else if (thisDate<middate)
			{
				var newdate=MCDateTime.getGoodDate(thisDate.addDays(1));
				this.editField(recordid,"begindate",newdate);
			}
			else
			{
				if (MCDateTime.getGoodDate(begindate)==MCDateTime.getGoodDate(enddate))
				{
					this.editField(recordid,"begindate","");
					this.editField(recordid,"enddate","");
				}
				else
				{
					var newdate=MCDateTime.getGoodDate(thisDate.addDays(-1));
					this.editField(recordid,"enddate",newdate);
				}
			}
			}
			record=this.getRow(recordid);			
			this.addColorForTimeline(row,record);
				
	}
	
	this.getRow=function(recordid)
	{
		if (typeof(recordid)==='number')
			return this.mcssData.data[recordid];
		
		for(var i=0;i<this.mcssData.data.length;i++)
		{
			if (this.mcssData.data[i]['id']==recordid)
				return this.mcssData.data[i];
		}
	}
	this.editField=function(recordid,fieldid,value)
	{
		
		for(var i=0;i<this.mcssData.data.length;i++)
		{
			if (this.mcssData.data[i]['id']==recordid)
			{
				this.mcssData.data[i][fieldid]=value;
				this.mcsstable.cellEditor.showFieldValueInCell(recordid,fieldid);
				
				break;
			}
		}
	}


}
//查看下一月
McssGantetu.prototype.next_month=function()
{
	var p=this.get_next_month_params();
	var _this=this;
	_this=new McssGantetu(this.id,p);
	_this.run();
}
//查看下一月
McssGantetu.prototype.get_next_month_params=function()
{
	var p=this.params;
	var year=p.begindate.getFullYear();
	var month=p.begindate.getMonth();
	month++;
	if (month==12)
	{
		year++;
		month=1;
	}
	else
		month++;
	p.begindate=MCDateTime.newDate(year+"-"+month+"-1");
	if (month==12)
	{
		year++;
		month=1;
	}
	else
		month++;
	p.enddate=MCDateTime.newDate(year+"-"+month+"-1");
	p.enddate=p.enddate.addDays(-1);
	return p;
}

McssGantetu.prototype.get_prev_month_params=function()
{
	var p=this.params;
	var year=p.begindate.getFullYear();
	var month=p.begindate.getMonth();
	month++;
	if (month==1)
	{
		year--;
		month=12;
	}
	else
		month--;
	p.begindate=MCDateTime.newDate(year+"-"+month+"-1");
	if (month==12)
	{
		year++;
		month=1;
	}
	else
		month++;
	p.enddate=MCDateTime.newDate(year+"-"+month+"-1");
	p.enddate=p.enddate.addDays(-1);
	return p;

}

//查看上一月
McssGantetu.prototype.prev_month=function()
{
	var p=this.get_prev_month_params();
	var _this=this;
	_this=new McssGantetu(this.id,p);
	_this.run();

}	

//查看上周
McssGantetu.prototype.prev_week=function()
{
	var p=this.params;
	p.begindate=p.begindate.addDays(-7);
	p.enddate=p.begindate.addDays(6);
	
	var _this=this;
	_this=new McssGantetu(this.id,p);
	_this.run();

}	

//查看上周
McssGantetu.prototype.next_week=function()
{
	var p=this.params;
	p.enddate=p.enddate.addDays(7);
	p.begindate=p.enddate.addDays(-6);
	
	var _this=this;
	_this=new McssGantetu(this.id,p);
	_this.run();

}	

McssGantetu.prototype.createGantetuOption=function()
{
	if ($("#"+this.id+"_gantetuoption").size()>0)
		return;
	var s="";	
	s+="<span id='"+this.id+"_gantetuoption' style='float:right;'>";
	s+="  显示："
	+"<input type='checkbox' id='gentetu_option_showtasktype"+this.id+"'";
	if (this.params.timelinetext && this.params.timelinetext.indexOf('TYPE')>-1)
		s+=" checked='checked'";
	s+="><label for='gentetu_option_showtasktype"+this.id+"'>工作类别</label>";

	s+=" <input type='checkbox' id='gentetu_option_showtaskname"+this.id+"'";
	if (this.params.timelinetext && this.params.timelinetext.indexOf('TASK')>-1)
		s+=" checked='checked'";
	s+="><label for='gentetu_option_showtaskname"+this.id+"'>任务名称</label>";
	s+=" <input type='checkbox' id='gentetu_option_showexecuter"+this.id+"'";
	if (this.params.timelinetext && this.params.timelinetext.indexOf('TASK')>-1)
		s+=" checked='checked'";
	s+="><label for='gentetu_option_showexecuter"+this.id+"'>执行人</label>"	;

	s+="  时间线跨度:"
	+"<select id='time_select'>"
	+"<option value='time_thismonth'>月</option>"
	+"<option value='time_thisweek'>周</option>"
//	+"<option value='time_premonth'>上月</option>"
//	+"<option value='time_nextmonth'>下月</option>"
	+"<option value='time_project'>项目起止</option>"
	+"</select>"
	+"</span>";
		
	
	//$("#"+this.tableid).find('caption').find('div').eq(0).append(s);
	$("#"+this.tableid).before(s);

	
	var _this=this;
	
	$("#gentetu_option_showtasktype"+this.id).click(function(){
		if (this.checked)
		{
			$(".gantetu_tasktype").show();
			_this.showTaskType=true;
		}
		else
		{	
			$(".gantetu_tasktype").hide();
			_this.showTaskType=true;
		}
	})
	$("#gentetu_option_showtaskname"+this.id).click(function(){
		if (this.checked)
		{
			$(".gantetu_taskname").show();
			_this.showTaskName=true;
		}
		else	
		{
			$(".gantetu_taskname").hide();
			_this.showTaskName=false;
		}
		
	})
	$("#gentetu_option_showexecuter"+this.id).click(function(){
		if (this.checked)
		{
			$(".gantetu_executer").show();
			_this.showExecuter=true;
		}
		else
		{	
			$(".gantetu_executer").hide();
			_this.showExecuter=true;
		}
		
	})
	var _this=this;
	$("#editproject_btn_"+this.id).click(function(){
		var e=this;
		var detailproject_modelid = _this.params.detailproject_modelid;
		var project_id = _this.params.project_id;
		var s="<div id='"+_this.id+"_autoform'></div>"
		+"<div class='fuceng_buttom'><a id='"+_this.id+"_save' href='javascript:void(0)' class='mcssingbutton'>保存</a></div>"
		+"";
		mcdom.showPopup(e,s,null,null,null,300,360,"编辑");
		$("#"+_this.id+"_autoform").parent().parent().css("width","auto");		
		var Mcsstableform=new Autoform(_this.id+"_autoform",{modelid:detailproject_modelid,recordid:project_id,popupObj:e,parentObject:_this});   
		Mcsstableform.run(autowithpopup);
	
		$("#"+_this.id+"_save").click(function(){
			Mcsstableform.save(false,afterSaveProject,false);
		})
	})
	
	function afterSaveProject(recordid,hint,mcform)
	{
		mcform.params.parentObject.params.project_data=mcform.datarecord;
		mcdom.closePopup(mcform.params.popupObj);
	}
	$("#time_select").change(function(){
		var p=_this.params;	
		if ($(this).val()=='time_thisweek')
		{
			_this.params.timespan="week";
			var m=_this.today.getMonth();
			var weekday=_this.today.getDay();
			//alert(weekday);
			p.begindate=_this.today.addDays(weekday*-1);
			p.enddate=p.begindate.addDays(6);
		}
		else
		if ($(this).val()=='time_thismonth')
		{
			_this.params.timespan="month";
			var m=_this.today.getMonth();
			m++;
			p.begindate=MCDateTime.newDate(_this.today.getFullYear()+"-"+m+"-1");
			m++;
			p.enddate=MCDateTime.newDate(_this.today.getFullYear()+"-"+m+"-1");
			p.enddate=p.enddate.addDays(-1);
		
		}
		else
		if ($(this).val()=='time_premonth')
		{
			p=_this.get_prev_month_params();
		}
		else
		if ($(this).val()=='time_nextmonth')
		{
			p=_this.get_next_month_params();
		}else if ($(this).val()=='time_project')
		{
			_this.params.timespan="project";
			p.begindate=MCDateTime.newDate(_this.params.project_data.begindate);
			p.enddate=MCDateTime.newDate(_this.params.project_data.enddate);
			
		}
	
		_this.removeTimeSpan();
		_this.params=p;
			//_this.params=_this.get_next_month_params();
			var dom=_this.getdateTD();
			var prevButton=_this.table.rows[0].cells[_this.taskCellCount];
			$(prevButton).after(dom);
			_this.createTaskTimeSpan();
			
		})
}
//创建时间表头
McssGantetu.prototype.getdateTD=function()
{
	var fromdate=this.params.begindate;
	var enddate=this.params.enddate;
	var monthdays;
    var r = "";
	var day_title="";
	var m=0;
	var day;
	var date1=fromdate;
	var monthcolor;
	var today=MCDateTime.getGoodDate(new Date());
	while(date1<=enddate)
    {
        w = date1.getDay();
        day = date1.getDate();
		monthcolor="";
		var css="";
        if (w==0 || w==6)
        {
			css="holiday_th";
		}
		else
			css="workday_th";
		day_title=MCDateTime.getGoodDate(date1);
		if (day==1)
		{
			m=date1.getMonth()+1;
			day=date1.getFullYear()+"-"+m;
			monthcolor="red";
		}
		r+="<th class='"+css+"' ";
		if (monthcolor)
			r+=" style='color:"+monthcolor+"'";
		if (day_title==today)
		{
			r+=" style='color:red' ";
			day_title="今天:"+day_title;	
		}
		r+=" title='"+day_title+"'>"+day+"<br />"+MCDateTime.calendar_weekday[w]+"</th>";
        date1=date1.addDays(1);
    }
    return r;

	
}


McssGantetu.prototype.getdateTD_month=function(year,month,monthdays)
{

	var today=new Date();
    if (!year)
    {
        year = "2012";
    }
    if (!month)
    {
        month = "0";
    }
    var r = "";
	var day_title="";
	var m=0;
	for(var d=1;d<=monthdays;d++)
    {
        var d1 =MCDateTime.newDate(year + "-" + month + "-" + d);
        w = d1.getDay();
		var css="";
        if (w==0 || w==6)
			css="holiday_th";
		else
			css="workday_th";
		//m=MCDateTime.getGoodDate(d1);
		day_title=MCDateTime.getGoodDate(d1);
		r+="<th class='"+css+"' title='"+day_title+"'>"+d+"<br />"+MCDateTime.calendar_weekday[w]+"</th>";
        
    }
    return r;

	
}
function gantetu_clickNoneDate(e)
{
	var d=e.parentNode.parentNode.rows[0].cells[e.cellIndex].title;
	d=MCDateTime.newDate(d);
	var recid=e.parentNode.id.substr("task_recordid_".length);
	
			if ($(e).attr("className")=="td_work_none")
			{
				$(e).attr("className","td_work_unstarted");
			}
			else
			{
				$(e).attr("className","td_work_none");
			}

} 
/*人员负荷图主体内容
executerid:任务表中的执行人id的字段
params.workloadstafffield:员工列表的id的字段，如果为空，则默认为“id”
*/
McssGantetu.prototype.getworkloadTD=function(data,firstdate,lastdate)
{
	if (!data) return;
	var len=data.length;
	var s="";
	var date1;//日期变量
	var t;//一条工作变量
	var css;//td的样式
	var worktype="";
	var taskdata=this.params.taskdata;
 	var dayhours=0;
	var tasklist;
	var taskcount;
	var projectname;//项目名称
	var workloadstafffield=this.params.workloadstafffield;
	if (!workloadstafffield)
		workloadstafffield="id";
	var staffname_field=this.params.staffname_field;
	if (!staffname_field)
		staffname_field="name";
		
	for(var i=0;i<len;i++)
	{
		t=data[i];
		s+="<tr>";
		s+="<td>"+t[staffname_field]+"</td>";
		s+="<td></td>";

		var d="";//日期中的“日”
		var nullcount=0;//空格数量
		date1=firstdate;
		
		while(date1<=lastdate)
		{
			css="green";
 			s+="<td";
			dayhours=0;
			tasklist="";
			taskcount=0;
			for(var j=0;j<taskdata.length;j++)
			{
				var b=MCDateTime.newDate(taskdata[j].begindate);
				var e=MCDateTime.newDate(taskdata[j].enddate);
				if (taskdata[j].executerid==t[workloadstafffield] && (MCDateTime.diffDays(b,date1)>=0 && MCDateTime.diffDays(e,date1)<=0))
				{
					dayhours+=8;
					taskcount++;
				}
			}
			if (taskcount>1)
				css="red";
			else
			if (taskcount==0)
				css="";
			
			if (taskcount==0)
			{
				dayhours="";
				taskcount="";
			}
			s+=" style='background-color:"+css+"' title='"+tasklist+"'>"+taskcount+"</td>";
			date1=date1.addDays(1);
		}
		s+="<td></td>";
		s+="</tr>";
	}
	return s;
}

//查询获得执行人字段的顺序
McssGantetu.prototype.getExecuterFieldIndex=function(executerFieldId)
{
	var r=-1;
	if (executerFieldId)
	{
		r=this.mcsstable.getFieldIndex(executerFieldId);
	}
	else
	{
		r=this.mcsstable.getFieldIndex('executer');
		if (r==-1)
			r=this.mcsstable.getFieldIndex('executerid');
	}	
	return r;
}

//创建干特图对象
McssGantetu.prototype.createGantetu=function(data,firstdate,lastdate)
{
	var table="<table border='1' id='"+this.tableid+"'></table>";
	$("#"+this.id).append(table);
	//获取项目信息
	//var p={modelid:this.params.detailproject_modelid,filter:"id="+this.params.project_id};
	var p={projectid:this.params.project_id};
	//var url=getHomeUrl()+"/List/List/getData";
	var url=getHomeUrl()+"/Oa/Project/getProjectAndTaskInfo";
	var _this=this;

	//获取项目基本信息后，再获得任务数据生成干特图
	$.getJSON(url, p,function(data) {
		//if (data.length==1)
		_this.params.project_data=data['project'][0];
		if (_this.params.tableview=='tableedit' && data['taskcount']>30) //任务条数太多了，用全表编辑状态加载太慢，所以自动改为列表
			_this.params.tableview="celledit";
		//如果当前日期大于项目的结束日期，则默认显示项目结束日期那个月，否则显示当前月
		var begindate = _this.params.project_data.begindate;
		var enddate = _this.params.project_data.enddate;
		if(enddate && enddate!='0000-00-00' && MCDateTime.getGoodDate(new Date()) > enddate){
			begindate = MCDateTime.newDate(begindate);
			enddate = MCDateTime.newDate(enddate);
			var m=begindate.getMonth()+1;
			var day=enddate.getDate();
			_this.params.begindate=MCDateTime.newDate(enddate.getFullYear()+"-"+m+"-1");
			_this.params.enddate=MCDateTime.newDate(enddate.getFullYear()+"-"+m+"-"+day);
		}
		
		if (_this.params.afterGetProjectInfo)
		{
			if (_this.params.project_data.name)
				$("#"+_this.id+"_projectname").html(_this.params.project_data.name);
			_this.params.afterGetProjectInfo(_this.params.project_data);
		}
		
		

		var params={showfirst:true,
			afterLoadRows:gantetu_afterLoadRows,
			afterAppendRow:afterAppendRow, 
			gantetu_id:_this.id,filter:"projectid="+_this.params.project_id,
			diffLineCss:true,
			SpecialFieldActionAfterChange:mySpecialFieldActionAfterChange,
			afterGetModel:gantetu_afterGetModel,
			gantetu:_this,showRecordActionAtLast:false,
			userAction:taskAction,
			pageposition:"rightdown",
			auto_fill_fields:"executer,tasktype",//需要提供自动收集值选项的字段列表
			afterOpenEditForm:afterOpenEditForm,
			addGroupInfo:addGroupAction,hidecheckbox:true,
			afterRunTableEdit:gantetu_runWhenFinished,
			afterDataChanged:gantetu_afterDataChanged,
			afterSave:gantetu_afterSave,
//			actionStyle:'ICON',
			defaultValue:"projectid:"+_this.params.project_id,view:_this.params.tableview};
		_this.mcsstable=newMCSSTable(_this.tableid,_this.params.task_modeld_id,params);
		_this.mcsstable.run();
		_this.mcsstable.gantetu=_this;
		
	});

	//下面的方法供本方法内调用
	function addGroupAction(mctable,rowData)
	{
		return groupAction(mctable,rowData);
	}
	function mySpecialFieldActionAfterChange(mcsstable,dom,recordid,fieldid)
	{
		var record=mcsstable.cellEditor.params.mcssData.getRow(recordid);
		if (fieldid=='begindate')
		{
			if (!record.enddate || record.enddate<record.begindate)
				this.gantetu.editField(recordid,"enddate",record.begindate);
		}
		else if (fieldid=='enddate')
		{
			if (!record.begindate || record.enddate<record.begindate)
				this.gantetu.editField(recordid,"begindate",record.enddate);
		}
		else
		{
				this.gantetu.editField(recordid,fieldid,record[fieldid]);
		}
		if(record.status=="done")			
			this.gantetu.editField(recordid,"finishper",1);
		record=mcsstable.cellEditor.params.mcssData.getRow(recordid);
		mcsstable.gantetu.createOneTimeSpan(dom.parentNode.parentNode,record);
		mcsstable.gantetu.bindEvernForRows(dom.parentNode.parentNode);
//		mcsstable.gantetu.addColorForTimeline(dom.parentNode.parentNode,record);
	}
	function taskAction(html,recordid,record,mcsstable)
	{
		return html+getTaskActionHTML(recordid,mcsstable,record);
	}
}
//保存后事件
function gantetu_afterSave(mcsstable)
{
	if (!mcsstable.gantetu.params.project_data.status)
	{
		var url=getHomeUrl()+"/Oa/Project/updataProjectStatus/";
		$.post(url,{projectid:mcsstable.gantetu.params.project_data.id},function(data){
			mcsstable.gantetu.params.project_data.status='plan';
			//alert(data);
		});
	}
}

function gantetu_afterDataChanged(mcssdata,record)
{
	$("#"+mcssdata.params.refObject.id+"_action_save").css('border-color','red');
}
function groupAction(mctable,rowData)
{
	var h="<span class='groupaction' style='float:left; display:none;'>";
	h+="<input type='button' id='addgroupbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='添加分组'  title='添加分组' class='add_group' />"
	+"<input type='button' id='deletegroupbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='删除'  title='删除分组，不包括任务' class='del_group'/>"
	+"<input type='button' id='editgroupbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='修改分组' title='修改分组' class='edit_group' />"
	+"<input type='button' id='addnewtaskbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='添加任务' title='添加任务' class='add_group_renwu' />"
	h+="</span>";
	return h;
}
//任务分组处理
function project_dealGroup(e,tasktype,mcsstableid)
{
	var mctable =mcsstable_getMCSSTable(mcsstableid);
	
	var pid=mctable.gantetu.params.project_id;	
	var tr=e.parentNode.parentNode.parentNode;
	var oldtasktype=tasktype;
	tasktype=$(tr).find(".groupname").html();	
	if (e.id=='deletegroupbtn')
	{
		if(confirm("确认删除‘"+tasktype+"’?"))
		{
			var url=getHomeUrl()+"/Oa/Project/updataTaskType/";
			$.getJSON(url,{tasktype:tasktype,projectid:pid},function(data) {
				if(data==1)
				{
					mcdom.alert("删除成功！",'删除','success','fadeout');
					$(tr).remove();
					//更新当前项目信息中的项目的分组
					var lists=mctable.gantetu.params.project_data.tasktypes;
					lists=lists.replace(tasktype,'');
					mctable.gantetu.params.project_data.tasktypes=lists;
				}
				else
					mcdom.alert("删除失败！",'删除','success','fadeout');
			});			
		}
	}
	else
	if (e.id=='addgroupbtn')
	{
		mcdom.temp_gantetu_current_row=tr;
		mcdom.temp_project_id=pid;
		mcdom.temp_mcsstable=mctable;
		mcdom.input(project_addGroup,"新组");
		
	}
	else
	if (e.id=='addgroupbtn_atlast')
	{
		mcdom.temp_gantetu_current_row=tr;
		mcdom.temp_project_id=pid;
		mcdom.temp_mcsstable=mctable;
		mcdom.input(project_addGroup1,"新组");
	}
	else
	if (e.id=='addnewtaskbtn')
	{
		project_insertNewTask(mctable,tr);
	}
	else
	if (e.id=='editgroupbtn')
	{
		mcdom.temp_gantetu_current_row=tr;
		mcdom.temp_project_id=pid;
		mcdom.temp_mcsstable=mctable;
		mcdom.temp_prevtasktype=tasktype;
		mcdom.temp_oldtasktype=oldtasktype;
		//alert(oldtasktype);
		mcdom.input(project_editGroup,tasktype);
	}
}

//修改组名
function project_editGroup(newname)
{
	if (!newname)
	{
		mcdom.alert("不能为空！","提示",'info','fadeout');	
		return;
	}
	
	var id=mcdom.temp_mcsstable.tableid;
	var mcsstable=mcsstable_getMCSSTable(id);
	var prevname=mcdom.temp_prevtasktype;
	if(newname==prevname)
	{
		mcdom.closePopup();
		mcdom.alert("没有可保存的数据","提示",'info','fadeout');		
	}
	else
	if(!prevname || prevname=="(无分组)")
	{		
		project_addGroup(newname);
	}else
	{
		if ((","+mcsstable.gantetu.params.project_data.tasktypes+",").indexOf(","+newname+",")>-1)

		{
			mcdom.alert("该分组已存在","修改组名",'info','fadeout');
			return;
		}else
		{			
			var url=getHomeUrl()+"/Oa/Project/updateTaskTypeName/";	
			$.getJSON(url,{newname:newname,prevname:prevname,projectid:mcdom.temp_project_id},function(data) {
			if(data==1)
			{
				mcdom.closePopup();
				mcdom.alert("修改成功","修改组名",'success','fadeout');				
				var tr=mcdom.temp_gantetu_current_row;
				$(tr).find(".groupname").html(newname);	
				project_refreshTasktype(mcsstable,'edit',newname,prevname);				
			}
			else
				mcdom.alert("修改失败","修改组名",'fail','fadeout');				
			});	
		}
	}

	
}
function project_insertNewTask(mctable,tr)
{
	var name=$(tr).find(".groupname").html();
	mctable.currentRow=tr;
	var tr=mcsstable_addNewEditRow(mctable,true);	
	$(tr).find("#tasktype select").val(name);
	var id=$(tr).attr("recordid");
	
	var rec={recordid:id,fieldid:"tasktype",value:name};
	mctable.gantetu.mcssData.editNewData(rec);
	$(tr).find("input").eq(0).focus();
}
//新增组
function project_addGroup(newname)
{
	var id=mcdom.temp_mcsstable.tableid;
	var mcsstable=mcsstable_getMCSSTable(id);//ssss
	var tasktypes = mcss_getFieldProp(mcsstable.modeldata,'tasktype','data');
	if ((","+mcsstable.gantetu.params.project_data.tasktypes+",").indexOf(","+newname+",")>-1)
	{
		mcdom.alert("该分组已存在","添加组",'info','fadeout');
		return;
	}
	var url=getHomeUrl()+"/Oa/Project/insertTaskType/";
	
	$.getJSON(url,{tasktype:newname,projectid:mcdom.temp_project_id},function(data) {
		if(data==1)
		{
			mcdom.alert("添加成功","添加组",'success','fadeout');				
			var newRow1=mcdom.temp_gantetu_current_row;
			/*
			var prevname=($(newRow1).find('.groupname').html());
			if(!prevname || prevname=="(无分组)")
			{
				var oldtasktype=mcdom.temp_oldtasktype;
				//更新数据
				var url1=getHomeUrl()+"/Oa/Project/updateTaskTaskTypefun/";
				$.getJSON(url1,{prevtasktype:oldtasktype,newtasktype:newname,projectid:mcdom.temp_project_id},function(data) {
					if(data==0)
					{
						alert("数据更新错误");
					}
				});
				$(newRow1).find('.groupname').html(newname);
				var newRow=newRow1;				
				mcdom.closePopup();
			}else
			*/
			{
				var newRow=newRow1.cloneNode(true); 
				newRow.id=$(newRow).attr('id')+"copy";
				$(newRow).find('.groupname').html(newname);	
				$(mcdom.temp_mcsstable.table).find("tbody").append(newRow);
			}
			project_bindMouseEventOnGroup($(newRow),mcdom.temp_mcsstable);	
			project_refreshTasktype(mcsstable,'add',newname);			
		}
		else
			mcdom.alert("添加失败","添加组",'fail','fadeout');				
	});	
	return true;
}
//新增组1
function project_addGroup1(newname,isbs)
{
	var tableid=mcdom.temp_mcsstable.tableid;
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if(!isbs)
	{
		if ((","+mcsstable.gantetu.params.project_data.tasktypes+",").indexOf(","+newname+",")>-1)
		{
		
			mcdom.alert("该分组已存在","添加组",'info','fadeout');
			return;
		}
	}
	var url=getHomeUrl()+"/Oa/Project/insertTaskType/";
	
	$.getJSON(url,{tasktype:newname,projectid:mcdom.temp_project_id},function(data) {
		if(data==1)
		{
			mcdom.alert("添加成功","添加组",'success','fadeout');				
			gantetu_appenNewGroup(mcsstable,newname);
			
			project_refreshTasktype(mcsstable,'add',newname);
		}
		else
			mcdom.alert("添加成功","添加组",'fail','fadeout');				
	});	
	return true;
}

function project_refreshTasktype(mcsstable,action,groupname,oldgroup)
{
	var fields=mcsstable.modeldata.fields;
	for(var i=0;i<fields.length;i++)
	{
		if (fields[i].id=='tasktype')
		{
			var arr=fields[i].data.split(",");
			if (action=='add')
				arr.push(groupname);
			else if (action=='edit')
			{
				var j=arr.indexOf(oldgroup);
				if (j>-1)
					arr.splice(j,1,groupname); 			
			}
			else if (action=='delete')
			{
				var j=arr.indexOf(groupname);
				arr.splice(j,1); 
			}
			fields[i].data=arr.join(",");
			mcsstable.gantetu.params.project_data.tasktypes=fields[i].data;
			break;
		}
	}
}
function getTaskActionHTML(recordid,mcsstable,record)
{
return "<a class='smallbut gantetu_clearDate'  title='清除日期' style='display:none;cursor:pointer;' onclick='clearTaskDate(\""+recordid+"\","+mcsstable.id+");' >X</a>"
//+"<a class='smallbut gantetu_addDiscussion'  title='评论' style='display:none;cursor:pointer;' onclick='project_addDiscussion(this,\""+recordid+"\",\""+mcsstable.id+"\");' >评论</a>"
+"<a class='smallbut gantetu_moveTask'  title='移动' style='display:none;cursor:pointer;'  onclick='mcss_displaymenu(this,\""+recordid+"\","+mcsstable.id+");' >移动</a>";
}

//移动任务到分组的菜单选项
function mcss_displaymenu(e,recordid,table)
{
	 var self=e;
	 var tableid='abc';
	var mcsstable=mcsstable_getMCSSTable(table.id);
	var tasktypes = mcss_getFieldProp(mcsstable.modeldata,'tasktype','data');
	var strs= new Array(); //定义一数组
	strs=tasktypes.split(","); //字符分割      
	var h = "<div class='popupmenu' style='text-align:left'>";
	h+="移动到：<a style='float:right;' onclick='$(\".popupmenu\").remove();' href='javascript:void(0)' title='关闭' class='close_popup' id='mcss_table_table_action_add_close_popup'><i class='i-pop-close'> </i></a>";	
	for(var i=0;i<strs.length;i++)
	{
		h+="<a class='popupmenu_csv'>"+strs[i]+"</a>";
	}
	h+="</div>";

	 
	 self = $(e);
	 self.after(h);
	$(".popupmenu_csv").click(function(){
		var tasktype=this.innerHTML;
		excuteMoveTask(e,mcsstable,tasktype,recordid);
		$(".popupmenu").remove();
	});	
	 
	 var div = $(".popupmenu"); //要浮动在这个元素旁边的层  
	 div.css("position", "absolute");//让这个层可以绝对定位   
	 var p = self[0].getBoundingClientRect();

	 
	 var x = p.left;// + self.width();//获取这个浮动层的left  
	 var docWidth = $(document).width();//获取网页的宽  
	 if (x > docWidth - div.width() ) {  
		x = p.left - div.width();  
	 }  
	 div.css("left", x);  
	 div.css("top", parseInt(p.top+20));  
	 div.show();  
}

function afterOpenEditForm(autoform)
{
//alert(autoform);
}

//清除指定记录的开始与结束日期，并重新绘制时间线
function clearTaskDate(recordid,table)
{
	var mcsstable=mcsstable_getMCSSTable(table.id);
	mcsstable.gantetu.editField(recordid,"begindate","");
	mcsstable.gantetu.editField(recordid,"enddate","");
	var row=$(table).find("tr[recordid='"+recordid+"']").get(0);
	var record=mcsstable.gantetu.mcssData.getRow(recordid);
	mcsstable.gantetu.addColorForTimeline(row,record);
}

//添加评论
function project_addDiscussion(e,recordid,mcsstableid)
{	
	var s=""
	+"<div id='"+recordid+"_autoform'></div>"
	+"<p style='text-align:left'><input type='checkbox' onclick='project_showStaff(this)'>发送通知给:<span id='xuanze' style='display: none;'>[<a href='javascript:void(0)' onclick='project_selectAll(this,\"select\")'>全选</a> | <a href='javascript:void(0)' onclick='project_selectAll(this)'>全不选</a>]</span></p>";
	s+="<div style='display:none;width:90%;padding:10px;overflow:auto; border:#515151 1px solid'>";
	var url = getHomeUrl()+"/Oa/Project/getAllStaff";
	$.getJSON(url,function(data){
		if(data){
			for(var i = 0;i < data.length;i++){
				s+="<input type='checkbox' value='"+data[i]['id']+"' style='margin:5px' />"+data[i]['name'];
			}
		}
		s+="</div>";
		s+="<div style='text-align:center'><a id='"+recordid+"_save' href='javascript:void(0)' class='mcssingbutton btn btn-green'>发表评论</a></div>";
		s+="<div id='"+recordid+"_commentlist'></div>";
		//s+="</div>";
		var w=700;
		var h=300;
		mcdom.showPopup(e,s,null,null,null,700,620,"评论");
		var mctable =mcsstable_getMCSSTable(mcsstableid);
		var record=mctable.mcssData.getRow(recordid);
		var savebtn=document.getElementById(recordid+"_save");
		var params={modelid:'oa_pm_taskcomment',defaultValue:"task_id:"+recordid+",project_id:"+record.projectid,saveButton:savebtn};
		var Mcsstableform=new Autoform(recordid+"_autoform",params);   
		Mcsstableform.run();
		$("#"+recordid+"_save").click(function(){
			var obj = $(this);
			var strList = "";
			if(obj.parent().prev().prev().children('input').eq(0).attr("checked")){
				var objlist = obj.parent().prev().children('input');
				for(var i = 0;i < objlist.length;i++){
					if(objlist.eq(i).attr("checked")){
						if(strList)
							strList+=',';
						strList+=objlist.eq(i).val();
					}
				}
				if(!strList){
					alert("请至少选择一个员工!");return;
				}
				var url = getHomeUrl()+"/Oa/Project/sendMessageAndMail";
				var taskid = Mcsstableform.getFieldValue('task_id');
				var projectid = Mcsstableform.getFieldValue('project_id');
				var content = Mcsstableform.getFieldValue('content');
				var openurl = getHomeUrl()+"/Oa/Project/projectdetail/param:modelid/oa_project_addcopy/detailproject_modelid/oa_project/taskmodelid/oa_project_task/staffmodelid/oa_project_staff/pagetype/open/id/"+projectid;
				if(!content){
					alert("请填写评论内容!");return;
				}
				$.post(url,{ids:strList,taskid:taskid,projectid:projectid,content:content,openurl:openurl},function(){
					Mcsstableform.save(false,afterAddDiscussion,false);
				})
			}else{
				Mcsstableform.save(false,afterAddDiscussion,false);
			}
		})
		newMCSSTable(recordid+"_commentlist",'oa_pm_taskcomment',{filter:"task_id="+recordid,showfirst:true,hidecheckbox:false}).run();
	})
}
//全部选中
function project_selectAll(obj,type){
	var objlist = $(obj).parent().parent().next().children("input");
	for(var i = 0;i < objlist.length;i++){
		if(type=='select')
			objlist.eq(i).attr("checked",true);
		else
			objlist.eq(i).attr("checked",false);
	}
}
//显示所有员工
function project_showStaff(obj){
	if($(obj).attr("checked"))
	{
		$(obj).parent().next("div").show();
		$(obj).next("span").show();
		$("#xuanze").show();
	}
	else{
		$(obj).parent().next("div").hide();
		$(obj).next("span").hide();
		$("#xuanze").hide();
	}

}

function afterAddDiscussion(id,hint)
{
	mcdom.closePopup();
	mcdom.alert(hint,'评论','success','fadeout');	
}
//移动任务
function project_moveTask(e,recordid,table)
{
	var mcsstable=mcsstable_getMCSSTable(table.id);
	var tasktypes = mcss_getFieldProp(mcsstable.modeldata,'tasktype','data');
	var strs= new Array(); //定义一数组
	strs=tasktypes.split(","); //字符分割      
	var s='<ul id="tasktype_"'+recordid+'>';
	for(var i=0;i<strs.length;i++)
	{
		s+="<li class='tasktype_li'>"+strs[i]+"</li>";
	}
	s+='</ul>';
	mcdom.showPopup(e,s,null,null,null,300,200,"移动");
	$(".tasktype_li").click(function(){
		var tasktype=this.innerHTML;
		excuteMoveTask(e,mcsstable,tasktype,recordid);
	});	
	return;
	var h="<div id='"+recordid+"_autoform'></div>";		
	h+="<div style='text-align: center;' id='"+recordid+"_commentlist'><select id='tasktypelist' style='width:100px'>"+s+"</select></div>";
	h+="<div class='fuceng_buttom'>";
	h+="<a id='"+recordid+"_save' href='javascript:void(0)' class='mcssingbutton btn btn-green'>确定</a>";
	h+="</div>";
	mcdom.showPopup(e,h,null,null,null,300,200,"移动");
	$("#"+recordid+"_save").click(function(){
		var tasktype=$("#tasktypelist").val();
		excuteMoveTask(e,mcsstable,tasktype,recordid)
	});	
}
function excuteMoveTask(e,mcsstable,tasktype,recordid)
{
	var row=$(mcsstable.table).find("tr[recordid='"+recordid+"']").get(0);
	var record=mcsstable.gantetu.mcssData.getRow(recordid);
	var tr=$(".groupname:contains("+tasktype+")").parent().parent().parent();	
	$(row).find("#tasktype select").val(tasktype);
	
	if($(tr).html())
	{		
		$(tr).after($(row));
	}else{
		$(mcsstable.table).find("tbody").append($(row));
	}	
	mcdom.closePopup(e);
	
	var rec={mcss_recordtype:"UPDATE",mcss_rowindex:$(row).attr('rowIndex'),recordid:recordid,fieldid:"tasktype",value:tasktype};
	mcsstable.gantetu.mcssData.editNewData(rec);
	
}

//获得工作模型对象后，把项目设置中的“工作类别”赋予工作模型的“工作类别”的data属性.这样做的目的是避免把工作类别属性分别设置到项目模型和工作模型中
function gantetu_afterGetModel(modeldata,owner)
{

var project_data;
if (owner.__proto__.constructor.name=="MCSSTable")
{
	project_data=owner.gantetu.params.project_data;
	//根据用户设置显示指定的任务字段ssss name:,begindate:开始,enddate:结束,executer:执行人,finishper:完成率,status:状态,worktime:工时,priority:优先级,score:评分,notes:备注,attach:附件,price:单价,totalprice:总价,unitname:单位,amount:任务量
	if (project_data.taskfields)
	{
	var fieldArr=project_data.taskfields.split(",");
	for(var i=0;i<modeldata.fields.length;i++)
	{
		if (fieldArr.indexOf(modeldata.fields[i].id)==-1)
			modeldata.fields[i].isvisible='false';
		else
			modeldata.fields[i].isvisible='true';
		
	}
	}
	if (project_data.diyfields)
	{
	var fieldArr=project_data.diyfields.split(",");
	for(var i=0;i<fieldArr.length;i++)
	{
		if (i>5) 
			break;
		var f=model_getFieldById(modeldata.fields,'diyfield'+(i+1));
		f.isvisible='true';
		f.name=fieldArr[i];
	}
	}
}
else
if (owner.__proto__.constructor.name=="Autoform")
{
	project_data=owner.params.refObject.gantetu.params.project_data;

}
var fields=modeldata.fields;
for(var i=0;i<fields.length;i++)
{
	if (fields[i].id=="tasktype")
	{
		fields[i].data=project_data.tasktypes;
		if (fields[i].data && fields[i].data.substr(0,1)!=",")
			fields[i].data=","+fields[i].data;
		break;
	}
}
}
//根据字段id获得字段
function model_getFieldById(fields,id) 
{
	for(var i=0;i<fields.length;i++)
	{
		if (fields[i].id==id)
			return fields[i];
	}
	return null;
}

//给新追加的tr行显示时间线
function afterAppendRow(mctable,row)
{
	var recordid=$(row).attr("recordid");	
	var record=mctable.mcssData.getRow(recordid);
	
	var gantetu=getGantetu(mctable.params.gantetu_id);
	gantetu.createOneTimeSpan(row,record);
	gantetu.bindEvernForRows(row);

}

//加载完数据到表格后事件
function gantetu_afterLoadRows(mctable)
{
	var gantetu=getGantetu(mctable.params.gantetu_id);
	var id="executerid";
	if (gantetu.params.executerFieldId)
		id=gantetu.params.executerFieldId;
	gantetu.executerFieldIndex=gantetu.getExecuterFieldIndex(id);

	$("#"+mctable.tableid+"_pagebar").css("float","left");
	gantetu_showTimeLines(mctable);
	
	$("#"+mctable.tableid).attr('autowithstyle',lang.compact);
	mcsstable_autowidth(mctable.tableid);
	if (gantetu.params.afterLoadRows)
		gantetu.params.afterLoadRows(mctable,gantetu);
	
	mctable.showAction('firstpage,lastpage,RecordCount,pagerows',false);
	
	project_bindMouseEventOnGroup($(mctable.table).find("tr"),mctable);	


	$(mctable.table).find(".mcsstable_record_del").hide();
	$("#"+mctable.tableid).find(".mcsstable_record_edit").hide();
	//gantetu.mcsstable.cellEditor=new TableEditor(mctable.tableid,mctable.mcssData.data,mctable.modeldata,{mcssData:mctable.mcssData});
//	if ($(mctable.table).find('tr').size()>20 && $(mctable.table).find('.rowgroup').size()>5)//任务太多时收起分组
//	{
//		$(mctable.table).find(".showallgroup").click();
//	}	

	gantetu.createGantetuOption();
	gantetu_showgroup(mctable,gantetu.params.project_data.tasktypes)

}
//添加没有显示的分组
function gantetu_showgroup(mctable,tasktypes)
{
	if (!tasktypes)
		return;
	var arr=tasktypes.split(",");
	//project_dealGroup(e,tasktype,mcsstableid)
	//mcsstable_getMCSSTable(mcsstableid);
	
	
	for(var i=0;i<arr.length;i++)
	{
		newname=arr[i];
		var len=$(mctable.table).find(".groupname:contains("+newname+")").length;
		var n=0;
		for(var j=0;j<len;j++)
		{
			var name=$(mctable.table).find(".groupname:contains("+newname+")").eq(j).html();
			if(name!=newname)
				n++;
		}
		if(n==len && newname)
			gantetu_appenNewGroup(mctable,newname);	
	}
}

//在列表中显示出新的组
function gantetu_appenNewGroup(mctable,newname)
{
	var tableid=mctable.tableid;	
	var fields=mctable.fields;
	var rowid=tableid+"_row_"+document.getElementById(tableid).rows.length;
	var s=groupAction(mctable,'');
	
	var newRow="<tr id='"+rowid+"'";	
	newRow+=" class='rowgroup' align='left' groupname='"+newname+"'>"
		+"<td id='"+tableid+"_first' style='font-weight:bold;text-align:left;' class='mcsstable_groupname' colspan='"+(fields.length)+"'>"
		+"<div class='expandgroup' style='float:left'><a href='javascript:void(0)' onclick='mcsstable_slidenogroup(this)'><img src='"+getrooturl()+"/Public/jusaas/mctree/img/jian.gif' alt='收起'></a><span class='groupname'>"+newname+"</span></div>"
		+s
		+"</td></tr>";
	$(mctable.table).find("tbody").append(newRow);
	var row=$(mctable.table).find(".groupname:contains("+newname+")").parent().parent().parent();
	mctable.currentRow=row;
	project_bindMouseEventOnGroup($(row),mctable);
}

function gantetu_runWhenFinished(mcsstable)
{
	var h=$(mcsstable.table).find(".mcsstable_th_first").find("input").parent().html();
	if (mcsstable.view=="tableedit" && $(mcsstable.table).find(".mcsstable_th_first").find(".add_group").size()==0)
		h+="<span class='groupaction_to'><input type='button' id='addgroupbtn_atlast' onclick='project_dealGroup(this,\"新组\",\""+mcsstable.id+"\")' value='添加分组'  title='添加分组'  class='add_group'/></span>";
	$(mcsstable.table).find(".mcsstable_th_first").html("<div style='width:50px'>"+h+"</div>");
	gantetu_showgroup(mcsstable,mcsstable.params.gantetu.params.project_data.tasktypes);
	if ($(mcsstable.table).find("tr").size()==1)
	{
		$(mcsstable.getAction('add')).click();
	}
}

function project_bindMouseEventOnGroup(rows,mcsstable)
{
	$(rows).mouseover(function(){
		if (mcsstable.view=='tableedit')
		{
			$(this).find(".groupaction").show();
			$(this).find(".gantetu_clearDate").show();		
			$(this).find(".gantetu_moveTask").show();
		}
		$(this).find(".mcsstable_record_del").show();		
		$(this).find(".gantetu_addDiscussion").show();
		$(this).find(".mcsstable_record_edit").show();		
	}).mouseout(function(){
		$(this).find(".mcsstable_record_del").hide();
		$(this).find(".gantetu_clearDate").hide();		
		$(this).find(".groupaction").hide();
		$(this).find(".gantetu_moveTask").hide();		
		$(this).find(".gantetu_addDiscussion").hide();
		$(this).find(".mcsstable_record_edit").hide();
	})

}
//创建时间先区块 
function gantetu_showTimeLines(mctable)
{
	var gantetu=getGantetu(mctable.params.gantetu_id);
	gantetu.params.data=mctable.data;
	gantetu.params.modeldata=mctable.modeldata;
	gantetu.table=mctable.table;
	gantetu.showTimeLines(mctable);
	
}

//创建完表格、记录、时间线后的事件绑定
McssGantetu.prototype.bindEvernForRows=function(row)
{
		var _this=this;
		var dom;
		if (row)
			dom=row;
		else
			dom="#gantetu_"+this.id;
		$(dom).find(".timeline_td").click(function(){
			_this.clickTimeLine(this);
		}).mouseover(function(){
			$(this.parentNode.parentNode.rows[0].cells[this.cellIndex]).css("background","yellow");
		}).mouseout(function(){
			$(this.parentNode.parentNode.rows[0].cells[this.cellIndex]).css("background","");
		})
}

//创建时间线区块，包括翻页按钮
McssGantetu.prototype.showTimeLines=function(mctable)
{
	this.removeWholeTimeSpan();

	var row=$("#"+mctable.tableid).find("tr").get(0);
	this.taskCellCount=row.cells.length;

	var dom;
	$(row).append(dom);
	this.mcssData=mctable.mcssData;
	dom=this.createPrevTimeSpan();
	$(row).append(dom);
	dom=this.getdateTD();
	$(row).append(dom);
	dom=this.createNextTimeSpan();
	$(row).append(dom);

	this.bindEventForPreNextTimespan();
	//上面创建时间线区块的日历头部
	
	//创建时间线
	this.createTaskTimeSpan();
}

//创建时间线区域，就是干特图右侧部分的图
McssGantetu.prototype.createTaskTimeSpan=function()
{
	var rows=$("#"+this.tableid).get(0).rows;
	var cell_count=this.taskCellCount-1;
	var record;
	for(var i=1;i<rows.length;i++)
	{
		record=this.mcssData.getRow($(rows[i]).attr("recordid"));
		if (record)
		{
			this.createOneTimeSpan(rows[i],record);
		}
	}
	this.bindEvernForRows();
}

//给指定的行的任务信心后面追加时间线需要的格子（td）并标上颜色
McssGantetu.prototype.createOneTimeSpan=function(row,record)
{
		var dom=this.createOneTimeLine(record,this.params.begindate,this.params.enddate);
		var cell_count=this.taskCellCount-1;

		if (row.cells.length>=cell_count)
		{
			$(row.cells[cell_count]).nextAll().remove();
			$(row.cells[cell_count]).after(dom);
		}
		this.addColorForTimeline(row,record);
}

McssGantetu.prototype.createPrevTimeSpan=function(mctable)
{
		var h="";		
		h+="<th class='gante_header'><a  style='cursor:pointer; width:20px;text-decoration:none' title='显示更多字段' id='a_setDiyFields'>...</a>&nbsp;<a  style='cursor:pointer; width:20px;text-decoration:none' title='前翻' id='pre_timespan_l'>《</a>&nbsp;<a  style='cursor:pointer;width:20px;text-decoration:none' title='后翻' id='next_timespan_l'>》</a></th>";
		return h;
}
McssGantetu.prototype.createNextTimeSpan=function(mctable)
{
		var h="";		
		h+="<th class='gante_header'><a  style='cursor:pointer; width:20px;text-decoration:none' title='前翻' id='pre_timespan_r'>《</a>&nbsp;<a style='cursor:pointer;width:20px;text-decoration:none' title='后翻' id='next_timespan_r'>》</a></th>";
		return h;
}
//删除日期格子区，不包括翻页按钮
McssGantetu.prototype.removeTimeSpan=function()
{
			var from=this.taskCellCount;
			$(this.table).find("tr").each(function(i,item){
				if (i==0)	
					$(item).children().eq(from).nextAll().not(":last").remove();					
				else
					$(item).children().eq(from-1).nextAll().remove();
			})
}
//删除日期格子区，包括翻页按钮
McssGantetu.prototype.removeWholeTimeSpan=function()
{
	var from=this.taskCellCount;
	$(this.table).find("tr").each(function(i,item){
		$(item).children().eq(from-1).nextAll().remove();
	})
}

function gantetu_afterSetDiyFields()
{
	mcdom.closePopup();
	mcdom.alert('设置完成，请刷新页面生效。','设置字段','info');
}

McssGantetu.prototype.bindEventForPreNextTimespan=function()
{
	
	function afterRunDiyFields(mcform)
	{
		mcform.addText('diyfields','输入字段显示名称，最多10个。例子：部门,是否有预算,工作类别');
	}
	var _this=this;
	var thistd=this.table.rows[0].cells[this.taskCellCount];
		$("#a_setDiyFields").click(function(){
	var h="<div id='form_diyfields'></div>";
	mcdom.showPopup(this,h,'',null,null,null,null,'设置显示字段');
	var mcform=new Autoform('form_diyfields',{modelid:'oa_pm_taskdiyfield',recordid:'url:KEYFIELD',showFormButton:'SAVE',afterSave:gantetu_afterSetDiyFields});
	mcform.run(afterRunDiyFields);
	
			
		})

		$("#pre_timespan_l,#pre_timespan_r").click(function(){
			_this.removeTimeSpan();
			if (!_this.params.timespan || _this.params.timespan=="month" || _this.params.timespan=="project")
			{
				_this.params=_this.get_prev_month_params();
				var dom=_this.getdateTD();
				$(thistd).after(dom);
				_this.createTaskTimeSpan();
			}
			else if (_this.params.timespan=="week")
			{
				var p=_this.params;
				p.begindate=p.begindate.addDays(-7);
				p.enddate=p.begindate.addDays(6);
			
				_this.params=p;
				var dom=_this.getdateTD();
				$(thistd).after(dom);
				_this.createTaskTimeSpan();
			}
		})
		
		$("#next_timespan_l,#next_timespan_r").click(function(){
			_this.removeTimeSpan();
			if (!_this.params.timespan || _this.params.timespan=="month" || _this.params.timespan=="project")
			{
				_this.params=_this.get_next_month_params();
				var dom=_this.getdateTD();
				$(thistd).after(dom);
				_this.createTaskTimeSpan();
			}
			else if (_this.params.timespan=="week")
			{
				var p=_this.params;
				p.begindate=p.begindate.addDays(7);
				p.enddate=p.begindate.addDays(6);
				_this.params=p;
				var dom=_this.getdateTD();
				$(thistd).after(dom);
				_this.createTaskTimeSpan();
			}
		})
}

//根据完成率获得时间线样式名
McssGantetu.prototype.getTimelineCssByState=function(percent)
{
	var css="td_work_doing";
	if (percent==0 || !percent)
		css="td_work_unstarted";
	else if (percent==1)
		css="td_work_done";
	return css;
}

//给某任务记录的时间线，根据开始结束日期和完成率标上颜色
McssGantetu.prototype.addColorForTimeline=function(row,record)
{
	var css=this.getTimelineCssByState(record["finishper"]);
	var begin=MCDateTime.newDate(record["begindate"]);
	var end=MCDateTime.newDate(record["enddate"]);
	var date1=this.params.begindate;
	var tds=$(row).children().eq(this.taskCellCount).nextAll().not(":last").each(function(i,item){
		if (date1>=begin && date1<=end)
		{
			$(item).removeClass("td_work_unstarted").removeClass("td_work_doing").removeClass("td_work_done");
			$(item).addClass(css);
			$(item).css("background","");
		}
		else
		{
			var date = new Date($(item.parentNode.parentNode.rows[0].cells[item.cellIndex]).attr("title")).getDay();
			if(date==0 || date==6)
				$(item).css("background","#EEEEEE");		
			$(item).removeClass(css);
		}
		var s="";
		if (record.name)
			s+=record.name;
		if (record.begindate){
			s+="\r\n开始："+record.begindate;
			s+=" 结束："+record.enddate;
		}
		if (record.worktime)
		s+="\r\n工作耗时："+record.worktime+"h";
		if (record.executer)
		s+="\r\n执行人："+record.executer;
		
		$(item).attr('title',s);
		date1=date1.addDays(1);
	})
	
}

//给指定的行创建时间线部分的td和提示文字，供后面绘制时间线颜色
McssGantetu.prototype.createOneTimeLine=function(oneTaskRow,firstdate,lastdate)
{
	var t=oneTaskRow;	
	var	percent=parseFloat(t.finishper);

		var s="<td></td>";

		if (this.mcsstable.view!='tableedit')
		{
			//if (!t.begindate || t.begindate=="0000-00-00" || !t.enddate || t.enddate=="0000-00-00" )
				//return;
		}
		var begindate=MCDateTime.newDate(t.begindate);  
		var enddate=MCDateTime.newDate(t.enddate);  
		var beginday=MCDateTime.diffDays(firstdate,begindate);//任务的开始日期与第一个格子间的格子数量
		var endday=MCDateTime.diffDays(firstdate,enddate);//任务的结束日期与第一个格子间的格子数量
		var diff=MCDateTime.diffDays(begindate,enddate)+1;//任务的开始日期与开始日期间的格子数量
		var nulldays=MCDateTime.diffDays(enddate,lastdate);//任务的结束日期与最后一个格子间的格子数量
		var d="";//日期中的“日”
		var nullcount=0;//空格数量
		var date1=firstdate;
		var hasShowInfo=false;

		while(date1<=lastdate)
		{
			var css="td_work_none";
			if ((date1-begindate)>=0  && (date1-enddate)<=0 )
			{
				css="";
			}
			s+="<td class='timeline_td'";
			if (MCDateTime.diffDays(date1,begindate)>0)
			{
				if (this.params.showEveryDayTime==true)
					date1=date1.addDays(1);
				else
				{
					s+=" colspan='"+beginday+"' ";
					date1=date1.addDays(beginday);
				}
			}
			else
			if (MCDateTime.diffDays(date1,begindate) <1 && MCDateTime.diffDays(date1,enddate)>-1)
			{
				if (this.params.showEveryDayTime==true)
					date1=date1.addDays(1);
				else
				{
					s+=" colspan='"+diff+"' ";
					date1=date1.addDays(diff);
				}
			}
			else
			{
				if (this.params.showEveryDayTime==true)
					date1=date1.addDays(1);
				else
				{
					s+=" colspan='"+nulldays+"' ";
					date1=date1.addDays(nulldays);
				}
			}		
			s+=">";
			
			if (css!="td_work_none")
			{
				if (!hasShowInfo)
				{
				if (t.tasktype)
				{
					var show='none';
					if (this.showTaskType) 
						show="";
					s+="<span style='color:white;white-space:nowrap;display:"+show+";' class='timespan_bar gantetu_tasktype'>["+t.tasktype.substr(0,5)+"]</span>";
				}
				if (t.name)
				{
					var show='none';
					if (this.showTaskName) 
						show="";
					s+="<span style='color:white;white-space:nowrap;display:"+show+";' class='timespan_bar gantetu_taskname'>"+t.name.substr(0,5)+" </span>";
				}
				if (t.executer)
				{
					var show='none';
					if (this.showExecuter) 
						show="";				
					s+="<span style='color:white;white-space:nowrap;display:"+show+";' class='timespan_bar gantetu_executer'>"+t.executer.substr(0,5)+" </span>";
				}
				else
				if (t.executerid)
				{
					var show='none';
					if (this.showExecuter) 
						show="";				
					var tr=this.mcsstable.getRow(t.id);
					var obj=$(tr.cells[this.executerFieldIndex]).get(0);
					var name=mcsstable_getCellDisplayText(obj);
					s+="<span style='color:white;white-space:nowrap;display:"+show+";' class='timespan_bar gantetu_executer'>"+name+" </span>";
				}
				hasShowInfo=true;
				}
			}
			s+="</td>";
			
		}
		css="td_work_none";
		s+="<td class='"+css+"'></td>";
		return s;
	

}

 
var gantet_statu={
'':"",
plan:"计划",
doing:"执行中",
done:"完成",
cancel:"终止",
}