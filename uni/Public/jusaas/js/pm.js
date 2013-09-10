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
	if (!params.timelinewordlen_view)
		params.timelinewordlen_view=20;
	if (!params.timelinewordlen_edit)
		params.timelinewordlen_edit=20;
		
	var _this=this;
	this.run=function()
	{
		mcss_importJS("jusaas/js/JSData.js");
		mcss_importJS("jusaas/js/MCDateTime.js");

		var _this=this;
		$("#"+id).html("");
		this.today=new Date();
		this.todaystr=MCDateTime.getGoodDate(this.today);
		this.today=MCDateTime.newDate(this.todaystr);
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
			//this.addColorForTimeline(row,record);
			this.createOneTimeSpan(row,record);
			this.bindEvernForRows(row);			
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
	
	//showup:是否显示在输入框上
	this.editField=function(recordid,fieldid,value,showup)
	{
		this.mcssData.setFieldValue(recordid,fieldid,value);
		if(showup==undefined || showup===true)
			this.mcsstable.cellEditor.showFieldValueInCell(recordid,fieldid);				
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

//保存甘特图显示选项	
McssGantetu.prototype.saveShowOptions=function()
{
	var option="";
	if ($("#gentetu_option_showtasktype"+this.id).attr('checked')==true)
	{
		option+='type';
	}
	if ($("#gentetu_option_showtaskname"+this.id).attr('checked')==true)
	{
		option+=',name';
	}
	if ($("#gentetu_option_showexecuter"+this.id).attr('checked')==true)
	{
		option+=',executer';
	}
	if($("#time_select_"+this.id).val()=='setdate')
		option+=',showDate['+begindate+'|'+enddate+']';
	else
		option+=','+$("#time_select_"+this.id).val();
	var url=getHomeUrl()+"/Oa/Project/saveGantetuOption/";
	$.post(url,{projectid:this.params.project_data.id,option:option},function(data){
	
	});
}


//创建干特图对象
McssGantetu.prototype.createGantetu=function(data,firstdate,lastdate)
{
	var table="<table border='1' id='"+this.tableid+"'></table>";
	$("#"+this.id).append(table);
	//获取项目信息
	//var p={modelid:this.params.detailproject_modelid,filter:"id="+this.params.project_id};
	var p={projectid:this.params.project_id};
	var url=getHomeUrl()+"/Oa/Project/getProjectAndTaskInfo";
	var _this=this;

	//获取项目基本信息后，再获得任务数据生成干特图
	$.getJSON(url, p,function(data) {
		//if (data.length==1)
		_this.project_fulldata=data;//project_fulldata包括项目基本信息、任务数量、有评论的任务id
		_this.params.project_data=data['project'][0];
		if (_this.params.tableview=='tableedit' && data['taskcount']>20) //任务条数太多了，用全表编辑状态加载太慢，所以自动改为列表
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
		
		var timeperiod=_this.params.project_data.show_options;
		if (timeperiod)
		{
		if (timeperiod.indexOf('month')>-1)
			timeperiod='month';
		else
		if (timeperiod.indexOf('week')>-1)
			timeperiod='week';
		else
		if (timeperiod.indexOf('project')>-1)
			timeperiod='project';
		else
		if(timeperiod.indexOf('showDate')>-1){
			var arr = timeperiod.substring((timeperiod.indexOf('[')+1),(timeperiod.indexOf(']')));
			var date = arr.split('|');
			var start = date[0];
			var end = date[1];
			timeperiod='setdate,'+start+','+end;
		}else
		if (timeperiod.indexOf('none')>-1)
			timeperiod='none';		
		_this.changeBeginEndDate(timeperiod);		
		}
		
		if (_this.params.afterGetProjectInfo)
		{
			_this.params.afterGetProjectInfo(_this.params.project_data);
		}
		
		_this.params.timelinetext=_this.params.project_data.show_options;//需要在甘特图上显示哪些字段
		var filter="projectid="+_this.params.project_id;
		//if (_this.params.project_id=="ALLMINE")
		//	filter="";
		var gantetu_special_field_show=[{'finishper':special_field_show_finishper}];
		var params={showfirst:true,
			gantetu_id:_this.id,filter:filter,
			diffLineCss:true,
			gantetu:_this,showRecordActionAtLast:false,
			userAction:taskAction,
			pageposition:"rightdown",
			auto_fill_fields:"executer,tasktype",//需要提供自动收集值选项的字段列表

			SpecialFieldActionAfterChange:mySpecialFieldActionAfterChange,
			beforeSave:gantetu_beforeSave,
			beforeAddNew:gantetu_beforeAddNew,
			afterGetModel:gantetu_afterGetModel,
			afterOpenEditForm:afterOpenEditForm,
			addGroupInfo:addGroupAction,hidecheckbox:true,
			afterRunTableEdit:gantetu_afterRunTableEdit,
			afterDataChanged:gantetu_afterDataChanged,
			afterSave:gantetu_afterSave,
			afterLoadRows:gantetu_afterLoadRows,
			afterAppendRow:afterAppendRow, 
			aterExecuteAction:gantetu_aterExecuteAction,
			afterCreateHeader:gantetu_afterCreateHeader,
			special_field_show:gantetu_special_field_show,
//			actionStyle:'ICON',
			exporturl:_this.params.exportutl,
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
			if (record.begindate && mcsstable.gantetu.params.project_data.begindate && mcsstable.gantetu.params.project_data.begindate!='0000-00-00'  && mcsstable.gantetu.params.project_data.begindate>record.begindate){
				mcdom.alert("错啦，输入的开始日期("+record.begindate+")不应该小于项目限定的开始日期("+mcsstable.gantetu.params.project_data.begindate+")",'','warning','fadeout','','',{seconds:4});
				this.gantetu.editField(recordid,"begindate",'');
			}
			else
			if (record.begindate && mcsstable.gantetu.params.project_data.enddate && mcsstable.gantetu.params.project_data.enddate!='0000-00-00' && mcsstable.gantetu.params.project_data.enddate<record.begindate){
				mcdom.alert("错啦，输入的开始日期("+record.begindate+")不应该大于项目限定的结束日期("+mcsstable.gantetu.params.project_data.enddate+")",'','warning','fadeout','','',{seconds:4});
				this.gantetu.editField(recordid,"begindate",'');
			}
			if (!record.enddate || record.enddate<record.begindate)
				this.gantetu.editField(recordid,"enddate",record.begindate);
		}
		else if (fieldid=='enddate')
		{
			if (record.enddate && mcsstable.gantetu.params.project_data.enddate && mcsstable.gantetu.params.project_data.enddate!='0000-00-00' && mcsstable.gantetu.params.project_data.enddate<record.enddate){
				mcdom.alert("错啦，输入的结束日期("+record.enddate+")不应该大于项目限定的结束日期("+mcsstable.gantetu.params.project_data.enddate+")",'','warning','fadeout','','',{seconds:4});
				this.gantetu.editField(recordid,"enddate",'');
			}
			else
			if (record.enddate && mcsstable.gantetu.params.project_data.begindate && mcsstable.gantetu.params.project_data.begindate!='0000-00-00' && mcsstable.gantetu.params.project_data.begindate>record.enddate){
				mcdom.alert("错啦，输入的结束日期("+record.enddate+")不应该小于项目限定的开始日期("+mcsstable.gantetu.params.project_data.begindate+")",'','warning','fadeout','','',{seconds:4});
				this.gantetu.editField(recordid,"enddate",'');
			}
			
			if (!record.begindate || record.enddate<record.begindate)
				this.gantetu.editField(recordid,"begindate",record.enddate);
		}
		else if (fieldid=='status')
		{
			if(record.status=="done")			
				this.gantetu.editField(recordid,"finishper",1);
			else if(record.status=="plan")
				this.gantetu.editField(recordid,"finishper",0);
			else if(record.status=="doing")
				this.gantetu.editField(recordid,"finishper",0.5);		
			else if(record.status=="cancel")
				this.gantetu.editField(recordid,"finishper",-1);		
			else
				this.gantetu.editField(recordid,"finishper",0);		
		}
		else if (fieldid=='executer')
		{
			//判断是否新的人。如果是，则增加一个datalist的option
			var id="datalist_"+mcsstable.modeldata.id+"_"+fieldid;
			if ($("#"+id).find("option[value='"+record[fieldid]+"']").size()==0){
				$("#"+id).append("<option value='"+record[fieldid]+"'>"+record[fieldid]+"</option>");
			}
			this.gantetu.editField(recordid,fieldid,record[fieldid],false);
		}
		else
		{
			this.gantetu.editField(recordid,fieldid,record[fieldid],false);
		}
		record=mcsstable.cellEditor.params.mcssData.getRow(recordid);
		mcsstable.gantetu.createOneTimeSpan(dom.parentNode.parentNode,record);
		mcsstable.gantetu.bindEvernForRows(dom.parentNode.parentNode);
		
		if (fieldid==mcsstable.groupby){			
			//如果没有对应的分组的行
			if ($(mcsstable.table).find("tr[groupname='"+record[fieldid]+"']").size()==0){
				gantetu_appenNewGroup(mcsstable,record[fieldid],dom.parentNode.parentNode);			
			} else{
				excuteMoveTask(null,mcsstable,record[fieldid],recordid);				
			}
		}
	}
	function taskAction(html,recordid,record,mcsstable)
	{
		return getTaskActionHTML(recordid,mcsstable,record)+html;
	}
}

function special_field_show_finishper(value,record)
{
	var h='',css='';	
	if (value==0)
		css='td_work_unstarted';
	else if (value==1)
		css='td_work_done';
	else if (value==-1)
		css='td_work_done';
	else
		css='td_work_doing';
		
	h="<div class='"+css+"'></div>";
	return h;
}
McssGantetu.prototype.createGantetuOption=function()
{
	if ($("#"+this.id+"_gantetuoption").size()>0)
		return;
	var s="";	
	s+="<span id='"+this.id+"_gantetuoption' style='float:right;padding-top:5px;'>";
	s+="  "+lang.view+"："
	+"<input type='checkbox' id='gentetu_option_showtasktype"+this.id+"'";
	if (this.params.timelinetext && this.params.timelinetext.indexOf('type')>-1)
	{
		s+=" checked='checked'";
		this.showTaskType=true;
		$(".gantetu_tasktype").show();		
	}
	s+="><label for='gentetu_option_showtasktype"+this.id+"'>"+lang.worktype+"</label>";

	s+=" <input type='checkbox' id='gentetu_option_showtaskname"+this.id+"'";
	if (this.params.timelinetext && this.params.timelinetext.indexOf('name')>-1)
	{
		s+=" checked='checked'";
		this.showTaskName=true;
		$(".gantetu_taskname").show();		
	}
	s+="><label for='gentetu_option_showtaskname"+this.id+"'>"+lang.taskname+"</label>";
	s+=" <input type='checkbox' id='gentetu_option_showexecuter"+this.id+"'";
	if (this.params.timelinetext && this.params.timelinetext.indexOf('executer')>-1)
	{
		s+=" checked='checked'";
		this.showExecuter=true;
		$(".gantetu_executer").show();		
	}
	s+="><label for='gentetu_option_showexecuter"+this.id+"'>"+lang.executer+"</label>"	;

	s+="&nbsp;&nbsp;"+lang.timelinespan+":&nbsp;"
	+"<select id='time_select_"+this.id+"'>"
	+"<option value='month'>"+lang.month+"</option>"
	+"<option value='week'>"+lang.week+"</option>"
	+"<option value='project'>"+lang.projectstartend+"</option>"
	+"<option value='setdate'>"+lang.setdate+"</option>"
	+"<option value='none'>"+lang.none+"</option>"
	+"</select>";
	+"</span>";
		
	
	$("#"+this.tableid).after(s);
	if(this.params.timespan.indexOf('setdate')>-1)
		$("#time_select_"+this.id).val('setdate');
	else
		$("#time_select_"+this.id).val(this.params.timespan);
	
	//$(this.mcsstable.getToolBar()).append(s);
	
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
		_this.saveShowOptions();
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
		_this.saveShowOptions();
		
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
		_this.saveShowOptions();
		
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
	$("#time_select_"+this.id).change(function(){
		var period=$(this).val();
		if (period=='week')
			period='week';
		else
		if (period=='month')
			period='month';
		else
		if (period=='project')
			period='project';
		else
		if(period=='setdate'){
			var e = this;
			period='setdate';
			var h="<div>"+lang.begindate+":<input id='setdate_begindate' onclick='autoform_selectDate(\"yyyy-MM-dd\")' style ='width:185px'><br><br>"+lang.enddate+":<input id='setdate_enddate' onclick='autoform_selectDate(\"yyyy-MM-dd\")' style='width:185px'> <input id='setdate_confirm' type='button' value='"+lang.confirm+"'></div>";
			mcdom.showPopup(e,h,'middle',null,null,null,300,lang.ask);
			$("#setdate_confirm").click(function(){
				_this.changeBeginEndDate(period);
				_this.removeTimeSpan();
				if (!utils_isSharingPage() && begindate && enddate)
					_this.saveShowOptions();
				var dom=_this.getdateTD();
				var prevButton=_this.table.rows[0].cells[_this.taskCellCount];
				$(prevButton).after(dom);
				_this.setTableWidth();
				_this.createTaskTimeSpan();
			})
			return;
		}
		else
		if (period=='none')
			period='none';
		_this.changeBeginEndDate(period);
		_this.removeTimeSpan();
		if (!utils_isSharingPage())
			_this.saveShowOptions();
		var dom=_this.getdateTD();
		var prevButton=_this.table.rows[0].cells[_this.taskCellCount];
		$(prevButton).after(dom);
		_this.setTableWidth();
		_this.createTaskTimeSpan();
		})
}


//根据甘特图时间周期修改开始与结束日期
//period：周期选项值
var begindate;
var enddate;
McssGantetu.prototype.changeBeginEndDate=function(period)
{
		var p=this.params;	
		if (!period)
			period="month";
		if (period=='week')
		{
			var m=this.today.getMonth();
			var weekday=this.today.getDay();
			p.begindate=this.today.addDays(weekday*-1);
			p.enddate=p.begindate.addDays(6);
		}
		else
		if (period=='month')
		{
			var m=this.today.getMonth();
			m++;
			p.begindate=MCDateTime.newDate(this.today.getFullYear()+"-"+m+"-1");
			m++;
			p.enddate=MCDateTime.newDate(this.today.getFullYear()+"-"+m+"-1");
			p.enddate=p.enddate.addDays(-1);
		
		}
		else
		if(period.indexOf('setdate')>-1)
		{
			begindate = $("#setdate_begindate").val();
			enddate = $("#setdate_enddate").val();
			var arr = period.split(',');
			if(arr[1])
				begindate = arr[1];
			if(arr[2])
				enddate = arr[2];
			if(!begindate){
				alert('请选择开始日期!');
				return;
			}else if(!enddate){
				alert('请选择结束日期!');
				return;
			}else if(begindate > enddate){
				alert('开始日期不能大于结束日期!');
				return;
			}
			p.begindate = MCDateTime.newDate(begindate);
			p.enddate = MCDateTime.newDate(enddate);
			mcdom.closePopup();
		}
		else if (period=='project')
		{
			p.begindate=MCDateTime.newDate(this.params.project_data.begindate);
			p.enddate=MCDateTime.newDate(this.params.project_data.enddate);
		}
		else if (period=='none')
		{
			p.begindate=null;//MCDateTime.newDate(this.params.project_data.begindate);
			p.enddate=null;//MCDateTime.newDate(this.params.project_data.enddate);
		}
		p.timespan=period;
}

//创建时间表头
McssGantetu.prototype.getdateTD=function()
{
	var fromdate=this.params.begindate;
	var enddate=this.params.enddate;
	if (!fromdate || !enddate)
		return '';
	var monthdays;
    var r = "";
	var day_title="";
	var m=0;
	var day;
	var date1=fromdate;
	var monthcolor;
	var today=MCDateTime.getGoodDate(new Date());
	var style="";
	day=MCDateTime.getGoodDate(date1);
	style="width:65px";				
	while(date1<=enddate)
    {
        w = date1.getDay();
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
			day=date1.getFullYear()+"-"+m+"-1";
			monthcolor="red";
		}
		r+="<th class='"+css+"' ";
		if (monthcolor)
		{
			//r+=" style='color:"+monthcolor+";'";
			style="color:"+monthcolor+";width:60px";			
		}
		if (day_title==today)
		{
			style="color:red;";
			day_title=lang.today+":"+day_title;	
		}
		r+=" style='"+style+"' title='"+day_title+"'>"+day+"<br />"+lang[w]+"</th>";
        date1=date1.addDays(1);
        day = date1.getDate();
		style="width:20px";        
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
		r+="<th class='"+css+"' title='"+day_title+"'>"+d+"<br />"+lang[w]+"</th>";
        
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


//保存前处理
function gantetu_beforeSave(mcsstable)
{
	var mcssdata=mcsstable.cellEditor.params.mcssData;
	var rows=mcssdata.newData;
	var idpull=mcsstable.gantetu.project_fulldata.newids_for_newtask;
	for(var i=0;i<rows.length;i++)
	{
		if (rows[i].mcss_recordtype=='NEW' && !mcssdata.getFieldValue(rows[i].recordid,'name'))
		{
			rows[i].mcss_recordtype="";
		}
		if (rows[i].mcss_recordtype=='NEW')
		{
			if (idpull.length==0){
				alert('一次性新增的任务太多了，没有可分配的id，请重新加载。');//因为每次从服务器取的id（newids_for_newtask）是99个
				break;
			}
			rows[i].id=idpull[0];
			idpull.shift();
		}
		
	}

	

	//把任务名称为空的记录删除
	if (mcsstable.temp_name_fieldindex==undefined)
	{
		for(var i=0;i<mcsstable.table.rows[0].cells.length;i++)
		{
			if (mcsstable.table.rows[0].cells[i].id=='name')
			{
				mcsstable.temp_name_fieldindex=i;
				break;
			}
		}
	}
	if (mcsstable.data.length>0)
	{
		$("#"+mcsstable.tableid).find("tbody").find("tr[class!='rowgroup']").each(function(i,item){
			if ($(item.cells[mcsstable.temp_name_fieldindex]).find("input").val()=="")
			{
				$(item).remove();
			}
		})
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
	//h+="<input type='button' id='addgroupbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='添加分组'  title='添加分组' class='add_group' />"
	//+"<input type='button' id='deletegroupbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='删除'  title='删除分组，不包括任务' class='del_group'/>"
	//+"<input type='button' id='editgroupbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='修改分组' title='修改分组' class='edit_group' />"
	h+="<input type='button' id='addnewtaskbtn' onclick='project_dealGroup(this,\""+rowData.tasktype+"\",\""+mctable.id+"\")' value='添加任务' title='添加任务' class='add_group_renwu' />"
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

//在本组下插入新记录，在第一条记录前
function project_insertNewTask(mctable,tr)
{
	var name=$(tr).find(".groupname").html();
	mctable.currentRow=tr;
	var tr=mcsstable_addNewEditRow(mctable,true);	
	$(tr).find("#tasktype select").val(name);
	//var id=$(tr).attr("recordid");	
	//var rec={recordid:id,fieldid:"tasktype",value:name};
	//mctable.gantetu.mcssData.editNewData(rec);
	$(tr).find("input").eq(0).focus();
}
//新增组
function project_addGroup(newname)
{
	var id=mcdom.temp_mcsstable.tableid;
	var mcsstable=mcsstable_getMCSSTable(id);
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
			var newRow=newRow1.cloneNode(true); 
			newRow.id=$(newRow).attr('id')+"copy";
			$(newRow).find('.groupname').html(newname);	
			$(mcdom.temp_mcsstable.table).find("tbody").append(newRow);
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
	var show="";
	var discus_css="gantetu_addDiscussion";
	if (mcsstable.gantetu.project_fulldata.tasks_withdiscussion && mcsstable.gantetu.project_fulldata.tasks_withdiscussion.indexOf(recordid)>-1)
	{
		show="inline";
		discus_css="gantetu_addDiscussion_withdata";
	}
	else
		show='none';
	var r="";
	if (record)
		r+="<a id='discussion_"+recordid+"' class='smallbut "+discus_css+"' style='display:"+show+";cursor:pointer;float:left;' onclick='project_addDiscussion(this,\""+recordid+"\",\""+mcsstable.id+"\");' title='"+lang.comment+"'  onmouseover='setDiscussTitle(this,"+recordid+")'>"+lang.comment+"</a>";
	//r+="<a class='smallbut gantetu_clearDate'  title='"+lang.cleardate+"' style='display:none;cursor:pointer;float123:right;' onclick='clearTaskDate(\""+recordid+"\","+mcsstable.id+");' >X</a>"
	//r+="<a class='smallbut gantetu_moveTask'  title='"+lang.move+"' style='display:none;cursor:pointer;float123:right;'  onclick='pm_moveTask(this,\""+recordid+"\","+mcsstable.id+");' id='movetask_a'>"+lang.move+"</a>"
	//+"";
	//用了float:right在火狐下会导致第一栏折行
	return r;
}

//移动到讨论上面浮现出内容
function setDiscussTitle(obj,id){
	if($(obj).attr('className').indexOf('gantetu_addDiscussion_withdata')>-1 && !$(obj).attr("hasloaddiscussion")){
		var url = getHomeUrl()+"/Oa/Task/getNewDiscuss";
		$.post(url,{id:id},function(data){
			var content = clearHTML(data).substr(0,200);
			$(obj).attr("title",content).attr("hasloaddiscussion",true);
		})
	}
}
//移动任务到分组的菜单选项
function pm_moveTask(e,recordid,table)
{
	var self=e;
	var tableid='abc';
	var mcsstable=mcsstable_getMCSSTable(table.id);
	var tasktypes = mcss_getFieldProp(mcsstable.modeldata,'tasktype','data');
	if(!tasktypes)
	{
		mcdom.alert("您还没有创建分组",'移动','warning','fadeout');
		return;
	}
	var strs= new Array(); //定义一数组
	strs=tasktypes.split(","); //字符分割      
	var h = "<div class='popupmenu' style='text-align:left'>";
	h+="<a style='float:right;' onclick='$(\".popupmenu\").remove();' href='javascript:void(0)' title='"+lang.close+"' class='close_popup' id='mcss_table_table_action_add_close_popup'></a>";	
	var thistype=mcsstable.mcssData.getFieldValue(recordid,'tasktype');
	for(var i=0;i<strs.length;i++)
	{
		if (thistype!=strs[i])
		h+="<a class='popupmenu_none'>"+strs[i]+"</a>";
	}
	h+="</div>";
	 
	self = $(e);
	self.after(h);
	$(".popupmenu_none").click(function(){	
		var tasktype=this.innerHTML;
		excuteMoveTask(e,mcsstable,tasktype,recordid);
		$(".popupmenu").remove();
	});	

	 $(document).click(function(event){
		if (event.target.id!=e.id)
			$(".popupmenu").remove();
	 })

	var div = $(".popupmenu"); //要浮动在这个元素旁边的层  
	dom_showPopupMenu(div,e);
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
	//+"<p style='text-align:left'><input type='checkbox' onclick='project_showStaff(this)'>发送通知给:<span id='xuanze' style='display: none;'>[<a href='javascript:void(0)' onclick='project_selectAll(this,\"select\")'>全选</a> | <a href='javascript:void(0)' onclick='project_selectAll(this)'>全不选</a>]</span></p>";
	s+="<div style='display:none;width:90%;padding:10px;overflow:auto; border:#515151 1px solid'>";
	var url = getHomeUrl()+"/Oa/Project/getAllStaff";
	$.getJSON(url,function(data){
		if(data){
			for(var i = 0;i < data.length;i++){
				s+="<input type='checkbox' value='"+data[i]['id']+"' style='margin:5px' />"+data[i]['name'];
			}
		}
		s+="</div>";
		s+="<div style='padding-top:10px;text-align:center;'><a id='"+recordid+"_save' href='javascript:void(0)' class='mcssingbutton btn btn-green'>"+lang.save+"</a></div>";
		s+="<table id='"+recordid+"_commentlist' style='table-layout:fixed;word-wrap: break-word; word-break: break-all;'></table>";
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
		newMCSSTable(recordid+"_commentlist",'oa_pm_taskcomment',{filter:"task_id="+recordid,showfirst:true,hidecheckbox:false,pageposition:'rightdown'}).run();
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

function afterAddDiscussion(id,hint,form)
{
	$("#discussion_"+form.getFieldValue('task_id')).show().addClass("gantetu_addDiscussion_withdata").removeClass("gantetu_addDiscussion").attr('title',clearHTML(form.getFieldValue('content')).substr(0,200)).attr("hasloaddiscussion",true);
	mcdom.closePopup();
	mcdom.alert(hint,'评论','success','fadeout');	
}
//指定移动任务到分组
function excuteMoveTask(e,mcsstable,tasktype,recordid)
{
	var row=$(mcsstable.table).find("tr[recordid='"+recordid+"']").get(0);
	var record=mcsstable.gantetu.mcssData.getRow(recordid);
	if (tasktype)
	{
		var tr=$(".groupname:contains("+tasktype+")").parent().parent().parent();	
		$(row).find("#tasktype select").val(tasktype);
	
		if($(tr).html())
		{		
			$(tr).after($(row));
		}else{
			$(mcsstable.table).find("tbody").append($(row));
		}
	}
	else
	{
		$(mcsstable.table.rows[1]).before($(row));
	}
	mcdom.closePopup(e);
	
	mcsstable.gantetu.mcssData.setFieldValue(recordid,'tasktype',tasktype);
	var rec={mcss_recordtype:"UPDATE",mcss_rowindex:$(row).attr('rowIndex'),recordid:recordid,fieldid:"tasktype",value:tasktype};
	mcsstable.gantetu.mcssData.editNewData(rec);
	if(mcsstable.view!='tableedit')
		mcsstable.gantetu.mcssData.save(null,{alert:false});
	
}

//获得工作模型对象后，把项目设置中的“工作类别”赋予工作模型的“工作类别”的data属性.这样做的目的是避免把工作类别属性分别设置到项目模型和工作模型中
//从mcsstable的新增按钮打开的autoform也会触发gantetu_afterGetModel，因此需要分别处理
function gantetu_afterGetModel(modeldata,owner)
{

var project_data;
if (owner.typename=="MCSSTable")
{
	project_data=owner.gantetu.params.project_data;
	//根据用户设置显示指定的任务字段 name:,begindate:开始,enddate:结束,executer:执行人,finishper:完成率,status:状态,worktime:工时,priority:优先级,score:评分,notes:备注,attach:附件,price:单价,totalprice:总价,unitname:单位,amount:任务量
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
			if (i>10) 
				break;
			var f=model_getFieldById(modeldata.fields,'diyfield'+(i+1));
			f.isvisible='true';
			f.name=fieldArr[i];
		}
	}
}
else
if (owner.typename=="Autoform")
{
	project_data=owner.params.refObject.gantetu.params.project_data;
	gantetu_setFieldVisible(modeldata,project_data);
	var idpull=owner.params.refObject.gantetu.project_fulldata.newids_for_newtask;
	for(var i=0;i<modeldata.fields.length;i++)
	{
		if (modeldata.fields[i].id=='id'){
			if (idpull.length==0){
			}
			modeldata.fields[i].defaultdata=idpull[0];//99是最后一个
			idpull.shift();
			break;
		}
	}
}
/*如果不特殊处理工作类别，下面就没必要
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
*/
}

//根据项目字段设置，来修改模型中的字段的显示与否
function gantetu_setFieldVisible(modeldata,project_data)
{
	//根据用户设置显示指定的任务字段 name:,begindate:开始,enddate:结束,executer:执行人,finishper:完成率,status:状态,worktime:工时,priority:优先级,score:评分,notes:备注,attach:附件,price:单价,totalprice:总价,unitname:单位,amount:任务量
	if (project_data.taskfields)
	{
		var fieldArr=project_data.taskfields.split(",");
		for(var i=0;i<modeldata.fields.length;i++)
		{
			if (fieldArr.indexOf(modeldata.fields[i].id)==-1){
				modeldata.fields[i].isvisible='false';
				modeldata.fields[i].visibleWhenAdd='false';
			}				
			else{
				modeldata.fields[i].isvisible='true';		
				modeldata.fields[i].visibleWhenAdd='true';
			}
		}
	}
	if (project_data.diyfields)
	{
		var fieldArr=project_data.diyfields.split(",");
		for(var i=0;i<fieldArr.length;i++)
		{
			if (i>10) 
				break;
			var f=model_getFieldById(modeldata.fields,'diyfield'+(i+1));
			f.isvisible='true';
			f.name=fieldArr[i];
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

//给新追加的tr行j你行一些处理：如显示时间线、默认值
function afterAppendRow(mctable,row)
{
	//给新增行的任务类别字段赋予与前个任务一样的值ssss
	//var recordid=$(row).prev().attr("recordid");
	//var tasktype=mctable.mcssData.getFieldValue(recordid,'tasktype');
	recordid=$(row).attr("recordid");	
	
	//下面两行好像没用
	//var rec={recordid:recordid,fieldid:"tasktype",value:tasktype};
	//mctable.mcssData.editNewData(rec);	

	var record=mctable.mcssData.getRow(recordid);	
	var gantetu=getGantetu(mctable.params.gantetu_id);
	
	//新增行的分组字段的值应该默认等于当前分组
	if (mctable.groupby && mctable.groupby!='NONEGROUP'){
		var g=$(row).prevAll('.rowgroup').attr('groupname');
		if (g)
		gantetu.editField(recordid,mctable.groupby,g,true);
	}

	gantetu.createOneTimeSpan(row,record);
	gantetu.bindEvernForRows(row);

}

//根据编辑模式调整表格样式
function gantetu_adjustTableStyle(mcsstable)
{
	var fixed="fixed";
	var table=$(mcsstable.table);
	if (mcsstable.view!='tableedit')
	{
        $(mcsstable.table).css('white-space','nowrap');
        $(mcsstable.table).attr('autowithstyle',lang.nofoldlines);
	}
	else
	{
        $(mcsstable.table).css('white-space','');
      	//$(".timeline_td").css('overflow','hidden');
        
        $(mcsstable.table).attr('autowithstyle',lang.compact);		
	}
	var taskcell=table.find("tr[class!='rowgroup']");
	taskcell.find("td.mcsstable_td1").css('overflow','hidden');
	taskcell.find("td.mcsstable_td2").css('overflow','hidden');

	$(mcsstable.table.rows[0]).find("th").css('overflow','hidden');       
	$(mcsstable.table.rows[0]).find("th:first").css('width','40px');       
	//$(mcsstable.table).find("tr[class!='rowgroup']").find("td:first").css('float','right');
}

//加载完数据到表格后事件
function gantetu_afterLoadRows(mcsstable)
{
	var gantetu=getGantetu(mcsstable.params.gantetu_id);
	gantetu.mcssData=mcsstable.mcssData;
	
	var id="executerid";
	if (gantetu.params.executerFieldId)
		id=gantetu.params.executerFieldId;
	gantetu.executerFieldIndex=gantetu.getExecuterFieldIndex(id);

	$("#"+mcsstable.tableid+"_pagebar").css("float","left");
	gantetu_showTimeLines(mcsstable);

	if (gantetu.params.afterLoadRows)//这是甘特图的加载记录后方法，不是mcsstable的加载记录后方法
		gantetu.params.afterLoadRows(mcsstable,gantetu);
	
	mcsstable.showAction('firstpage,lastpage,RecordCount',false);
	
	project_bindMouseEventOnGroup($(mcsstable.table).find("tr"),mcsstable);	


	$(mcsstable.table).find(".mcsstable_record_del").hide();
	$("#"+mcsstable.tableid).find(".mcsstable_record_edit").hide();
	//gantetu.mcsstable.cellEditor=new TableEditor(mcsstable.tableid,mcsstable.mcssData.data,mcsstable.modeldata,{mcssData:mcsstable.mcssData});
//	if ($(mcsstable.table).find('tr').size()>20 && $(mcsstable.table).find('.rowgroup').size()>5)//任务太多时收起分组
//	{
//		$(mcsstable.table).find(".showallgroup").click();
//	}	

	gantetu.createGantetuOption();
	//gantetu_showgroup(mcsstable,gantetu.params.project_data.tasktypes);//不需要了
	
	//mcsstable.showAction('hidedone',false);
	/*
	$(mcsstable.getAction('hidedone')).html(lang.hidedone);
	var status='show';
	if (gantetu.params.project_data.show_options && gantetu.params.project_data.show_options.indexOf("[hidedonetask]")>-1)
	{
		status='hide';
	}
	if (status=='hide')
		showDoneTask(mcsstable,true);
	
	if (status=='hide')
		mcsstable_exeAction_hidedone(mcsstable.tableid);
		*/
	gantetu_adjustTableStyle(mcsstable);	
	
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
//在某个位置添加新组
//currentRow:如果不为空，则在前面添加新组，否则在表末
function gantetu_appenNewGroup(mctable,newname,currentRow)
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
	if (currentRow)
		$(currentRow).before(newRow);
	else
		$(mctable.table).find("tbody").append(newRow);
	var row=$(mctable.table).find(".groupname:contains("+newname+")").parent().parent().parent();
	mctable.currentRow=row;
	project_bindMouseEventOnGroup($(row),mctable);
}

//创建完数据表格后事件
function gantetu_afterRunTableEdit(mcsstable)
{
		
	var h=$(mcsstable.table).find(".mcsstable_th_first").find("input").parent().html();
	//if (mcsstable.view=="tableedit" && $(mcsstable.table).find(".mcsstable_th_first").find(".add_group").size()==0)
	//	h+="<span class='groupaction_to'><input type='button' id='addgroupbtn_atlast' onclick='project_dealGroup(this,\"新组\",\""+mcsstable.id+"\")' value='添加分组'  title='添加分组'  class='add_group'/></span>";
	$(mcsstable.table).find(".mcsstable_th_first").html("<div style='width:75px'>"+h+"</div>");
	//gantetu_showgroup(mcsstable,mcsstable.params.gantetu.params.project_data.tasktypes);//显示所有的组
	if ($(mcsstable.table).find("tr").size()==1)
	{
		$(mcsstable.getAction('add')).click();
	}
	//mcsstable.showAction('hidedone');
	//showDoneTask(mcsstable,true);
	//编辑状态时，把时间线的文字长度变短
	var wordlen=mcsstable.gantetu.params.timelinewordlen_edit;
	$(mcsstable.table).find(".gantetu_taskname").each(function(i,item){
		item.innerHTML=item.innerHTML.substr(0,wordlen);
	})
	//执行全表编辑时没有重新加载数据，但需要修改样式
	gantetu_adjustTableStyle(mcsstable);
}

//处理自定义按钮
function mcsstable_exeAction(action,tableid)
{
	if (action=="hidedone")
		mcsstable_exeAction_hidedone(tableid);
}

//给行绑定鼠标出入事件
function project_bindMouseEventOnGroup(rows,mcsstable)
{
	$(rows).mouseover(function(){
		if (mcsstable.view=='tableedit')
		{
			$(this).find(".groupaction").show();
			//$(this).find(".gantetu_clearDate").show();		
		}
		//$(this).find(".gantetu_moveTask").show();
		$(this).find(".mcsstable_record_del").show();		
		$(this).find(".gantetu_addDiscussion").show();
		$(this).find(".mcsstable_record_edit").show();		
	}).mouseout(function(){
		$(this).find(".mcsstable_record_del").hide();
		//$(this).find(".gantetu_clearDate").hide();		
		$(this).find(".groupaction").hide();
		//$(this).find(".gantetu_moveTask").hide();		
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
McssGantetu.prototype.showTimeLines=function(mcsstable)
{
	this.removeWholeTimeSpan();

	var row=$("#"+mcsstable.tableid).find("tr").get(0);
	this.taskCellCount=row.cells.length;//任务字段数量

	var dom;
	$(row).append(dom);
	this.mcssData=mcsstable.mcssData;
	dom=this.createPrevTimeSpan();
	$(row).append(dom);
	dom=this.getdateTD();
	$(row).append(dom);
	dom=this.createNextTimeSpan();
	$(row).append(dom);	
	this.bindEventForPreNextTimespan();
	//上面创建时间线区块的日历头部
	this.setTableWidth();
	//创建时间线
	this.createTaskTimeSpan();
}

//根据字段数量和日期格子数量调整表格宽度
McssGantetu.prototype.setTableWidth=function()
{
	var mcsstable=this.mcsstable;
//	if (mcsstable.view=='table' || mcsstable.view=='' || mcsstable.view=='celledit')
		$(mcsstable.table).css('table-layout','fixed');
	if (this.params.timespan=='week')
		var w='100%';
	else
	{
		var w=30;
		for(var i=0;i<mcsstable.table.rows[0].cells.length;i++)
		{
			w1=parseFloat(mcsstable.table.rows[0].cells[i].style.width);
			if (!isNaN(w1))
				w+=w1;
		}
		if (w<document.documentElement.clientWidth || Math.abs(w-document.documentElement.clientWidth)<100)
			w="100%";
		else
			w+="px";
	
	}
	$(mcsstable.table).css('width',w);
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

//创建任务字段与时间线之间的那个格子，用来显示一些附加按钮
McssGantetu.prototype.createPrevTimeSpan=function(mctable)
{
		var h="";		
		h+="<th class='gante_header' style='width:40px;'><a  style='cursor:pointer; width:20px;text-decoration:none' title='显示更多字段' id='a_setDiyFields'>...</a><a  style='cursor:pointer; width:20px;text-decoration:none' title='前翻' id='pre_timespan_l'>《</a>&nbsp;<a  style='cursor:pointer;width:20px;text-decoration:none' title='后翻' id='next_timespan_l'>》</a></th>";
		return h;
}
McssGantetu.prototype.createNextTimeSpan=function(mctable)
{
		var h="";		
		h+="<th class='gante_header' style='width:25px;'><a  style='cursor:pointer; width:20px;text-decoration:none' title='前翻' id='pre_timespan_r'>《</a>&nbsp;<a style='cursor:pointer;width:20px;text-decoration:none' title='后翻' id='next_timespan_r'>》</a></th>";
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

function gantetu_afterSetDiyFields(id,data,mcform)
{

	mcdom.closePopup();
	var gantetu=mcform.params.temp_gantetu;
	//如果数据未保存，提示，否则直接刷新
	if (gantetu.mcsstable.cellEditor && gantetu.mcsstable.cellEditor.params.mcssData.state=="CHANGED" 
			&& $(gantetu.mcsstable.table).find("tr").size()>2)
		mcdom.alert(lang.finishsetting,lang.setfieldvisible,'info');
	else
		mcform.params.temp_gantetu.run();
}

McssGantetu.prototype.bindEventForPreNextTimespan=function()
{
	
	function afterRunDiyFields(mcform)
	{
		mcform.addText('diyfields',lang.pm_diyfields_title);
		if (!mcform.getFieldValue('taskfields'))
			mcform.setFieldValue('taskfields','name,begindate,enddate,executer,finishper');
	}
	var _this=this;
	var thistd=this.table.rows[0].cells[this.taskCellCount];
	$("#a_setDiyFields").click(function(){
		var h="<div id='form_diyfields'></div>";
		mcdom.showPopup(this,h,'',null,null,null,null,lang.setvisiblefields);
		var mcform=new Autoform('form_diyfields',{modelid:'oa_pm_taskdiyfield',recordid:'url:KEYFIELD',showFormButton:'SAVE',afterSave:gantetu_afterSetDiyFields,temp_gantetu:_this});
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
	var css="td_work_unstarted";
	if (percent==0 || !percent)
		css="td_work_unstarted";
	else if (percent==1 || percent==-1)
		css="td_work_done";
	else
		css="td_work_doing";
	return css;
}

//给某任务记录的时间线，根据开始结束日期和完成率标上颜色
McssGantetu.prototype.addColorForTimeline=function(row,record)
{
	//根据完成率画出背景色		
	var css='';//this.getTimelineCssByState(record["finishper"]);
	//$(row.cells[this.taskCellCount]).addClass(css);
	css='gantetu_timeline';
	var begin=MCDateTime.newDate(record["begindate"]);
	var end=MCDateTime.newDate(record["enddate"]);
	var date1=this.params.begindate;
	var tds=$(row).children().eq(this.taskCellCount).nextAll().not(":last").each(function(i,item){
		if (date1>=begin && date1<=end)
		{
			//$(item).removeClass("td_work_unstarted").removeClass("td_work_doing").removeClass("td_work_done");
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
	if (!firstdate || !lastdate)
		return '';
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
		var wordlen=this.params.timelinewordlen_view;//截取字符长度
		if (this.mcsstable.view=='tableedit')
			wordlen=this.params.timelinewordlen_edit;
		var wordcolor='rgb(100,100,100)';//时间线字体颜色
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
			
			if (MCDateTime.diffDays(date1,enddate)==-2)
			{
				if (!hasShowInfo)
				{
				if (t.tasktype)
				{
					var show='none';
					if (this.showTaskType) 
						show="";
					s+="<span style='color:"+wordcolor+";white-space:nowrap;display:"+show+";' class='timespan_bar gantetu_tasktype'>["+t.tasktype.substr(0,wordlen)+"]</span>";
				}
				if (t.name)
				{
					var show='none';
					if (this.showTaskName) 
						show="";
					s+="<span style='color:"+wordcolor+";white-space:nowrap;display:"+show+";' class='timespan_bar gantetu_taskname'>"+t.name.substr(0,wordlen)+" </span>";
				}
				if (t.executer)
				{
					var show='none';
					if (this.showExecuter) 
						show="";				
					s+="<span style='color:"+wordcolor+";white-space:nowrap;display:"+show+";' class='timespan_bar gantetu_executer'>"+t.executer.substr(0,wordlen)+" </span>";
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

 
function gantetu_aterExecuteAction(mcsstable,e)
{
	if (e.id.indexOf("cancel")>-1)
	{
		$(mcsstable.table).css('table-layout','fixed');
	}
}

//创建完表头后
function gantetu_afterCreateHeader(mcsstable)
{
	var h="<optgroup label='"+lang.commonuse+"'>";
	h+="<option value='SHOW_UNFINISHED_TASK' tag='USEROPTION'>"+lang.unfinished+"</option>";
	h+="<option value='SHOW_TODAY_TASK' tag='USEROPTION'>"+lang.todaybeginorend+"</option>";
	h+="<option value='SHOW_AFTERODAY_TASK' tag='USEROPTION'>"+lang.fromtoday+"</option>";
	h+="<option value='SHOW_THISWEEK_TASK' tag='USEROPTION'>"+lang.thisweektask+"</option>";
	h+="</optgroup>";
	$(mcsstable.getAction('historysearch')).append(h).change(function(){
		var v=this.value;
		if (v=='SHOW_UNFINISHED_TASK'){
			mcsstable.searchword ="finishper<><yh>1<yh> and finishper<><yh>-1<yh>";//<yh>是后台处理的特殊标记，会当做高级搜索的条件
			mcsstable_loaddatarows(mcsstable.tableid);
		}
		else if (v=='SHOW_TODAY_TASK'){
			var today=MCDateTime.getGoodDate(new Date());		
			mcsstable.searchword ="begindate=<yh>"+today+"<yh> or enddate=<yh>"+today+"<yh>";//<yh>是后台处理的特殊标记，会当做高级搜索的条件
			mcsstable_loaddatarows(mcsstable.tableid);
		}
		else if (v=='SHOW_THISWEEK_TASK'){
			var today=new Date();		
			var weekday=today.getDay();
			var begindate=MCDateTime.getGoodDate(today.addDays(weekday*-1));
			var enddate=MCDateTime.getGoodDate(today.addDays(weekday*-1).addDays(6));
			var filter=utils_getBeginEndDateSql('begindate','enddate',begindate,enddate);
			filter=filter.replace(/\'/g,"<yh>");
			mcsstable.searchword =filter;
			mcsstable_loaddatarows(mcsstable.tableid);
		}
		else if (v=='SHOW_AFTERODAY_TASK'){
			var today=MCDateTime.getGoodDate(new Date());
			var filter="begindate>='"+today+"'";
			filter=filter.replace(/\'/g,"<yh>");
			mcsstable.searchword =filter;
			mcsstable_loaddatarows(mcsstable.tableid);
		}
		
	});
}

function utils_getBeginEndDateSql(beginfield,endfield,begindate,enddate)
{
	return "(("+beginfield+"<='"+begindate+"' and "+endfield+">='"+begindate+"') or  ("+beginfield+">='"+begindate+"' and "+endfield+"<>'0000-00-00' and "+endfield+"<='"+enddate+"') or ("+beginfield+"<='"+enddate+"' and "+endfield+">='"+enddate+"') or ("+beginfield+"<='"+begindate+"' and "+endfield+">='"+enddate+"'))";
}
//设置显示隐藏状态
//show:要设置的状态，显示还是隐藏
function showDoneTask(mcsstable,show)
{
	if (mcsstable.getAction('hidedone'))
	{
		if (show)
			$(mcsstable.getAction('hidedone')).html(lang.showorhidedone).attr('status','show').attr("title",lang.showorhidedonetask);
		else
			$(mcsstable.getAction('hidedone')).html(lang.showorhidedone).attr('status','hide').attr("title",lang.showorhidedonetask);
	}
}

//显示/隐藏已完成任务的按钮,互相切换
function mcsstable_exeAction_hidedone(tableid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (mcsstable.temp_finishper_fieldindex==undefined)
	{
		for(var i=0;i<mcsstable.table.rows[0].cells.length;i++)
		{
			if (mcsstable.table.rows[0].cells[i].id=='finishper')
			{
				mcsstable.temp_finishper_fieldindex=i;
				break;
			}
		}
	}

	var show=false;
	if ($(mcsstable.getAction('hidedone')).attr('status')=='hide')
	{
		showDoneTask(mcsstable,true);
		show=true;
	}
	else
	{
		showDoneTask(mcsstable,false);
	}
	
	if (mcsstable.data.length>0)
	{
	
		for(var i=0;i<mcsstable.mcssData.data.length;i++)
		{
			if (mcsstable.mcssData.data[i]['finishper']==1 || mcsstable.mcssData.data[i]['finishper']==-1){
				if (show)
					$(mcsstable.table).find("tr[recordid='"+mcsstable.mcssData.data[i]['id']+"']").show();
				else
					$(mcsstable.table).find("tr[recordid='"+mcsstable.mcssData.data[i]['id']+"']").hide();
			}
		}
	}
	return;
	
	if (!utils_isSharingPage())//还应该加上判断，状态修改了还需要保存，不要每次都保存
	{
		var showoption="hidedonetask";
		if (show)
			showoption="showdonetask";	
		var url=getHomeUrl()+"/Oa/Project/saveHideDoneTaskOption/";
		$.post(url,{projectid:mcsstable.gantetu.params.project_data.id,showoption:showoption})
	}
}

//隐藏已完成任务的按钮,互相切换
function gantetu_hidedonetask(mcsstable)
{	
	for(var i=0;i<mcsstable.mcssData.data.length;i++)
	{
		if (mcsstable.mcssData.data[i]['finishper']==1 || mcsstable.mcssData.data[i]['finishper']==-1){
				$(mcsstable.table).find("tr[recordid='"+mcsstable.mcssData.data[i]['id']+"']").hide();
		}
	}
}

//新增前
function gantetu_beforeAddNew(mcsstable)
{
	if (!mcsstable.addnewCount)
		mcsstable.addnewCount=0;
	mcsstable.addnewCount++;
	if (mcsstable.gantetu.project_fulldata.newids_for_newtask.length<mcsstable.addnewCount)
	{
		alert('一次性新增的任务太多了，没有可分配的id，请重新加载。');//因为每次从服务器取的id（newids_for_newtask）是99个
		return false;
	}
	return true;
}