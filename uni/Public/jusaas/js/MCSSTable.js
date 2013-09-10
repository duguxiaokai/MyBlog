//document.write("<span style='color:red'>调试中...</span>");
//alert("看到这行表明js文件被加载了。");
g_dinamic_cssjs.push("jusaas/js/MCSSTable.js");

/*
制作团队:Mcssing小队
更新时间:2012/12/07
--------------------------------------------------------------------------------------------------------------------------------------------------------------
MCSSTable类概述：
1、用于快速根据MCSS数据对象配置生成HTML表格
2、需要显示表格的网页，只要简单几行代码即可ajax方式生成table。
3、只需通过适当的配置，生成的table就具有浏览、翻页、删除、编辑、搜索等功能。
4、通过这个类的接口，还可以给在第一个栏和最后一栏添加额外的代码，例如在每一行的最后显示某个按钮。
--------------------------------------------------------------------------------------------------------------------------------------------------------------
使用范例:
此范例会生成一个带有增删改简单搜索翻页的图标式按钮表格
var field_dealing=[{'loginuser':myshow},{'tasktype':myshow2}];
var mcssTable=new MCSSTable({tableid:"mcss_table",modelid:modelid,showfirst:true,last_td_name:'操作',toolbar:"add,edit,del,search",
special_field_show:field_dealing,last_td_func:last_td_func,sql:"select * from table1s",showRecordCount:true,showRecordActionAtLast:true,actionStyle:"ICON",
pageposition:"rightdown"});
mcssTable.run();	
--------------------------------------------------------------------------------------------------------------------------------------------------------------
Params参数说明：
tableid:HTML的Table的id
modelid:MCSS的模型ID
homeUrl:方法路径前缀，在TP中一般写成"__APP__"即可
selectType:radio:单选按钮,checkbox：复选框
hidecheckbox:隐藏第一列的复选框
toolbar:显示在操作按钮的按钮。如果无此参数，则自动取模型的actions属性
showtitle:显示标题
showfirst:数据在表格第一列前增加新的列，一般用于储存“打开、编辑、选择”等按钮。如果选择此参数，则first_td_func也是必须的，否则列头与下面的内容对不齐
sql:如果传递了sql参数，获取数据时将以该sql语句代替模型的sql属性
filter:执行sql的条件
special_field_show:自定义表格上字段内容展现形式
showRecordActionAtLast:true表示把记录操作按钮显示在每行最后，否则显示在第一栏
showRecordCount:true表示直接把记录总数显示出来
pageposition:翻页按钮位置，如果为'rightdown'，则显示在右下角，否则显示在工具栏
first_td_name:额外在第一列添加的操作列的名称
first_td_func：额外增加的第一列的内容的产生方法，是一个function名
last_td_name:额外添加在最后一列的名称
last_td_func：额外增加的最后一列的内容的产生方法，是一个function名
beforeAddRow:添加每行记录前的方法，可以用来添加额外的td或tr，如分组显示
afterAddRow(mctable,tr_html,rowData):添加每行记录后的方法，可以用来添加额外的td或tr，如分组显示(此方法暂无)
addGroupInfo(mctable,rowData):添加每行记录后的方法，可以用来添加额外的td或tr，如分组显示(此方法暂无)
style:simple/normal  风格:简约/标准 默认为标准风格
exporturl：执行特殊导出时可以添加此参数跳入自定义的Action中
afterLoadRows：数据加载完后执行的方法
actionStyle:按钮的展现形式 TEXT为文字按钮 ICON为图片按钮 
view:默认的显示模式。tableedit:表格编辑，calendar：日历，table：列表。空值表示列表
hideToolbar:true表示不创建工具栏
lang:传入的语言类型,cn/en
callbackAfterDeleted:删除一条记录后执行的方法,参数为mcsstable和删除的rowids
defaultvalue：设置打开表单的默认值,格式: pid:'123',name:'jack'
currentpage:当前页
searchword:搜索关键词
width:列表宽度
pagerows:列表默认显示一页的条数
hideLastTd:隐藏最后一列
hideLastTr:隐藏最后一行
deleteRow:删除一行,参数为删除的rowids和mcsstable
confirmword:自定义的确定删除语言,如”您真的要删除吗?”
popupFormID:弹出列表的模型id
popupFieldID: 弹出列表的所选字段id
showConfirmButton:弹出列表显示确定按钮
impurl:
refObject:
params.afterDeleteRows:自定义删除后方法
params.diffLineCss==false:鼠标移动到行上必不有样式效果
params.userAction:用户自定义加入记录操作栏的方法
params.afterGetModel:获得模型后的事件，可用于动态修改模型的属性
params.view:表格表现形式。'table'或空表示常规的列表；‘tableedit’表示全表编辑，‘celledit’表示单元格编辑，‘calendar’表示日历
params.beforeAddNew
function myshow2(fieldvalue)
{
	return "<strong>"+fieldvalue+"</strong>";
}
function myshow(fieldvalue,record)
{
	return "<span style='color:red;font-size:30px;'>"+fieldvalue+":"+record.id+"</span>";
}	
function first_td_func(id)
{
	return "<input type='button' value='处理' onclick='openRecord("+id+")' >";
}
function last_td_func(id)
{
	return "<input type='button' value='查看' onclick='viewRecord("+id+")' >";
}
this.getRow(recordid):根据记录id获得行（tr）
 */
//var MCSSTables=new Array;//多个MCSSTable对象管理
//MCSSTables定义在common.js中
function mcsstable_updateMCSSTables(id,data)
{	
	//如果已经存在则更新
	for(var i=0;i<MCSSTables.length;i++){
		if (MCSSTables[i].id==id)
		{
			MCSSTables[i].data=data;
			return;
		}
	}
	//如果不存在则添加
	MCSSTables.push({"id":id,data:data});
}
function mcsstable_getMCSSTable(id){
	for(var i=0;i<MCSSTables.length;i++){
		if (MCSSTables[i].id==id)
		{
			r = MCSSTables[i].data;
			break;
		}
	}
	return r;
}


//创建MCSSTable对象的更简单的方式
function newMCSSTable(id,modelid,params) 
{
	if (!params)
		params={};
	//params.id=id;
	//params.modelid=modelid;
	//return new MCSSTable(params, params.first_td_func, params.last_td_func);
	return new MCSSTable(id, modelid,params);
}
 
//function MCSSTable(params, first_td_func, last_td_func) {
function MCSSTable(id,modelid,params) {
	this.typename='MCSSTable';
	//alert("看到这行表明对象被创建了。");
	if (!document.getElementById(id))
	{
		alert("创建MCSS表格失败。因为“tableid”参数值("+id+")是错误的！");
		return;
	}

	this.id=id;
	this.modelid=modelid;
	this.params=params;	
	this.tableid=id;//即将创建的table的id
	this.params.tableid=id;//即将创建的table的id。为了兼容历史
	this.params.modelid=modelid;
	//如果表格容器是div的处理。但尽量不要用div，因为可能的Bug尚未全面测试
	var o=document.getElementById(id);
	if (o.nodeName=="DIV")
	{
		this.tableid=this.id+"_table";
		this.params.tableid=this.tableid;
		$("#"+this.id).html("<table id='"+this.tableid+"' style='width:100%;'></table>");	
	}

	mcsstable_updateMCSSTables(this.tableid,this);

	this.fields = new Array();
	this.first_td_func=params.first_td_func;
	this.last_td_func=params.last_td_func;
			
	this.currentpage=1;
	if (params.currentpage)
		this.currentpage=params.currentpage;

	this.totalpages=-1;//总页数
	this.pagerows=10;//每页记录数
	this.searchword='';
	if (params.searchword)
		this.searchword=params.searchword;
	this.filter=params.filter;//数据过滤器，允许程序动态修改
	this.view="table";
	if (params.view)
		this.view=params.view;

		
	this.lang='cn';//语言，cn或en
	if (params.lang)
		this.lang=params.lang;
	if (!params.homeUrl)
		params.homeUrl=g_homeurl;
	if (params.showRecordCount==undefined)
		params.showRecordCount=true;
	if (params.exporturl)
		this.exporturl=params.exporturl;
		
	//如果没有传入下列url，则采用默认的url
	if (!params.getDataListUrl)
		params.getDataListUrl=params.homeUrl+g_systempath+"/getData";
	if (!params.getModelUrl)
		params.getModelUrl=params.homeUrl+g_systempath+"/getModelData";

	if (params.homeUrl.substring(params.homeUrl.length)!="/")
	{
		params.homeUrl+="/";
	}
	if(!params.showSaveAndAddButton)
		this.showSaveAndAddButton=params.showSaveAndAddButton;
	this.getModelUrl = params.getModelUrl;
	this.getDataListUrl = params.getDataListUrl;

	this.modeldata;
	this.keyfield='id';
	this.actions = "";
	this.order = "";//手工修改后的排序
	this.rooturl=getrooturl();
	this.mcss_theme=getCookie("mcss_theme");
	this.fieldData;//不知道哪里用到
	this.selectedRowsID='';//已选记录的id列表，用逗号分隔
	this.recordCount=0;//当前这些取到的记录数量，用于判断翻页
	this.table=$("#"+this.tableid).get(0);
	//开始创建表格
	this.run = function(func) {
		$("#"+this.tableid).empty();
		$("#"+this.tableid+"_action_pagebar").remove();
		$("#"+this.tableid).attr("modelid",params.modelid);

		this.loadJS();
		this.runWhenFinished=func; //第一次创建了表格并加载了数据后执行的方法
		var _this=this;
		//alert("控制器方法路径是："+this.getModelUrl);
		$.getJSON(this.getModelUrl,
			{modelid : params.modelid	},function(data){
				//alert("控制器方法返回的模型数据是："+data);
				if (!data)
				{
					$("#"+_this.id).attr('modelid',_this.modelid);
					$("#"+_this.id).html("<span style='color:red'>ERROR:模型结构错误("+_this.modelid+")</span>");
					return;
				}
				var err=common_getAccessError(data);				
				if (err)
				{
					$("#"+_this.id).html(err);
					return;
				}
				_this.modeldata=data;
				_this.parseModel();
				if (_this.params.afterGetModel)
				{
					_this.params.afterGetModel(_this.modeldata,_this);
				}
				mcsstable_createTable(data,params.tableid);
				
			}
		);
	}
	
	this.loadJS=function()
	{
		mcss_importJS("jusaas/js/dom.js");
		mcss_importJS("jusaas/js/autoform.js");		
		mcss_importJS("jusaas/js/JSData.js");	
		//mcss_importJS("jusaas/js/viewImage.js");	
		mcss_importJS("js/popup.js");	
		mcss_importJS("js/DatePicker/WdatePicker.js");				
	}
	
	this.getFieldValue=function(id,fieldname)
	{
		if (this.data)
		{
			for(var i=0;i<this.data.length;i++)
			{
				if (this.data[i].id==id)
				{
					//return this.data[i].
				}
			}
		}
		return '';
	}
	this.reload=function()
	{
		 mcsstable_reload(this.tableid);
	}
	this.showAllActions=function(yesno)
	{
		if (yesno==false)
			$("#"+this.tableid+"_caption").children().hide();
		else
			$("#"+this.tableid+"_caption").children().show();
	}
	this.showAction=function(actions,show)
	{
		var actionArr = actions.split(',');
		for(var i = 0;i < actionArr.length;i++){
			var action = actionArr[i];
			$("#"+this.tableid+"_action_"+action).show();
			if (show==false)
				$("#"+this.tableid+"_action_"+action).hide();
		}
	}
	//获得工具栏dom对象	
	this.getToolBar=function()
	{
		return document.getElementById(this.tableid+"_caption");
	}
	//获得指定按钮dom对象,action例子:add表示新增按钮，edit表示编辑按钮	
	this.getAction=function(action)
	{
		return document.getElementById(this.tableid+"_action_"+action);
	}
	this.parseModel=function()
	{
		//form1.modeldata=data;
		var fields=this.modeldata.fields;
		if (this.params.defaultValue)
		{
			var defaultValue=this.params.defaultValue;
			var arr=defaultValue.split(",");
			for(var i=0;i<arr.length;i++)
			{
				var arr1=arr[i].split(":");
				if (arr1.length==2)
				{
					fields=autoform_setArrPropValue(fields,arr1[0],'defaultdata',arr1[1]);
				}
			}
	 
		}
		this.modeldata.fields=fields;
	
	}
	//显示／隐藏所有分组的按钮
	this.getShowHideGroupBtn=function(tableid)
	{	
		return "<input type='button' id='"+tableid+"_expandAllGroup'  class='showallgroup' onclick='mcsstable_expandAllGroup(this);' value='' title='隐藏/显示所有组' />";
	}
	
}

//重新加载表格的模型和数据
function mcsstable_reload(tableid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	mcsstable.currentpage=1;
	mcsstable.last_td_value="";
	mcsstable_loaddatarows(tableid); 
}

function mcsstable_expandAllGroup(e)
{
	if (!e.tag || e.tag=='show')
	{
		//e.value='显';
		$(".expandgroup").find('a').children('img').attr('alt',"展开");
		$(".expandgroup").find('a').children('img').attr('src',getrooturl()+"/Public/jusaas/mctree/img/jia.gif");
		$(".mcsstable_tr1").hide();
		$(".mcsstable_tr2").hide();
		e.tag="hide";
	}
	else
	{
		//e.value='隐';
		$(".expandgroup").find('a').children('img').attr('alt',"收起");
		$(".expandgroup").find('a').children('img').attr('src',getrooturl()+"/Public/jusaas/mctree/img/jian.gif");
		$(".mcsstable_tr1").show();
		$(".mcsstable_tr2").show();
		e.tag="show";
	}
}

function mcsstable_createTable(modeldata,tableid) {

	if (!modeldata) 
		return;
	var data=modeldata;
	$("#"+tableid).addClass("mcsstable");
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (mcsstable.params.style)
		$("#"+tableid).attr("style",mcsstable.params.style);
	if (mcsstable.params.width)
		$("#"+tableid).css("width",mcsstable.params.width);
	
	if (!mcsstable.modeldata)
		return;
	if (mcsstable.modeldata.view)
		mcsstable.view=mcsstable.modeldata.view;
	if (data.keyfield && data.keyfield!='')
		mcsstable.keyfield=data.keyfield;
	if (data.pagerows)
		mcsstable.pagerows=data.pagerows;
	var params=mcsstable.params;
	if (params.pagerows)
		mcsstable.pagerows=params.pagerows;
	
	if (data.openurl=="default") {
		data.openurl = g_systempath+"/viewRecord/model/"+params.modelid+"/tablename/"+data.tablename;
	}
	
 	if(!data.delurl || data.delurl=="default"){
		data.delurl = g_systempath+"/deleterows";
	}
	
	mcsstable.modeldata.openurl=data.openurl;
	mcsstable.modeldata.editurl=data.editurl;
	mcsstable.modeldata.delurl=data.delurl;
	if(!params.style && params.style!='NONE' && params.hideToolbar!=true)
		mcsstable_createToolbar(tableid);		
	
	data = data["fields"];

	$("#" + params.tableid).append("<tr id='"+params.tableid+"tableheader' modelid='"+params.modelid+"'></tr>");
	
	var fields = new Array();
	var s = "";

	mcsstable.ifShowLastTd=ifShowLastTd(mcsstable);
	
	//以下创建表格标题栏
	if ($("#"+params.tableid+"tableheader").html()=='')
	{
		var fcount=0;
		var html = '';
		if (params.showfirst)//显示第一栏
		{
			fcount++;
			if (mcsstable.modeldata.groupby=='y')
			{			
				html +=mcsstable.getShowHideGroupBtn(tableid);
			}
			if (!params.hidecheckbox){
				if(params.selectType=="radio")
					html += "<a id='" + params.tableid+ "_checkbox_selectall' style='border:none'>操作</a> ";
				else
					html += "<input  type='checkbox'  id='" + params.tableid+ "_checkbox_selectall' style='border:none'/> ";
			}
			

			if (params.first_td_name)
				html += params.first_td_name;

			//if (html != "")
			{
				s += "<th class='mcsstable_th_first' style='padding:0 10px; width:60px;'>" + html + "</th>";
			}
		}
		
		var v;
		for(var i=0;i<data.length;i++)
		{
			v=data[i];
			if (v.isvisible != "false") 
			{	
				if (v.prop && (v.prop).indexOf("NOTORDER")!=-1)
				{
					s += "<th id='" + v.id +" ";
					if(v.style)
						s+="style='"+v.style+"'";
				}
				else
				{
					s += "<th id='" + v.id + "' fieldid='" + v.id + "' title='排序' onClick='mcsstable_order(this,\""+params.tableid+"\")' onmouseover='mcsstable_headeronmouseover(this)' onmouseout='mcsstable_headeronmouseout(this)'";
					if(v.style)
						s+="style='cursor:pointer;"+v.style+"'";
				}
				if(lang[v.name])
					v.name=lang[v.name];
				s+=">"+v.name + "</th>";
				fields.push(v.id);
			}
		}
		//133 这个判断实在太复杂了，有可能搞不定所有情况，sorry. 陈
		if (mcsstable.ifShowLastTd)
		{	
			var act=params.last_td_name==undefined?'操作':params.last_td_name;
			s += "<th class='mcsstable_th_first' style='padding:0 10px; width:60px' >"+act+"</th>";
			fcount++;
		}

		
		mcsstable.fields = fields;
		$("#"+params.tableid+"tableheader").append(s);
		
		$("#"+params.tableid+"_checkbox_selectall").click(function(){mcsstable_selectAll(this,params.tableid);});
		
	}
	if (mcsstable.params.afterCreateHeader)
		mcsstable.params.afterCreateHeader(mcsstable);

	//以上创建表格标题栏
	$("#"+tableid).append("<tr><td colspan='"+fields.length+fcount+"'>　<img src='"+mcsstable.rooturl+"/Public/Images/loading.gif'/></td></tr>");

	
	mcsstable_loaddatarows(tableid); //加载记录数据	
	
	if (mcsstable.runWhenFinished)
		mcsstable.runWhenFinished(mcsstable);
}

//如果鼠标放到字段头上超过2秒，且字段是隐藏的（宽度小于5），则显示该字段
//var mouseonheader_start;
function mcsstable_headeronmouseover(e)
{
//	if (!mouseonheader_start)
//		 mouseonheader_start=1;
	if (parseFloat(e.style.width)<5)
		e.style.width='50.678px';
		
}
function mcsstable_headeronmouseout(e)
{
	if (e.style.width=='50.678px')
		e.style.width='3px';
}

//是否显示最后一栏
function ifShowLastTd(mcsstable)
{
	var params=mcsstable.params;
	var toolbar=','+params.toolbar+',';
	if  (!params.hideLastTd //既然指示隐藏，当然就不要显示了
		&& //下面的指示表示需要操作栏
			(
			params.style=="simple" 
			|| params.showRecordActionAtLast
			|| mcsstable.last_td_func 
			)
		&& //下面的条件表示操作栏中至少要有一样东西。
			(
				params.userAction || mcsstable.last_td_func || toolbar.indexOf(',open,') > -1 || toolbar.indexOf(',edit,') > -1 || toolbar.indexOf(',del,') > -1
			)
		)
		return true;
	return false;
}
//列排序
function mcsstable_order(obj,tableid)
{	
	var mcsstable=mcsstable_getMCSSTable(tableid);
	$(obj).parent().children().attr("class","order_default");
	if($(obj).data("orderdirection")=="desc")
	{
		$(obj).data("orderdirection","asc");
		$(obj).attr("class","order_desc");
		mcsstable.orderByHand=obj.id+" desc";
	}
	else
	{
		$(obj).data("orderdirection","desc");
		$(obj).attr("class","order_asc")
		mcsstable.orderByHand=obj.id+" asc";
	}
	mcsstable_loaddatarows(tableid);
}
//显示第一页数据
function mcsstable_loadfirstrow(tableid) {
	var mcsstable=mcsstable_getMCSSTable(tableid);
	mcsstable.currentpage=1;
	mcsstable_loaddatarows(tableid);
}

//重新给表格添加行
function mcsstable_loaddatarows(tableid) {
	
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (!mcsstable)	return;
	var params=mcsstable.params;
	var fields=mcsstable.fields;
	var modeldata=mcsstable.modeldata;
	var first_td_func=mcsstable.first_td_func;
	var last_td_func=mcsstable.last_td_func;
	//下面的escape方法在对应php中现在没有解码却正确，用了解码函数却不正确。因此没有用。对应的代码是$filterFromClient=($_REQUEST['filter']);
	try
	{
		var filter1=mcsstable.filter;
		if (navigator.appName.indexOf("Microsoft")>-1)
		{
			//filter1=escape(filter1);
			//mcsstable.searchword=escape(mcsstable.searchword);
		}

		if (params.data)
		{
			//对从参数传进来的数据，其数量可能超过模型设置的每页记录数，因此也要处理下			
			var temprows=new Array();
			for(var i=(mcsstable.currentpage-1)*mcsstable.pagerows;i<(mcsstable.currentpage)*mcsstable.pagerows;i++)
			{
				if (params.data[i])
				temprows.push(params.data[i]);
			}
			mcsstable_loadRows(mcsstable,temprows);	
			//mcsstable_loadRows(mcsstable,params.data);	
		}		
		else
		{
			var sql="";
			//如果mcsstable类接收了sql参数，则把该参数传递给后台，以代替模型的sql属性。这样便于程序员在前台灵活组件sql语句，但要注意sql的字段要与模型字段列表匹配
			mcsstable.order="";
			if (mcsstable.groupField=="NONEGROUP")
				mcsstable.order='';
			else
			if (mcsstable.groupField==""){
				mcsstable.order=mcsstable.modeldata.orderby;
				var fields=mcsstable.modeldata.orderby.split(",");
				if (fields.length>1){
					fields=fields[0].split(' ');
					mcsstable.order=mcsstable.groupField=fields[0];
				}
			}
			else
			{
				if (mcsstable.groupField)
					mcsstable.order=mcsstable.groupField;
				if (mcsstable.order_updown)
					 mcsstable.order+=" "+mcsstable.order_updown;
			}
			
			if (mcsstable.orderByHand)
			{
				if (mcsstable.order)
					mcsstable.order+=",";
				mcsstable.order+=" "+mcsstable.orderByHand;
			}
			if (params.sql) 
				sql=params.sql;
			if (filter1)
			filter1=filter1.replace(/\'/g, "<yh>");
			
			var p={modelid:mcsstable.modelid,filter:filter1,orderby:mcsstable.order,page:mcsstable.currentpage,pagerows:mcsstable.pagerows,sosoword:mcsstable.searchword,sql:sql};
			//mcss_getDataByModel(afterGetTableData,p);
			//return;//用上面新方法代替下面的
			$.getJSON(params.getDataListUrl, p,	function(data) {
					//alert("控制器方法返回的记录数组是："+data);
					mcsstable_loadRows(mcsstable,data);	
				}
				
			);
		}	
	}
	catch(ex)
	{
		//alert(ex.message);
	}
	
}

//根据模型获取到数据列表后事件处理
function afterGetTableData(data,parentObject)
{
	//parentObject在这里指mcsstable
	mcsstable_loadRows(parentObject,data);	

}

//给计算表达式类型的字段计算值
function calculated(mcsstable,data)
{
	var fields=mcsstable.modeldata.fields;
	var v="";
	
	for(var i=0;i<fields.length;i++)
	{
		if (fields[i].type=='calculated')
		{
			for(var j=0;j<data.length;j++)
			{
				var v=fields[i].data;
				for (var k=0;k<fields.length;k++)
				{ 		
				
					//if (data[j][fields[k].id])
					{
						if (fields[k].fieldtype=='float' || fields[k].fieldtype=='real' || fields[k].fieldtype=='int')
							v=v.replace(fields[k].id,data[j][fields[k].id]);
						else
							v=v.replace(fields[k].id,"'"+data[j][fields[k].id]+"'");
					}
				}
				try{
					data[j][fields[i].id]=eval(v);
				}
				catch(e){
					//alert();
				}
			}
		}
	}
	return data;
}

//根据已获得的data加载到表格中
function mcsstable_loadRows(mcsstable,data)
{
	function showError(tableid,err)
	{
		if (document.getElementById(tableid).rows.length>0)
		{
			//$("#"+tableid).after("<div id='"+tableid+"_nodata'>"+err+"</div>");
			$("#"+tableid).append("<tr id='"+tableid+"_nodata'><td colspan='"+document.getElementById(tableid).rows[0].cells.length+"'>"+err+"</td></tr>");
		}
	}
	var tableid=mcsstable.tableid;
	$("table[id='"+tableid+"'] tr:not(:first)").remove();
	$("#"+tableid+"_nodata").remove();
	var err="";
	if (data===false)
		err=lang.getfailed;
	else
		err=common_getAccessError(data);
	if (err)
	{
		showError(tableid,err);
		return;
	}
	//以上是错误处理
	

	mcsstable.mcssData=new MCSSData(data,mcsstable.modeldata,{refObject:mcsstable,afterDataChanged:mcsstable.params.afterDataChanged});
	data= calculated(mcsstable,data);
	mcsstable.data=data;

	//设置分组字段
	mcsstable.groupby=mcsstable.groupField;
	if(!mcsstable.groupby && mcsstable.modeldata.groupby=="y" && mcsstable.modeldata.orderby){
		var arr = mcsstable.modeldata.orderby.split(',');
		arr = arr[0].split(' ');
		mcsstable.groupby=arr[0];
	}

	mcsstable.last_td_value="";		
	mcsstable.hasSummaryField=ifNeedGroupSummary(mcsstable.modeldata.fields);//是否有合计字段
	if (data.length>0)
	{
		if (mcsstable.view=='calendar')
			mcsstable_loadCalendarRows(mcsstable);	
		else
		{
			mcsstable_createTableBody(tableid,data);
		}		
		
		var rows=$("#"+tableid).get(0).rows;
		if (rows.length==0)
			return;
	}
	else
	{
		showError(tableid,lang.nodata);
	}
	
	if (mcsstable.view=='tableedit')
		mcsstable_tableEdit(tableid);
	else
	if (mcsstable.view=='celledit')
		mcsstable_cellEdit(tableid);
	
	if (mcsstable.params.showRecordCount)
		mcsstable_getRecordCount(mcsstable.tableid);
		
	setCookie(mcsstable.tableid+"_action_total",data.length);
	

	//不应该需要single模式
	//if(mcsstable.params.style=="simple")
	//{
	//	mcsstble_createPagerForSimple(mcsstable);
	//}
	//else
		mcsstble_setPager(mcsstable);

	mcsstable_setRecordIndex(tableid);
	
	if (mcsstable.params.afterLoadRows)
	{
		mcsstable.params.afterLoadRows(mcsstable);
	}
	
//	mcsstable_setView(tableid);	
//	mcsstable_setView.view="";
}

//创建表格的数据区域的tr
function mcsstable_createTableBody(tableid,data)
{
		var s = "";
		var line=1;
		for(var i=0;i<data.length;i++)
		{
			s += mcsstable_addonerow(data[i],tableid,i);
		}
		var mcsstable=mcsstable_getMCSSTable(tableid);
		$("#" + tableid).append(s);
}
//根据当前数据量禁用某些翻页按钮
function mcsstble_setPager(mcsstable)
{
	//还没写好
	var data=mcsstable.data;
	var tableid=mcsstable.tableid;
	if(mcsstable.currentpage==1)
	{
		//$("#"+tableid+"_firstpage").addClass('actionicons firstpage_disabled');
		//$("#"+tableid+"_prepage").addClass('actionicons prepage_disabled');
	}
	else
	{
		//$("#"+tableid+"_firstpage").removeClass('actionicons firstpage_disabled');
		//$("#"+tableid+"_prepage").removeClass('actionicons prepage_disabled');
	}
	if(data.length<mcsstable.pagerows)//本页记录数不足，则一定是末页；但未考虑最后一页的数据数量刚好等于每页记录数的情况，因为这涉及总记录数的记录，会影响效率
	{	
		//$("#"+tableid+"_lastpage").addClass('actionicons lastpage_disabled');
		//$("#"+tableid+"_nextpage").addClass('actionicons nextpage_disabled');
	}
	else
	{	
		//$("#"+tableid+"_lastpage").removeClass('actionicons lastpage_disabled');
		//$("#"+tableid+"_nextpage").removeClass('actionicons nextpage_disabled');
	}	
	
}


//给记录序号排序
function mcsstable_setRecordIndex(tableid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (!mcsstable.modeldata)
		return;
	var fields=mcsstable.modeldata.fields;
	for(var i=0;i<fields.length;i++)
	{
		if (fields[i].type=='recordindex')
		{
			var ths=$("#"+tableid).find("tr").get(0).cells;			
			for(var j=0;j<ths.length;j++)
			{
				if (ths[j].id==fields[i].id)
				{
					$("#"+tableid).find("tr").each(function(k,item){
						 if (k>0 && (item.className=="mcsstable_tr1" || item.className=="mcsstable_tr2"))
							item.cells[j].innerHTML=k;
					})
				
				}
			}

			
		}
	}

}

function mcsstable_selectAll(self,tableid) {
	var mcsstable=mcsstable_getMCSSTable(tableid);
	var checkboxArr = document.getElementsByName(tableid + "_checkbox");
	for ( var i = 0; i < checkboxArr.length; i++) {
		if(self.checked){
			checkboxArr[i].checked = true;
			mcsstable_whencheckall(checkboxArr[i],tableid);
		}else{
			checkboxArr[i].checked = false;
			mcsstable.selectedRowsID='';
		}
	}
}

/*
点击隐藏和显示
*/
function mcsstable_slidenogroup(obj){
	if($(obj).children('img').eq(0).attr('alt')=="收起"){
		$(obj).children('img').eq(0).attr('src',getrooturl()+"/Public/jusaas/mctree/img/jia.gif");
		$(obj).children('img').eq(0).attr('alt',"展开");
	}else{
		$(obj).children('img').eq(0).attr('src',getrooturl()+"/Public/jusaas/mctree/img/jian.gif");
		$(obj).children('img').eq(0).attr('alt',"收起");
	}
	var thisgroupindex=$(obj).parent().parent().parent().attr('rowIndex');
	var nextgroupindex=$(obj).parent().parent().parent().nextAll("[class='rowgroup']").eq(0).attr('rowIndex');
	if(nextgroupindex)
		$(obj).parent().parent().parent().nextAll('tr:lt('+(nextgroupindex-thisgroupindex-1)+')').toggle();
	else
		$(obj).parent().parent().parent().nextAll().toggle();
}



/*
创建一个tr的html代码
v:一条记录数据
i：当前页第几条数据
*/
function mcsstable_addonerow(rowData, tableid,i) 
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (!mcsstable.data)
		return;
	var modeldata=mcsstable.modeldata;
	var fields=mcsstable.fields;
	var first_td_func=mcsstable.first_td_func;
	var last_td_func=mcsstable.last_td_func;
	var params=mcsstable.params;

	var s = "";

	
	if (mcsstable.params.beforeAddRow)
	{
		s+=mcsstable.params.beforeAddRow(mcsstable,rowData);
	}
	//aaaa
	var params=mcsstable.params;
	if (!mcsstable.modeldata)
		return;
	var fieldsList=mcsstable.modeldata["fields"];
	//处理分组显示
	if(mcsstable.groupby && rowData){
		if(!(rowData[mcsstable.groupby]===mcsstable.last_td_value))//开始新的一个分组
		{
			if ((rowData[mcsstable.groupby]==null && mcsstable.last_td_value=="") || (rowData[mcsstable.groupby]=="" && mcsstable.last_td_value==null))
			{
			}
			else
			{
				var g=mcsstable_getFieldText(fieldsList,mcsstable.groupby,rowData[mcsstable.groupby],rowData,mcsstable);
				mcsstable.groupname=g;
				if (!rowData[mcsstable.groupby])	
					g="(无分组)";
				s+="<tr class='rowgroup' align='left' groupname='"+g+"' ><td id='"+tableid+"_first' style='font-weight:bold;text-align:left;' class='mcsstable_groupname' colspan='"+(fields.length)+"'><div class='expandgroup' style='float:left'><a href='javascript:void(0)' onclick='mcsstable_slidenogroup(this)'><img src='"+getrooturl()+"/Public/jusaas/mctree/img/jian.gif' alt='收起' style='padding-top:5px;'></a><span class='groupname'>"+g+"</span></div>";
				if (mcsstable.params.addGroupInfo)
				{
					s+=mcsstable.params.addGroupInfo(mcsstable,rowData);
				}	
				s+="</td></tr>";			
				mcsstable.last_td_value= rowData[mcsstable.groupby];
				for(var j = 0;j<fieldsList.length;j++){
					 if(fieldsList[j].count){
						var linename =fieldsList[j].name;
						var lineid =fieldsList[j].id;
						if (rowData[lineid])
						{
							mcsstable_sumvalueByGroup=setArrayValue(mcsstable_sumvalueByGroup,tableid,lineid,parseFloat(rowData[lineid]));
						}
						else
							mcsstable_sumvalueByGroup=setArrayValue(mcsstable_sumvalueByGroup,tableid,lineid,0);
					}
				}
			}			
		}
		else
		{
			 for(var j = 0;j<fieldsList.length;j++){
				  if(fieldsList[j].count){
					var linename =fieldsList[j].name;
					var lineid =fieldsList[j].id;
					if (rowData[lineid])
					{
						var oldv=mcsstable_getArrayValue(mcsstable_sumvalueByGroup,tableid,lineid);
						mcsstable_sumvalueByGroup=setArrayValue(mcsstable_sumvalueByGroup,tableid,lineid,oldv+ parseFloat(rowData[lineid]));
					}
				  }
			  }			
		}

	}
	//本页统计
	for(var j = 0;j<fieldsList.length;j++){
		 if(fieldsList[j].count){
			var lineid =fieldsList[j].id;
			if (i==0)
			{
				mcsstable_sumvalueAll=setArrayValue(mcsstable_sumvalueAll,tableid,lineid,0);
			}
			if (rowData[lineid])
			{
				var oldv=mcsstable_getArrayValue(mcsstable_sumvalueAll,tableid,lineid);
				mcsstable_sumvalueAll=setArrayValue(mcsstable_sumvalueAll,tableid,lineid,oldv+ parseFloat(rowData[lineid]));
			}
		}
	}	
	//以上处理分组
	
	var line="1";
	if(i%2==0)
		line="2";
	if (params.diffLineCss==false)
		line="2";

	var rowid=tableid+"_row_"+randomString();
	mcsstable.lastRowId=rowid;
	var recordid=rowid;
	if (rowData)
		recordid=rowData[mcsstable.keyfield];
		
	s += "<tr onclick='mcsstable_clickRow(this,\""+tableid+"\");' recordid='"+recordid+"' id='"+rowid+"' class='mcsstable_tr"+line+"' ";
	if (params.diffLineCss!=false)
		s+=" onmouseover='mcsstable_row_onmouseover(this)' onmouseout='mcsstable_row_onmouseout(this)'";
	s+=">";
	var fvalue="";
	if (rowData)
		fvalue=	rowData[mcsstable.keyfield];
	if (params.showfirst)//显示第一栏
	{
		s += "<td class='mcsstable_td"+line+"' style='white-space:nowrap;'>";
		if (mcsstable.view!='tableedit') //表格编辑
		{
			if (!params.hidecheckbox) {
				if (params.selectType=="radio") {
					s += "<input type='radio' name='" + params.tableid
						+ "_checkbox' value='" + fvalue + "'/>";
				}
				else{
					s += "<input type='checkbox' name='" + params.tableid
						+ "_checkbox' value='" + fvalue + "' style='border:none' onclick='mcsstable_whencheck(this,\""+tableid+"\")' ";
					//选中已选择的记录
					s+=mcsstable_setSelectedRow(mcsstable,fvalue);
					s +="/> ";
				}
				
			}
		}
		if (!params.showRecordActionAtLast) //打开和编辑按钮
		{
			s += mcsstable_getRecordAction(mcsstable,rowData);
		}
 		
		if (first_td_func && rowData) {
			var td = first_td_func(rowData[mcsstable.keyfield],rowData);
			s += td;
		}
		s += "</td>";
	}
	var direction="";//文本对齐方式
	var fieldtype="";
	var type='';
	var style='';
	for ( var j = 0; j < fields.length; j++) 
	{
		direction="left";
		fieldtype=mcsstable.getFieldInfo(fields[j],"fieldtype");
		type=mcsstable.getFieldInfo(fields[j],"type");
		style=mcsstable.getFieldInfo(fields[j],"style");
		if (type=='recordindex' || type=='dropdown' || type=="date" || type=="radio" || type=="datetime" || type=="checkbox" || type=="checkboxlist" || mcsstable.getFieldInfo(fields[j],"length")<10)
			direction="center";
		else 
		if (fieldtype=='float' || fieldtype=="int" || fieldtype=="real")
			direction="right";
			
		if(style && style.indexOf(':')>-1)
			s += "<td class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' style='text-align:"+direction+";padding:0 8px ;"+style+"'  id='" + fields[j] + "'>";
		else
		{
			s += "<td class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' class='"+style+" 'style='text-align:"+direction+";padding:0 8px ;'  id='" + fields[j] + "'>";
		}	
		if (rowData)
			s+=mcsstable_getFieldDisplay(mcsstable.modeldata.fields,fields[j],rowData[fields[j]],params.special_field_show,rowData,mcsstable) ;
		s+=	"</td>";

	}
	//新的判断是否显示最后一行在代码
	if (mcsstable.ifShowLastTd)
	{
		var td = '';
		if(last_td_func)
		{	
			td = last_td_func(rowData[mcsstable.keyfield],rowData);
		}
		s += "<td class='mcsstable_td"+line+" recordaction' style='white-space:nowrap;width:60px;' id='"+rowid+"' >"+mcsstable_getRecordAction(mcsstable,rowData)+td+"</td>";
	}
	s += "</tr>";
	
	//有分组显示情况下显示字段合计
	if(mcsstable.groupby && mcsstable.hasSummaryField)
	{
		var nextGroup="";
		if (mcsstable.data.length == i+1)
		{
			nextGroup="12vcv23g2432dvdvvcvcv";//这是故意写一个不会重复的名称
		}
		else
			nextGroup=mcsstable.data[i+1][mcsstable.groupby];
		if(mcsstable.last_td_value != nextGroup)
		{			
			s+="<tr align='left' >";
			if (params.showfirst)//显示第一栏
			{
				s += "<td class='mcsstable_td"+line+"' style='white-space:nowrap;'></td>";
			}
			for ( var j = 0; j < fields.length; j++) 
			{
				var sum="";
				if(mcsstable.getFieldInfo(fields[j],"count")){
					var sum=mcsstable_getArrayValue(mcsstable_sumvalueByGroup,tableid,fields[j]);
					if (sum==null)
						sum='0';
					sum="<span title='"+mcsstable.groupname+"_合计'>本组合计:"+sum+"</span>";
				}
				s += "<td class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' class='"+style+" ' style='text-align:right;padding:0 8px ;'  id='" + fields[j] + "'>"+sum+"</td>";
			}
			if (params.showRecordActionAtLast)//显示最后栏
			//if (!params.hideLastTd)
			{
				s+="<td class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' class='"+style+" ' style='text-align:right;padding:0 8px ;'  id='" + fields[j] + "'></td></tr>";
			}
		}
		
	}
	
	//只有最后一行才显示本页合计
	if (mcsstable.data.length == i+1 )
	{
		s+=createPageTotalInfo(mcsstable,params,line,fields,style,fieldsList,tableid);
	}
	if (mcsstable.params.afterAddRow)
	{
		s=mcsstable.params.afterAddRow(mcsstable,s,rowData);
	}
 	return s;
}


function mcsstable_clickRow(e,tableid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	mcsstable.currentRow=e;
}

//判断是否有需要合计的字段
function ifNeedGroupSummary(fields)
{
	for ( var j = 0; j < fields.length; j++) 
	{
		if(fields[j].count)
			return true;
	}
	return false;
}

//生成本页合计信息
function createPageTotalInfo(mcsstable,params,line,fields,style,fieldsList,tableid)
{
	var sumHTML="";
	if (mcsstable.hasSummaryField)
	{
		if (params.showfirst)//显示第一栏
		{
			sumHTML += "<td class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' class='"+style+" ' style='text-align:right;padding:0 8px ;'></td>";
		}
		for ( var j = 0; j < fields.length; j++) 
		{
			var sum="";
			if(mcsstable.getFieldInfo(fields[j],"count")){
				var sum=mcsstable_getArrayValue(mcsstable_sumvalueAll,tableid,fields[j]);
				if (sum==null)
					sum='0';
				sum="本页合计:"+sum;
			}
			sumHTML += "<td class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' class='"+style+" ' style='text-align:right;padding:0 8px ;'  id='" + fields[j] + "'>"+sum+"</td>";			
		}

		if (params.showRecordActionAtLast)//显示最后栏
		{
			sumHTML+="<td class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' class='"+style+" ' style='text-align:right;padding:0 8px ;' ></td>";
		}
	}
	if (sumHTML)
		sumHTML="<tr align='left'>"+sumHTML+"</tr>";
	return sumHTML;

}

function mcsstable_getArrayValue(arr,tableid,field)
{
	for(var i=0;i<arr.length;i++)
	{
		if (arr[i].tableid==tableid && arr[i].field==field) 
		{
			return arr[i].sumvalue;
		}
	}
	return null;
}
function setArrayValue(arr,tableid,field,value)
{
	var found=false;
	for(var i=0;i<arr.length;i++)
	{
		if (arr[i].tableid==tableid && arr[i].field==field) 
		{
			arr[i].sumvalue=value;
			found=true;
		}
	}
	if (!found)
		arr.push({tableid:tableid,field:field,sumvalue:value});
	return arr;
}
function addItemToArray(arr,tableid,field,value)
{
	arr.push({tableid:tableid,field:field,sumvalue:value});
}

//创建记录操作（打开、编辑、删除）的html代码
function mcsstable_getRecordAction(mcsstable,v)
{
	var s="";
	var params=mcsstable.params;
	if(params.toolbar){
		var allactions=","+params.toolbar+",";
		var fvalue="";
		if (v)
			fvalue=v[mcsstable.keyfield];

		var modeldata=mcsstable.modeldata;
		var tableid=mcsstable.tableid;
		if (mcsstable.view!='tableedit') //表格编辑
		{
			if (allactions.indexOf(",open,")>-1) 
			{
				var openurl = modeldata.openurl;
				
				if(openurl && (openurl.indexOf("/")>0))
				{
					s += "<a class='smallbut mcsstable_record_open' style='cursor:pointer;color:blue;' onClick=\"mcsstable_showpopup('"+params.homeUrl
//						+ openurl+"/modelid/"+mcsstable.modelid
						+ openurl
						+ "/"+mcsstable.keyfield+"/"
						+ fvalue
						+ "','"
						+ lang.open
						+ "' ,'"+modeldata.open_window_style+"')\" title="
						+ lang.open + ">" + lang.open + "&nbsp;&nbsp;</a>";
				}else
				{
					s += "<a class='smallbut mcsstable_record_open' style='cursor:pointer;color:blue;' onClick=\"mcsstable_showMcssPopup(this,'"+tableid+"','"+urltmp+"','"+lang.open+"','"+modeldata.open_window_style+"','"+fvalue+"')\" title="
						+ lang.open + ">" + lang.open + "&nbsp;&nbsp;</a>";
				}
			}
			if (allactions.indexOf(",edit,")>-1) 
			{
				var editurl = modeldata.editurl;
				if (editurl=="default"  || !editurl) {
					editurl = g_systempath+"/addRecord/model/"+params.modelid+"/tablename/"+modeldata.tablename;
				}
				var urltmp=params.homeUrl + editurl+"/mcsstableid/"+tableid + "/"+mcsstable.keyfield+"/"+fvalue+"/modelid/"+mcsstable.modelid;
				s += "<a class='smallbut mcsstable_record_edit' id='"+tableid+"_edit' style='cursor:pointer;color:blue;' onClick=\"mcsstable_showMcssPopup(this,'"+tableid+"','"+urltmp+"','"+lang.edit+"','"+modeldata.open_window_style+"','"+fvalue+"')\" title="
							+ lang.edit + ">" + lang.edit + "&nbsp;&nbsp;</a>";
			}
		}
		if (allactions.indexOf(",del,")>-1) {
			s += "<a class='smallbut mcsstable_record_del' style='cursor:pointer;color:blue;' onClick=\"mcsstable_deleterows(this,'"+tableid+"','"+ fvalue+"')\" title='"+lang.del+"'>" + lang.del + "</a>";
		}
		if(params.userAction)
		{
			s = params.userAction(s,fvalue,v,mcsstable);
		}
				
	}
	return s;
}

function mcsstable_showpopup(url,title,open_window_style){
	if (open_window_style=='samewindow')
	{
		document.location.href=url;return;
	}
	else if (open_window_style=='newwindow'){
		window.open(url,"_blank");
	}
	else
	{
		//ShowIframe(url, w, h, t)是popup.js中的方法
		if (open_window_style=='popup:middle' || open_window_style=='popup'){
			url+="/newwinow/true";
			ShowIframe(url, 700,500,title);
			//mcdom.showIframe(url,{title:title,width:'700px',height:'500px'});			
		}
		else if (open_window_style=='popup:large'){
			url+="/newwinow/true";
			ShowIframe(url, 900,700,title);
			//mcdom.showIframe(url,{title:title,width:'900px',height:'700px'});			
		}
		else {
			//mcdom.showIframe(url,{title:title,size:'auto'});
			ShowIframe(url, 700,400,title);

		}
	}
}

function mcsstable_deleterows(e,tableid,recordid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (mcsstable.view=='tableedit')
	{
		var row=$(e.parentNode.parentNode);	
		var recordid=row.attr("recordid");						
		var record={mcss_recordtype:"DELETE",mcss_rowindex:row.attr('rowIndex'),recordid:recordid};
		mcsstable.cellEditor.params.mcssData.deleteRow(record);
		$(row).remove();		
		return;
	}

	var mcsstable=mcsstable_getMCSSTable(tableid);
	var modelid=mcsstable.params.modelid;
	//删除，关
	var arr="";
	if(recordid)
		arr=recordid;
	else
	{
		var arr=mcsstable.selectedRowsID;
		if(arr==''){
			alert(lang.select);
			return;
		}
	}
	if (mcsstable.params.deleteRow)//自定义的删除
	{
		if (!mcsstable.params.deleteRow(arr,mcsstable))
			return;
	}	
	
	
	var alarm = lang.isdelete;
	if(mcsstable.params.confirmword)
		alarm = mcsstable.params.confirmword;
	mcdom.confirm_params={mcsstable:mcsstable,arr:arr};
	mcdom.comfirm(alarm,mcsstable_realDeleteRows);//警告弹出位置判断不准确，在丽兹行里有问题。先不用
	//if (confirm('确定要删除吗？'))
	//{
	//	mcsstable_realDeleteRows(mcdom.confirm_params);
	//}
}

//执行删除记录操作
function mcsstable_realDeleteRows(confirm_params)
{
	var mcsstable=confirm_params.mcsstable;
	var arr=confirm_params.arr;
	mcsstable_deleteRowsNow(mcsstable,arr);
	if (mcsstable.params.afterDeleteRows)//自定义的删除
	{
		if (!mcsstable.params.afterDeleteRows(arr,mcsstable))
			return;
	}	

}
//直接删除指定的记录，不询问
function mcsstable_deleteRowsNow(mcsstable,arr)
{
	var urlpath = mcsstable.params.homeUrl+mcsstable.modeldata.delurl;	
	$.get(
	urlpath,
	{modelid:mcsstable.modelid,rowIds:arr},
	function(data)
	{
		if (data.indexOf("ok")>-1) 
		{
			//mcssTable.currentpage = getCookie(mcsstable.tableid+"_currentpage");
			mcsstable_loaddatarows(mcsstable.tableid);
			//setCookie(mcsstable.tableid+"_currentpage","1");
			if (mcsstable.params.showRecordCount)
				mcsstable_getRecordCount(mcsstable.tableid);				
			$("#"+mcsstable.tableid+"_checkbox_selectall").attr("checked",false);;
			if (mcsstable.params.callbackAfterDeleted)
			{
				mcsstable.params.callbackAfterDeleted(mcsstable,arr);
			}
		}
		else
		{
			alert(data);
		}
	});

}

function mcsstable_createToolbar(tableid){
	if ($("#"+tableid+"_caption").size()>0)
		return;
	var mcsstable=mcsstable_getMCSSTable(tableid);
	var data=mcsstable.modeldata["fields"];
	var params=mcsstable.params;
	var modeldata=mcsstable.modeldata;
	var actions="";
 	
	if (params.toolbar) {
		actions = params.toolbar;
	}
	else
		actions =modeldata.actions;
	
	
	//if (actions==undefined)
	//	actions="all";		
	
	if (!actions)
		actions='';
	
 	if (actions=="all") {
		actions="open,add,edit,tableedit,del,search,search2,filter,filter2,autowidth,groupby,import,export,view";
	}
	params.toolbar=actions;
	mcsstable.actions=actions;
	
	if (actions) { 
		var s = "";
		if (params.showtitle)
		{
			s+="<span id='"+mcsstable.id+"_action_title' class='mcssTable_title'>["+modeldata.title+"]</span>";
		}		
		var tools = params.toolbar+',';
		var actions=tools.split(",");
		var action="";
		for(var i=0;i<actions.length;i++)
		{
			action=actions[i];
			if (action=="add") {
				var tmp=modeldata.addurl;
				if (!tmp || tmp=="default")
					tmp=g_systempath+"/addRecord";
				if (tmp)
					tmp+="/modelid/"+params.modelid;
				var url=params.homeUrl+tmp;
				if (params.defaultValue)
				{
					url+="/defaultValue/"+params.defaultValue;
				}
				
				s += "<a id='"+tableid+"_action_"+action+"'  onclick=\"mcsstable_addnew(this,'"+tableid+"','"+url+"','"+lang.add+"','"+modeldata.open_window_style+"') \" ";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.add);				
			} 
			else if (action=="tableedit" ) {
				s+="<a id='"+tableid+"_action_insert' style='display:none' onclick=\"mcsstable_addnew(this,'"+tableid+"','"+url+"','"+lang.add+"','"+modeldata.open_window_style+"',true) \" ";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.insert,'在当前行前，插入新空行');

				s += "<a id='"+tableid+"_action_tableedit' onclick=\"mcsstable_tableEdit('"+tableid+"',this) \" ";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.edit);
				
				s += "<a id='"+tableid+"_action_save' style='display:none' onclick=\"mcsstable_save('"+tableid+"') \" ";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.save);

				s += "<a id='"+tableid+"_action_cancel' style='display:none' onclick=\"mcsstable_cancel('"+tableid+"',this) \" ";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.returnback,'返回浏览模式，已保存的数据不会丢失。');
				
				
				mcss_importJS("jusaas/js/MCSSInputer.js");	
				mcss_importJS("jusaas/js/MCSSTableEditor.js");	
				
			}
			else if (action=="del" ) {
				if (modeldata.delurl && params.showfirst)
				{
					s += "<a id='"+tableid+"_action_"+action+"'  onclick=\"return mcsstable_deleterows(this,'"+tableid+"')\" ";
					s+=mcsstable_getActionStyle(mcsstable,action,lang.del);
				}
			}
			else if (action=="refresh" ) {
				s += "<a id='"+tableid+"_action_"+action+"'  onclick=\"return mcsstable_refresh('"+tableid+"')\" ";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.refresh);
			}
			else if (action=='searchnow') {
			}
			else if (action=='search') {
				s += "<input type='text' class='searchInput' placeholder='"+lang.inputtosearch+"' x-webkit-speech id='" + params.tableid
						+ "_action_searchtext' title='"+lang.search_title+"'  onkeydown='mcsstable_onsearchkeyup(event,\""+params.tableid+"\")' onchange='mcsstable_onsearchchange(\""+params.tableid+"\") ' />";
				//title='例1：北京：表示搜索包含“北京”字眼的记录\r\n例2：北京*公司：表示搜索同时包含“北京”和“公司”字眼的记录\r\n例3：北京*公司 网站：表示搜索同时包含“北京”和“公司”字眼，或者包含“网站”字眼的记录'
			//	if (tools.indexOf('searchnow')>-1)
				//	s+="<input type='CheckBox'  id='"+params.tableid+"_searchnow' 	title='即时搜索'>";
				//s+="<a onclick='mcsstable_search(\""+params.tableid+"\");' ";
				//s+=mcsstable_getActionStyle(mcsstable,action,lang.search);
			}
			else if (action=='filter') {
				s += mcsstable.ceateFilter();
			}
			else if (action=='filter2') {
				mcss_importJS("jusaas/js/mcsstable_filterbar.js");	
				s += "<a id='"+tableid+"_action_"+action+"' onclick='mcsstable_createFilterBar(\""+params.tableid+"\")' ";
				s+=mcsstable_getActionStyle(mcsstable,action,'高级过滤器');
			}
			else if (action=='search2') {
				var has_action_search2=true;
				mcss_importJS("jusaas/js/mcsstable_advancesearch.js");
				//s += "<a id='" + params.tableid	+ "_action_"+action+"' onclick='mcsstable_search2(\""+params.tableid+"\");' title='"+lang.search2+"' ";
				//s+=mcsstable_getActionStyle(mcsstable,action,lang.search2);
				var select_id=params.tableid+ "_action_historysearch";
				s+="<select style='width:100px' id='"+select_id+"' onchange=\"mcsstable_openQuery('"+select_id+"','"+params.tableid+"')\"><option>--</option></select>";
				//如果有高级搜索按钮，则加载高级搜索js代码
			}
			else if (action=='autowidth') {
				s += "<a id='" + params.tableid + "_action_"+action+"' onclick='mcsstable_autowidth(\""+params.tableid+"\")'";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.adjust_width,lang.adjust_width+":"+lang.nofoldlines+"/"+lang.compact);
			}
			else if (action=='export') {
				s += "<a id='" + params.tableid	+ "_action_"+action+"'  onclick='mcsstable_displaymenu(this,\""+params.tableid+"\")'";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.exporttofile,lang.ordertofile_title);
			}
			else if (action=='print') {
				s += "<a id='" + params.tableid	+ "_action_"+action+"' onClick='mcsstable_print(\""+params.tableid+"\")' ";
				s+=mcsstable_getActionStyle(mcsstable,action,'打印','打印');
			}
			else if (action=='import') {
				s += "<a id='" + params.tableid+ "_action_"+action+"' onClick='mcsstable_importtosql(\""+params.tableid+"\")' ";
				s+=mcsstable_getActionStyle(mcsstable,action,lang.mcssimport,lang.mcssimport_title);
			}
			else if (action=='groupby') {
				s +="<select id='"+params.tableid+"_action_"+action+"' title='"+lang.viewbygroup+"' onchange='mcsstable_setGroup(\""+params.tableid+"\")'>";
				s += "<option value='' selected='true'>--"+lang.defaultgroup+"--</option>";
				$.each(data,function(i, v) {
					if (v.isvisible != "false") {
						if(lang[v.name])
							v.name=lang[v.name];
						s += "<option value='" + v.id + "'>" + v.name + "</option>";
					}
				});
				s += "<option value='NONEGROUP'>--"+lang.nonegroup+"--</option>";
				s +="</select><a href='javascript:mcsstable_setOrder(\""+params.tableid+"\")' id='"+params.tableid+"_action_sort'";
				s+=mcsstable_getActionStyle(mcsstable,'asc',lang.orderby,lang.orderbynotes);
				
			}
			else if (action=='page' ) {
				if (mcsstable.params.pageposition!='rightdown')
					s += mcsstable_page(params.tableid);
			}
			else if (action=='view') {
				//日历展示原来正常现在有问题
				//var temp="<select onChange='mcsstable_setView(\""+params.tableid+"\",this)'><option value='table'>表格</option><option value='calendar'>日历</option></select>"
				s += "<a id='" + params.tableid+ "_action_"+action+"' onclick='mcsstable_swithView(\""+params.tableid+"\")' ";
				s+=mcsstable_getActionStyle(mcsstable,action,'视图','切换表格视图与日历视图');

				mcss_importJS("jusaas/calendar/McssCalendar.js");
				mcsstable.canChangeView=true;
			}
			else
			{
				if (action && action!='open' && action!='edit')
				{
					s += "<a id='" + params.tableid+ "_action_"+action+"' onClick='mcsstable_exeAction(\""+action+"\",\""+mcsstable.tableid+"\")'";
					s+=mcsstable_getActionStyle(mcsstable,action,action);
				}
			}
		}
		if (mcsstable.view=="calendar")
			mcss_importJS("jusaas/calendar/McssCalendar.js");
			
		$("#" + params.tableid).append("<caption><div  id='"+params.tableid+"_caption' class='mcsstable_caption'>" + s + "</div></caption>");
		$("#" +tableid+"_pagerows").val(mcsstable.modeldata.pagerows);
		
		if (params.showtitle)
		{

		}		
	}
	if (mcsstable.params.pageposition=='rightdown')
	{
		s = mcsstable_page(params.tableid);
		//var length=document.getElementById(params.tableid).rows[0].cells.length+1;
		
		$("#" + params.tableid).after(s);
		$("#" +tableid+"_pagerows").val(mcsstable.modeldata.pagerows);	
	}
	
	if (has_action_search2)
	mcsstable_getHistorySearch(params.tableid+"_action_historysearch",mcsstable.modelid);//
	
	//创建页脚按钮
	//if (mcsstable.params.popupFormID || mcsstable.params.showConfirmButton){
	if (mcsstable.params.showConfirmButton){
		var h="<a class='setSubmit2 mcssingbutton big_btn big_btn-green' href='javascript:void(0)' id='"+tableid+"_confirm' onclick='returnSelectedRows(\""+tableid+"\")'>确定所选</a>";
		$("#"+params.tableid).after(h);
	}
	
	
}

//获取保存过的查询名称
function mcsstable_getHistorySearch(select_id,modelid)
{
	var h="<option value='' >--"+lang.search2+"--</option>";
	h+="<option value='DIYFILTER' >"+lang.diy+"...</option>";
	
	$("#"+select_id).html(h);
	$.post(g_homeurl+"/Sys/System/getSeachValue",{'modelid':modelid},function(data)
	{
		h="";
		var arr = data.split(',');
		for(i=0;i<arr.length;i++)
			h+="<option value='"+arr[i]+"'>"+arr[i]+"</option>";
		$("#"+select_id).append(h);
	});

}

//打开某个历史查询
function mcsstable_openQuery(select_id,tableid)
{
	if ($("#"+select_id).find("option:selected").attr('tag')!='USEROPTION')
	{
	var title=$("#"+select_id).val();
	if (title=='DIYFILTER')
	{
		mcsstable_search2(tableid);
	}
	else		
	$.post(g_homeurl+"/Sys/System/getSeachquery",{'title':title},function(data){
		var mcsstable=mcsstable_getMCSSTable(tableid);
		mcsstable.searchword =data.replace(/\'/g,"<yh>");
		mcsstable_loaddatarows(tableid);
	});
	}
}


//显示菜单
function mcsstable_displaymenu(e,tableid){
	var h = "<div id='mcsstable_actionmenu' class='popupmenu'>"
		+"<a  onclick='mcsstable_exporttoexcel(\""+tableid+"\",\"csv\")'  title='.csv' class='popupmenu_csv'>.csv file</a>"
		+"<a  onclick='mcsstable_exporttoexcel(\""+tableid+"\",\"xls\")' title='.xls' class='popupmenu_xls'>.xls file</a>"
		+"<a  onclick='mcsstable_exporttoexcel(\""+tableid+"\",\"xlsx\")'  title='.xlsx' class='popupmenu_xlsx'>.xlsx file</a></div>";	 
	$(e).after(h);
	//当点击不是导出按钮的时候把下拉菜单移除
	$(document).click(function(event){
		if (event.target.id!=e.id)
			$(".popupmenu").remove();
	})
	var div = $("#mcsstable_actionmenu"); //要浮动在这个元素旁边的层  
	dom_showPopupMenu(div,e); 
}

function mcsstable_removeActionMenu()
{	
}
function mcsstable_getActionStyle(mcsstable,action,text,title)
{
	var s="";
	if (!title)
		title=text;
	if (mcsstable.params.actionStyle=='ICON')
	{
		s+=" class='actionicons "+action +"' title='"+title+"'>";
	}
	else
	{
		s+=" class='labellink text_"+action+"_btn' title='"+title+"'>";
		s+=text;
	}
	s+="</a> ";
	return s;
}

//字段合计值处理
var mcsstable_sumvalueByGroup=new Array();
var mcsstable_sumvalueAll=new Array();
//mcsstable_sumvalueByGroup.push({fieldid:'amt',sum:5000});

//为分组变量赋值
function mcsstable_setGroup(tableid){
	var table=mcsstable_getMCSSTable(tableid);
	table.groupField=$("#"+tableid+"_action_groupby").val();
	
	if ($("#"+tableid+"_expandAllGroup").size()==0)
	{
		$("#"+tableid).find(".mcsstable_th_first").append(table.getShowHideGroupBtn(tableid));
	}
	mcsstable_loaddatarows(tableid);
}

//修改分组排序方向，倒序还是正序
function mcsstable_setOrder(tableid){
	var table=mcsstable_getMCSSTable(tableid);
	if(table.groupField){
		if(table.order_updown=='asc' || !table.order_updown){
			table.order_updown='desc';
		}	
		else{
			table.order_updown='asc';
		}	
		mcsstable_loaddatarows(tableid);
	}
	else
		alert(lang.pleaseselectfield);
}

//生成过滤器
MCSSTable.prototype.ceateFilter=function()
{
	var s="";
	var fieldData=this.modeldata.fields;
	s+="<select id='"+this.tableid+"_select' onChange='mcsstable_changeOption(this,\""+this.tableid+"\")' class='mcsstable_select'>";
	
	s+="<option value='ALL'>--"+lang.filterbytype+"--</option>";
	var options="";
	for(var i=0;i<fieldData.length;i++)
	{
		if (fieldData[i].type=="dropdown" || fieldData[i].type=="checkbox" || fieldData[i].type=="checkboxlist" || fieldData[i].type=="radio")
		{
			var dom=new MCDom();
			if (lang[fieldData[i].name])
				options+="<optgroup label='"+lang[fieldData[i].name]+"'>";
			else
				options+="<optgroup label='"+fieldData[i].name+"'>";
			if (fieldData[i].type=="checkbox")
			{
				options+="<option value='"+fieldData[i].id+":True'>"+lang.yes+"</option><option value='"+fieldData[i].id+":False'>"+lang.no+"</option>";
			}
			else	
				options+=dom.createOptions(fieldData[i].data,fieldData[i].id);
			options+="</optgroup>";
			delete dom;
			 
		}
	}
	s="<div>"+s+options+"</select></div>";
	return s;
}

//根据字段id获得其在表格中的顺序
MCSSTable.prototype.getFieldIndex=function(fieldId)
{
	var cells=this.table.rows[0].cells;
	for(var i=0;i<cells.length;i++)
	{
		if ($(cells[i]).attr('fieldid')==fieldId)
		{
			return i;
		}
	}
	return -1;
}

MCSSTable.prototype.getRow=function(recordid)
{
	var rows=$("#"+this.tableid).get(0).rows;
	for(var i=0;i<rows.length;i++)
	{
		if ($(rows[i]).attr('recordid')==recordid)
		{
			return rows[i];
		}
	}
	return null;
}

//下列列表过滤
function mcsstable_changeOption(obj,tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	var filter="";
	if (obj.value=="ALL")
		filter="";
	else
	{
		var arr=obj.value.split(":");
		filter=arr[0]+"='"+arr[1]+"'";
	}
	if (table.params.filter)
	{
		table.filter ="("+table.params.filter+")";
		if (filter)
			table.filter +=" and ("+filter+")";
	}
	else
		table.filter =filter;
	mcsstable_loaddatarows(table.tableid); 
}
 
function mcsstable_page(tableid){
var s="";
var mcss_table = mcsstable_getMCSSTable(tableid);
var defaultPage = 10;
var pagerow = mcss_table.modeldata.pagerows;
if(pagerow && pagerow>=-1)
	defaultPage = pagerow;
var pagerows = [5,10,20,50,100,200,500,-1];
s+="<a id='"+tableid+"_action_firstpage' onclick='mcsstable_firstpage(this,\""+tableid+"\");' ";
s+=mcsstable_getActionStyle(mcss_table,'firstpage',lang.firstpage);

s+="<a id='"+tableid+"_action_prepage'  onclick='mcsstable_prepage(this,\""+tableid+"\")' ";
s+=mcsstable_getActionStyle(mcss_table,'prepage',lang.priorpage);
s+="<input id='"+tableid+"_action_currentpage' onkeyUp='mcsstable_gotoPagex("+'"'+tableid+'"'+",this.value,event)' title='请输入要跳转的页数，敲回车跳转' class='Arial inputpage' value='1' style='line-height:18px;'/>";
if (mcss_table.params.showRecordCount)
{
	s+="<span>/</span>";
	s+="<span id='"+tableid+"_action_totalpage' style='margin-right:2px;' class='Arial' title='总页数'>1</span>";
}

s+="<a id='"+tableid+"_action_nextpage'  onclick='mcsstable_nextpage(this,\""+tableid+"\")' ";
s+=mcsstable_getActionStyle(mcss_table,'nextpage',lang.nextpage);

s+="<a id='"+tableid+"_action_lastpage'  onclick='mcsstable_lastpage(this,\""+tableid+"\")' ";
s+=mcsstable_getActionStyle(mcss_table,'lastpage',lang.lastpage);

s+="<a  id='"+tableid+"_action_RecordCount' ";
if (!mcss_table.params.showRecordCount)
{
	s+=" onClick='mcsstable_getTotalRows(this,\""+tableid+"\");' ";
}
else
	s+=" style='cursor:'";
s+=mcsstable_getActionStyle(mcss_table,'ipage',lang.totalrecord);

s+="<select id='"+tableid+"_action_pagerows' onChange='mcsstable_changepagerows(this,\""+tableid+"\")' title='每页记录数' > "; 
for(var i =0;i<pagerows.length;i++){
	if(pagerows[i]==defaultPage)
		s+="<option value='"+pagerows[i]+"' selected='true'>"+pagerows[i]+"</option> ";
	else if(pagerows[i]==-1)
		s+="";
	else
		s+="<option value='"+pagerows[i]+"'>"+pagerows[i]+"</option> ";
}
s+="</select>";
var s1="<div id='"+tableid+"_action_pagebar'";
if (mcss_table.params.pageposition=="rightdown")
	s1+=" class='TableFenye'";
s1+=" style='float:right'>" + s + "</div>";
return s1;
}

function mcsstable_firstpage(e,tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	if (table.currentpage==1 )
		mcdom.alert(lang.firstpages,lang.hint,'info','fadeout');
	else
	{
		mcsstable_gotoFirstPage(tableid);
	
	}
	//setCookie(tableid+'_currentpage',table.currentpage);
}

function gotofirstpage(tableid)
{
	mcsstable_gotoFirstPage(tableid);
}
function mcsstable_gotoFirstPage(tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	table.currentpage=1;
	$("#"+tableid+"_action_currentpage").attr("value",table.currentpage);
	mcsstable_loaddatarows(tableid);
}

function mcsstable_lastpage(e,tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	if ($("#"+tableid+" tr:not(:first)").length<table.pagerows ){
		mcdom.showPopup(e,"<br /><br />&nbsp;&nbsp;"+lang.lastpages+"!<br /><br /><br />",'middle',null,null,200,300,lang.hint,{fadeOut:true});
		setCookie(tableid+'_currentpage',table.currentpage);
	}else{
		var urlpath=table.params.homeUrl+ g_systempath+"/getTotalRows";	
		var sql = table.params.sql;
		$.get(urlpath,{sql:sql,modelid:table.modelid,filter:table.filter,sosoword:table.searchword}, function(data){
			data=getUTF8Str(data);
			var total =parseInt(data)/table.pagerows;

			if (total > parseInt(total))
				total =parseInt(total)+1;
			table.currentpage = total;
			$("#"+tableid+"_action_currentpage").attr("value",table.currentpage);
			setCookie(tableid+'_currentpage',table.currentpage);
			mcsstable_loaddatarows(tableid);
		});	
	}
}

function mcsstable_prepage(e,tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	if (table.currentpage==1 )
		mcdom.alert(lang.firstpages,lang.hint,'info','fadeout');
	else
	{
		$("#"+tableid+"_checkbox_selectall").attr("checked",false);
		table.currentpage= table.currentpage - 1;
		$("#"+tableid+"_action_currentpage").attr("value",table.currentpage);
		mcsstable_loaddatarows(tableid);
	}
	setCookie(tableid+'_action_currentpage',table.currentpage);
}

function mcsstable_nextpage(e,tableid)
{
	var table=mcsstable_getMCSSTable(tableid);	
	if ($("#"+tableid+" tr:not(:first)").length<table.pagerows ){
		mcdom.showPopup(e,"<br /><br />&nbsp;&nbsp;"+lang.lastpages+"!<br /><br /><br />",'middle',null,null,200,300,lang.hint,{fadeOut:true});
	}
	else
	{
		if (table.params.showRecordCount && table.recordCount <= table.currentpage * table.pagerows)
		{
			mcdom.showPopup(e,'',null,null,null,200,200,lang.lastpages,{fadeOut:true});
			return;
			
		}
		
		$("#"+tableid+"_checkbox_selectall").attr("checked",false);
		table.currentpage= parseInt(table.currentpage) + 1;
		$("#"+tableid+"_action_currentpage").attr("value",table.currentpage);
		setCookie(tableid+'_action_currentpage',table.currentpage);
		mcsstable_loaddatarows(tableid);
	}
}

function mcsstable_changepagerows(select,tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	table.pagerows = select.value;
	table.currentpage = 1;
	$("#"+tableid+"_action_currentpage").val(1);
	mcsstable_loaddatarows(tableid);
}

//获得记录总数表显示出来
function mcsstable_getRecordCount(tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	var urlpath=table.params.homeUrl+ g_systempath+"/getTotalRows";	
	var sql = table.params.sql;
	var filter=table.filter;
	if(filter)
		filter = filter.replace(/\'/g, "<yh>");
	$.get(urlpath,{sql:sql,modelid:table.modelid,filter:filter,sosoword:table.searchword}, function(data){
		if (!data)
		{}
			//$("#"+tableid+"_RecordCount").html(lang.totalrecords+":0");
		else
		{
			table.recordCount=parseInt(data);
			var total =parseInt(data)/table.pagerows;
			if (total > parseInt(total))
				total =parseInt(total)+1;		
			$("#"+tableid+"_action_RecordCount").attr("title",lang.totalrecords+":"+data+","+lang.totalpages+":"+total);
			$("#"+tableid+"_action_totalpage").html(total);
			if (!total)
			{
				$("#"+tableid+"_action_currentpage").attr("value",'0');
				$("#"+tableid+"_action_totalpage").html('0');
			}
			return data;
			
		}
	});
}

function mcsstable_getTotalRows(e,tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	var urlpath=table.params.homeUrl+ g_systempath+"/getTotalRows";	
	var sql = table.params.sql;
	$.get(urlpath,{sql:sql,modelid:table.modelid,filter:table.filter,sosoword:table.searchword}, function(data){
		if (!data)
			ShowAlert(lang.hint,'无法获得记录数，可能是因为计算记录时遇到错误！');
		else
		{
			var total =parseInt(data)/table.pagerows;
			if (total > parseInt(total))
				total =parseInt(total)+1;
			if (table.params.showRecordCount)
			{
				$("#"+tableid+"_action_RecordCount").attr("title",lang.totalrecords+":"+data+","+lang.totalpages+":"+total);
				$("#"+tableid+"_action_totalpage").html(total);
				if (!total)
				{
					$("#"+tableid+"_action_currentpage").attr("value",'0');
					$("#"+tableid+"_action_totalpage").html('0');
				}					
			}
			mcdom.showPopup(e,"<br /><br />&nbsp;&nbsp;"+lang.totalrecords+":"+data+","+lang.totalpages+":"+total+"<br /><br /><br />",'middle',null,null,200,300,lang.hint,{fadeOut:false});				
		}
	});
}
function mcsstable_getFieldDisplay(fieldData,field,value,special_field_show,record,mcsstable)
{
	var r=value;
	if (special_field_show) 
	{
		r=mcsstable_special_field_show(fieldData,field,special_field_show,value,record,mcsstable);
	
	} else
		r=mcsstable_getFieldText(fieldData,field,value,record,mcsstable);

	return r;
}
function mcsstable_special_field_show(fieldData,field,special_field_show,fieldvalue,record,mcsstable)
{
	r=fieldvalue;
	var func=getJSONValue(special_field_show,field);
	if (func)
	{
		record.mcsstable=mcsstable;
		r=func(fieldvalue,record);
	}
	else
		r=mcsstable_getFieldText(fieldData,field,fieldvalue,record,mcsstable);
	return r;
}

//获得字段定义信息,例如，获得name字段的fieldtype
MCSSTable.prototype.getFieldInfo=function(fieldid,info){
	var fieldData=this.modeldata.fields;
	for(var i=0;i<fieldData.length;i++)
	{
		if (fieldData[i].id==fieldid)
		{
			return fieldData[i][info];
		}
	}
	return null;
}

//点击放大图片
function getEvent(){
	if(document.all){
	return window.event;//如果是ie
	}
	func=getEvent.caller;
	while(func!=null){
		var arg0=func.arguments[0];
		if(arg0){
			if((arg0.constructor==Event || arg0.constructor ==MouseEvent)||(typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation)){
			return arg0;
			}
		}
		func=func.caller;
	}
	return null;
}
//点击放大图片
function ImgShow(evt){
	viewImage(evt);//这个放大定义文件是viewImage.js
}

//获得字段值的显示字符串。因为有些字段值与显示不一样。
function mcsstable_getFieldText(fieldData,field,value,record,mcsstable)
{
	var r=value;
	if (!r)
		r='';
	var rootrurl=getrooturl();
	var openRecord='';
	for(var i=0;i<fieldData.length;i++)
	{
		if (fieldData[i].id==field)
		{
			if (fieldData[i].type=='checkbox')
			{
					if (value=="1")
						r="true";
					else if (value=="0")
						r="false";
					break;
			}
			else if (fieldData[i].type=='hidden' || fieldData[i].type=='password')
			{
				if (r!="")
					r="***";
				break;
			}			
			else if (value && (fieldData[i].type=='date' || fieldData[i].type=='datetime' || fieldData[i].type=='SYS_ADDTIME' || fieldData[i].type=='SYS_EDITTIME' ))
			{
				if (value=="0000-00-00" || value=="0000-00-00 00:00:00")
					r="";
				else{
					r=value;
					if (fieldData[i].type=='date' && value.length>10)
						r=r.substr(0,10);	
					var d=new Date();
					var d1=getGoodDate(d);
					if (r.indexOf(d1)==0){
						if (fieldData[i].type=='date')
							r=r.replace(d1,lang.today);
						else
							r=r.replace(d1,'');
					}
				}
				break;
			}			
			else if (fieldData[i].type=='checkboxlist')
			{   
				var enum_arr = fieldData[i].data.split(",");
				if (value)
				{
					var value_arr=value.split(",");	
					
					r="";
					for(var n=0;n<value_arr.length;n++)
					{   
						for(var k=0;k<enum_arr.length;k++)
						{	
							var one_arr=enum_arr[k].split(":");
							if (one_arr[0]==value_arr[n] ||one_arr[1]==value_arr[n])
							{
								var v1=one_arr[0];
								if (one_arr.length==2)
									v1=one_arr[1];
								if (r=="")	
									r=v1;
								else
									r=r+","+v1;
								  if (lang[r]) r=lang[r];
									
								break;
							}
						}
					}
				}
			}
			
			else if (fieldData[i].type=='radio')
			{   
				var enum_arr = fieldData[i].data.split(",");
				if (value != null)
				{
					var value_arr=value.split(",");	
					
					r="";
					for(var n=0;n<value_arr.length;n++)
					{   
						for(var k=0;k<enum_arr.length;k++)
						{	
							var one_arr=enum_arr[k].split(":");
							if (one_arr[0]==value_arr[n]||one_arr[1]==value_arr[n])
							{  
								var r=one_arr[0];
								if (one_arr.length==2)
								{
								  r=one_arr[1];
								  if (lang[r]) r=lang[r];
								}																	
							}
						}
					}
				}
			}
			else if (fieldData[i].type=='image')
			{
				var x='60';
				var y='60';
				var imagepath =rootrurl+"/Public/uploadfile/"; //图片路径路径
				if(value)
				{
					//解析文件显示名与存储名
					var filename=value;
					var savename=value;
					var arr=value.split('~');
					if (arr.length>1)
					{
						filename=arr[0];
						savename=arr[1];
					}
				
					if(fieldData[i].width && fieldData[i].height)
					{
						r="<img src='"+imagepath+savename+"' width='"+fieldData[i].width+"' height='"+fieldData[i].height+"' onclick='ImgShow(this)'/>";
					}else if(fieldData[i].width)
					{
						r="<img src='"+imagepath+savename+"' width='"+fieldData[i].width+"' height='"+y+"' onclick='ImgShow(this)'/>";
					}else if(fieldData[i].height)
					{
						r="<img src='"+imagepath+savename+"' width='"+x+"' height='"+fieldData[i].height+"' onclick='ImgShow(this)'/>";
					}else
					{
						r="<img src='"+imagepath+savename+"' width='"+x+"' height='"+y+"' onclick='ImgShow(this)'/>";
					}
				}
			}
			else if (fieldData[i].type=='file')
			{
				var imagepath =rootrurl+"/Public/uploadfile/"; 
				if(value)
				{
					var filename=value;
					var savename=value;
					var arr=value.split('~');
					if (arr.length>1)
					{
						filename=arr[0];
						savename=arr[1];
					}
					r="<a target='_blank' href='"+imagepath+savename+"' >"+filename+"</a>";
				}
			}
			else if (fieldData[i].type=='dropdown' || !fieldData[i].type || fieldData[i].type=='smartselect')
			{   
				if(fieldData[i].data!=null)
				{
				     var enum_arr = fieldData[i].data.split(",");

					if (value !=null)
					{
						var value_arr=value.split(",");	
						r="";
						for(var n=0;n<value_arr.length;n++)
						{   
							for(var k=0;k<enum_arr.length;k++)
							{	
								var one_arr=enum_arr[k].split(":");
								if (one_arr[0]==value_arr[n] ||one_arr[1]==value_arr[n])
								{
									var v1=one_arr[0];
									if (one_arr.length==2)
										v1=one_arr[1];
									r=v1;
									  if (lang[r]) r=lang[r];
									
									break;
								}
							}
						}
					}
				}
				
			}
			if (fieldData[i].prop && (fieldData[i].prop).indexOf("OPENRECORD")!=-1)
			{
				openRecord=true;
			}
		}		
	}
	if (openRecord)
	{
		var modeldata=mcsstable.modeldata;
		var tableid=mcsstable.tableid;
		var openurl = modeldata.openurl;
		if(!openurl)
			openurl=g_systempath+"/viewRecord";
		r= "<a style='cursor:pointer;' onClick=\"mcsstable_showpopup('"+mcsstable.params.homeUrl
			+ openurl //+"/modelid/"+mcsstable.modelid
			+ "/"+mcsstable.keyfield+"/"
			+ record[mcsstable.keyfield]
			+ "','"
			+ r
			+ "' ,'"+modeldata.open_window_style+"')\""
			+ " title='"+ lang.open_title + "'>" + r + "</a>";
	}
	return r;
}
//String.prototype.trim = function() {    var str = this,    str = str.replace(/^\s\s*/, '')

//使用某个视图显示数据
function mcsstable_setView(tableid){
	var mcsstable=mcsstable_getMCSSTable(tableid);		
	if (mcsstable.view=="calendar")
	{
		if ($("#"+tableid+"_view_calendar").size()==0)
		{
			$("#"+tableid).after("<div id='"+tableid+"_view_calendar"+"'>日历</div>");
			mcsstable_loadCalendarRows(mcsstable);
		}
		
		$("table[id='"+tableid+"'] tr").hide();
		$("#"+tableid+"_view_calendar").show();		
		mcsstable.showAction("tableedit,del",false);
	}
	else
	if (mcsstable.view=="kapian")
	{	
		$("table[id='"+tableid+"'] tr").hide();
		$("#"+tableid+"_view_calendar").hide();
		mcsstable_loadKapian(mcsstable);
		mcsstable.showAction("tableedit,del",false);
	}
	else
	if (mcsstable.view=="table" || mcsstable.view=="")
	{	
		$("table[id='"+tableid+"'] tr").show();
		$("#"+tableid+"_view_calendar").hide();
		$("#"+tableid+"_view_kapian").hide();
		mcsstable.showAction("tableedit,del",true);
	}
	
}

//切换视图
function mcsstable_swithView(tableid){
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (!mcsstable.view || mcsstable.view=="table"|| mcsstable.view=="tableedit")
	{
		mcsstable.view="calendar";
	}
	else
	if (mcsstable.view=="calendar")
	{	
		mcsstable.view="kapian";	
	}
	else
	if (mcsstable.view=="kapian")
	{	
		mcsstable.view="table";	
	}
	mcsstable_setView(tableid);
}

//用卡片方式显示表的内容
function mcsstable_loadKapian(mcsstable)
{
	var html="";
	var fields=mcsstable.fields;
	var direction="";//文本对齐方式
	var fieldtype="";
	var type='';
	var style='';
	var rowData;
	var line="1";
	var params=mcsstable.params;
	var last_td_func=mcsstable.last_td_func;
	for(var i=0;i<mcsstable.data.length;i++)
	{
		rowData=mcsstable.data[i];

		
		html+="<br /><table>";
	for ( var j = 0; j < fields.length; j++) 
	{
		html+="<tr>";
		direction="left";
		fieldtype=mcsstable.getFieldInfo(fields[j],"fieldtype");
		type=mcsstable.getFieldInfo(fields[j],"type");
		style=mcsstable.getFieldInfo(fields[j],"style");
		if (type=='recordindex' || type=='dropdown' || type=="date" || type=="radio" || type=="datetime" || type=="checkbox" || type=="checkboxlist" || mcsstable.getFieldInfo(fields[j],"length")<10)
			direction="center";
		else 
		if (fieldtype=='float' || fieldtype=="int" || fieldtype=="real")
			direction="right";
			
		html+="<td style='text-align:right;width:100px;'>"+mcsstable.getFieldInfo(fields[j],"name")+":</td><td>";
		if(style && style.indexOf(':')>-1)
			html += "<span class='mcsstable_td1"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' style='padding:0 8px ;"+style+"'  id='" + fields[j] + "'>";
		else
		{
			html += "<span class='mcsstable_td"+line+" "+ mcsstable.tableid+"_"+fields[j]+"' class='"+style+" 'style='padding:0 8px ;'  id='" + fields[j] + "'>";
		}	
		if (rowData)
			html+=mcsstable_getFieldDisplay(mcsstable.modeldata.fields,fields[j],rowData[fields[j]],params.special_field_show,rowData,mcsstable) ;
		html+=	"</span></td></tr>";




	}
	if (
	((params.showRecordActionAtLast || last_td_func) && !params.hideLastTd 
	&& (params.toolbar.indexOf('open') > -1 || params.toolbar.indexOf('edit') > -1 || params.toolbar.indexOf('del') > -1)) 
	) //打开和编辑按钮
	{
		var td = '';
		if(last_td_func)
		{	
			
			td = last_td_func(rowData[mcsstable.keyfield],rowData);
		}
		html += "<tr><td class='mcsstable_td"+line+" recordaction' style='white-space:nowrap;width:60px;'>"+mcsstable_getRecordAction(mcsstable,rowData)+td+"</td></tr>";
	}
	if (last_td_func && !params.hideLastTd && !(params.style=="simple") && (params.toolbar.indexOf('open') == -1 && params.toolbar.indexOf('edit') == -1 && params.toolbar.indexOf('del') == -1)) 
	{
	
		var td = last_td_func(rowData[mcsstable.keyfield],rowData);
		html += "<tr><td class='mcsstable_td"+line+"' style='white-space:nowrap;'>" + td + "</td></tr>";
	}

		
		html+="</table>";
	}
	if ($("#"+mcsstable.tableid+"_view_kapian").size()==0)
		$("#"+mcsstable.tableid).after("<div id='"+mcsstable.tableid+"_view_kapian'></div>");	
	$("#"+mcsstable.tableid+"_view_kapian").html(html).show();	

}
//用日历方式显示表的内容
function mcsstable_loadCalendarRows(mctable)
{
	var fields=mctable.modeldata.fields;
	mctable.titleField=mctable.modeldata.calendar_title_field;
	mctable.timeField=mctable.modeldata.calendar_date_field;
	if (!mctable.titleField || !mctable.timeField)
	{	
		for(var i=0;i<fields.length;i++)
		{
			if (!mctable.titleField)
			{
				if (fields[i].id=="name" || fields[i].id=="title" || fields[i].id=="subtitle" || fields[i].fieldtype=="string" || fields[i].type=="text")
				{
					mctable.titleField=fields[i].id;
				}
			}
			if (!mctable.timeField)
			{
				if (fields[i].isvisible!="false" && (fields[i].type=="date" || fields[i].type=="datetime" || fields[i].fieldtype=="date" || fields[i].fieldtype=="datetime") )
				{
					mctable.timeField=fields[i].id;
				}
			}
		}
	}
	if (!mctable.titleField)
	{
		alert("无法按日历方式查看，因为缺少标题字段。");
		return;
	}
	if (!mctable.timeField)
	{
		alert("无法按日历方式查看，因为缺少日期字段。");
		return;
	}
	var data=[];
	for(var i=0;i<mctable.data.length;i++)
	{
		if (mctable.data[i][mctable.timeField])
			data.push({date:mctable.data[i][mctable.timeField],work:mctable.data[i][mctable.titleField],id:mctable.data[i][mctable.keyfield]});
	}
	//var data=[{date:'2012-06-19',work:'我今天的工作1'},{date:'2012-06-19',work:'我今天的工作2'}];

	this.calendar=new McssCalendar({id:mctable.tableid+"_view_calendar",data:data,mostRows:20,mostWords:20,onShowDayData:canlendar_showData,calendartype:'monthweek',refObject:mctable,showCalendartype:true});
	this.calendar.run();
	
}

function canlendar_showData(calendar,date,day,refObject)
{	
	var h="<div><span>"+day+"</span><span style='text-align:right;'></span></div>";
	var taskCount=0;
	var data=calendar.params.data;
	var mostRows=10;
	if (calendar.params.mostRows)
		mostRows=calendar.params.mostRows;
	var mostWords=20;
	if (calendar.params.mostWords)
		mostWords=calendar.params.mostWords;	
	var mcsstable=refObject;	
	
	for(var i=0;i<data.length;i++)
	{
		if (data[i].date==date || data[i].date.length>10 && data[i].date.substr(0,10)==date)
		{
			if (taskCount<mostRows)
			{
				var temp=data[i].work;
				if (mostWords && data[i].work.length>mostWords)
				{
					temp=data[i].work.substr(0,mostWords)+"..";
				}
				
				temp="<span style='cursor:pointer;color:blue;' onClick=\"mcsstable_showpopup('"+mcsstable.params.homeUrl
				+ mcsstable.modeldata.openurl
				+ "/"+mcsstable.keyfield+"/"
				+ data[i][mcsstable.keyfield]
				+ "','"
				+ lang.open
				+ "' ,'"+mcsstable.modeldata.open_window_style+"')\" title="
				+ lang.open_title + ">" + temp+  "&nbsp;&nbsp;</span>";
			 
				h+=temp+"<br />";
				taskCount++;
			}
			else
			{
				h+="<span>更多...</span>";
				break;
			}

		}
	}


	return h;
}
function calendar_openRecord()
{
	s += "<span style='cursor:pointer;color:blue;' onClick=\"mcsstable_showpopup('"+params.homeUrl
			+ openurl
			+ "/"+mcsstable.keyfield+"/"
			+ v[mcsstable.keyfield]
			+ "','"
			+ lang.open
			+ "' ,'"+modeldata.open_window_style+"')\" title="
			+ lang.open_title + ">" + lang.open + "&nbsp;&nbsp;</span>";
	return s;		
}
//将xls文件导入到数据库
function mcsstable_importtosql(tableid){
	var mcsstable=mcsstable_getMCSSTable(tableid);
	//下面的escape方法在对应php中现在没有解码却正确，用了解码函数却不正确。因此没有用。对应的代码是$filterFromClient=($_REQUEST['filter']);
	try
	{
		//var url=mcsstable.params.homeUrl+g_systempath+"/xlsTosql";
		//如果mcsstable类接收了sql参数，则把该参数传递给后台，以代替模型的sql属性。这样便于程序员在前台灵活组件sql语句，但要注意sql的字段要与模型字段列表匹配
		//$.post(url,{modelid:mcsstable.modelid},function(data){
			//alert(data);return;
			//if(data==0)
				//alert('导入数据库成功!');
			//window.location.reload();
		//});
		if(mcsstable.params.impurl)
			setCookie("mcss_impurl",mcsstable.params.impurl);
		var url=mcsstable.params.homeUrl+"Mcss/ModelMaker/uploadexcel/modelid/"+mcsstable.modelid+"/tableid/"+mcsstable.tableid;
		ShowIframe(url,"700","400","导入Excel文件");
	}
	catch(ex)
	{
		alert(ex.message);
	}
}
//导出到xls文件
function mcsstable_exporttoexcel(tableid,type)
{
	if(confirm(lang.confirmtoexportexcel)){
		mcdom.alert(lang.isexporting,'','info');
		var mcsstable=mcsstable_getMCSSTable(tableid);
		var params=mcsstable.params;
		var fields=mcsstable.fields;
		var modeldata=mcsstable.modeldata;
		var first_td_func=mcsstable.first_td_func;
		var last_td_func=mcsstable.last_td_func;
		//下面的escape方法在对应php中现在没有解码却正确，用了解码函数却不正确。因此没有用。对应的代码是$filterFromClient=($_REQUEST['filter']);
		try
		{
			var filter1=mcsstable.filter;
			var sql="";
			var url=mcsstable.params.homeUrl+g_systempath+"/exporttoxls";
			if (params.sql) 
				sql=params.sql;
			if (mcsstable.exporturl)//跳到指定的Action中
				url = mcsstable.exporturl;
			if (mcsstable.selectedRowsID)
			{
				var srids=mcsstable.selectedRowsID;
				filter1=mcsstable.keyfield + " in ("+srids+")";
			}
			//如果传递了数据，则后来直接根据这个数据导出，不需要sql语句
			$.post(url,{modelid:mcsstable.modelid,sosoword:mcsstable.searchword,sql:sql,data:params.data,filter:filter1,filetype:type},function(data){
				mcdom.closePopup();
				var arr = data.split(';');
				if(arr[1]){
					var filepath =mcsstable.rooturl+"/Public/temp/"+arr[1].replace(/[ ]/g,"");
					alert(lang.exportdone);
					window.location=filepath;
				}
				else alert(lang.failtoexport);
			});
		}
		catch(ex)
		{
			alert(ex.message);
		}
	}
}

//打印，王灿开发
function mcsstable_print(tableid)
{
	var mctable=mcsstable_getMCSSTable(tableid);
	var title=$('#'+tableid+'_caption span').html();
	var page=$('#'+tableid+'_action_currentpage').val();
	var modelid=mctable.params.modelid;
	var url=g_homeurl+this.systemPath+'/printList/param:table/'+modelid+'/title/'+title+'/page/'+page+'/tableid/'+tableid;//document.location.href+"/action/print";
	window.open(url,"_blank");
}

//切换把表格中内容的全部显示在一行，或者折行显示
function mcsstable_autowidth(tableid)
{

	if ($("#"+tableid).attr('autowithstyle')==lang.nofoldlines)
	{        
		//当前样式:100%，不折行
        $("#"+tableid).css('width','100%');
//        $("table[id='"+tableid+"'] tr td").css('white-space','');
        $("table[id='"+tableid+"']").css({'white-space':'','word-wrap':'break-word','word-break':'break-all'});
        $("#"+tableid).attr('autowithstyle',lang.compact);
	}
	else
	{
		//当前样式:紧凑，可折行
        $("#"+tableid).css('width','');//内容紧凑允许折行，不需要设置宽度
     //   $("table[id='"+tableid+"'] tr td").css('white-space','nowrap');
        $("table[id='"+tableid+"']").css('white-space','nowrap');
        $("#"+tableid).attr('autowithstyle',lang.nofoldlines);
	}
}

function mcsstable_onsearchkeyup(event,tableid)
{
	var obj = event.srcElement ? event.srcElement : event.target;
	if (event.keyCode==13){
		mcsstable_search(tableid);
	}
	if ($("#"+tableid+"_searchnow").attr('checked')==true)
		mcsstable_search(tableid);
}

function mcsstable_onsearchchange(tableid)
{
if ($("#"+tableid+"_searchnow").attr('checked')==true)
	mcsstable_search(tableid);
}

function mcsstable_search(tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	var str=$("#"+tableid+"_action_searchtext").val();
	str = str.replace(/^(\s|\u00A0)+/,'');   
	for(var i=str.length-1; i>=0; i--){   
		if(/\S/.test(str.charAt(i))){   
			str = str.substring(0, i+1);   
			break;   
		}   
	}
	table.searchword=str;
	mcsstable_gotoFirstPage(tableid);
}

//权限检查
function checkaccess(data)
{
	if (data)
	{
		if (data.length>0 && data[0]['err'])
		{	
			var err=data[0]['err'];
			if (err.indexOf('unaccessible:')>-1)
			{
				return err;
			}
		}
	}
	return '';
}


function getJSONValue(jsonArray,key)
{
	for(var i=0;i<jsonArray.length;i++)
	{
		if (jsonArray[i][key])
			return jsonArray[i][key];
	}
	return null;
} 

MCSSTable.prototype.getSelectedRows=function(){
	var checkboxs=document.getElementsByName(this.tableid+"_checkbox");
	var ary=[];
    var selectedID=[];
	for(var i=0;i<checkboxs.length;i++)
	{
		if(checkboxs[i].checked==true)
		{
            selectedID.push(checkboxs[i].value);
		}
	}
	selectedID=selectedID.join(",")+",";
	var rows=this.data;
	var result=[];
	for(var i=0;i<rows.length;i++)
	{
		for(var j=0;j<selectedID.length;j++)
		{
			if (selectedID.indexOf(rows[i][this.keyfield]+",")>-1){
				result.push(rows[i]);
				break;
			}
		}
	}
	return result;
	 
}

function returnSelectedRows(tableid)
{
 	var mcsstable=mcsstable_getMCSSTable(tableid);
	var arr=mcsstable.getSelectedRows();

	var owner=this;
	if (mcsstable.params.popupFormID)
	{
		var formid=mcsstable.params.popupFormID;
		var form=owner.getAutoform(formid);
		var mcssmodel=new MCSSModel(form.modeldata);
		var fieldsetting=mcssmodel.getFieldInfo(mcsstable.params.popupFieldID,'data');
		if (fieldsetting.indexOf("(")>-1)
		{
			fieldsetting=fieldsetting.substr(fieldsetting.indexOf("(")+1);
			fieldsetting=fieldsetting.substr(0,fieldsetting.length-1);
			fieldsetting=fieldsetting.split(",");
			var setarr;
			for(var i=0;i<fieldsetting.length;i++)
			{
				setarr=fieldsetting[i].split(":")
				if (setarr.length==2)
				{
					var fieldobjid=form.getFieldID(setarr[1]);
					var f=owner.document.getElementById(fieldobjid);					
					if (arr.length>0){
						f.value=arr[0][setarr[0]];
						if (form.params.afterPopupSelectOne)
							form.params.afterPopupSelectOne(setarr[1],f.value);
					}	
				}
			}
		}
		else
		{
			var fieldobjid=form.getFieldID(mcsstable.params.popupFieldID);
			var f=owner.document.getElementById(fieldobjid);
			if (arr.length>0){
				f.value=arr[0][mcsstable.fields[0]];
			}
		}
		 
	}
	else
	{
		if (parent.mcss_SetSelected)
			parent.mcss_SetSelected(arr,mcsstable.params.popupFieldID,mcsstable.params.popupFormID);//调用父窗体的方法来处理选中的记录。父窗体的方法名必须是mcss_SetSelected
		else
			alert("父窗体没有对应的方法（parent.mcss_SetSelected），因此现在无法调用。");
		parent.ClosePop();
	}
	//parent.ClosePop();
	
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


function getGoodDate(date1)
{
	var r="";
	r=date1.getFullYear();
	var m=date1.getMonth()+1;
	var d=date1.getDate();
	if (m<10)r+="-0"+m;else r+="-"+m;
	if (d<10)r+="-0"+d;else r+="-"+d;
	return r;
}

function mcsstable_tableEdit(tableid,e)
{
	var mctable=mcsstable_getMCSSTable(tableid);

	if ($("#"+tableid).find('tr').size()>51)
	{
		alert("记录数多余50时，性能较差，暂不支持该操作。");
		return;
	}

	mctable.view='tableedit';
	//var hint="<span id='statushint'>正在进入编辑状态...</span>";
	//$("#"+tableid).before(hint);

	//auto_fill_fields:需要提供自动收集值选项的字段列表
	var editor=new TableEditor(mctable.tableid,mctable.mcssData.data,mctable.modeldata,{mcsstable:mctable,mcssData:mctable.mcssData,auto_fill_fields:mctable.params.auto_fill_fields});
	editor.run();
	mctable.cellEditor=editor;
	mctable.showAllActions(false);
	mctable.showAction("title");
	mctable.showAction("add");
	mctable.showAction("save");
	mctable.showAction("cancel");
	mctable.showAction("insert");
	mctable.showAction("export");
	//mctable.showAction("autowidth");
	mctable.showAction("view");
	$(".mcsstable_record_edit").remove();
	$(".mcsstable_record_open").hide();
	$("#"+mctable.params.tableid+ "_checkbox_selectall").hide();
	$("input[name='"+mctable.params.tableid+ "_checkbox']").hide();
	
	$("#"+tableid+"_pagebar").hide();
	if (mctable.params.afterRunTableEdit)
		mctable.params.afterRunTableEdit(mctable);
}

//进入单元格编辑状态
function mcsstable_cellEdit(tableid,e)
{
	var mctable=mcsstable_getMCSSTable(tableid);
	mctable.view='celledit';
	//auto_fill_fields:需要提供自动收集值选项的字段列表
	var editor=new TableEditor(mctable.tableid,mctable.mcssData.data,mctable.modeldata,{mcsstable:mctable,mcssData:mctable.mcssData,auto_fill_fields:mctable.params.auto_fill_fields});
	editor.run();
	mctable.cellEditor=editor;
}

function mcsstable_save(tableid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (mcsstable.params.beforeSave)
		mcsstable.params.beforeSave(mcsstable);
	mcsstable.cellEditor.params.mcssData.save(mcsstable_afterSave);
}

function mcsstable_afterSave(mcssData,mcsstable)
{
	$(mcsstable.table).find(".mcss_editedData").removeClass(mcsstable.cellEditor.editedDataClassName).css("background-color","").css("color","");
	//mcsstable_gotoViewState(mcsstable.id);//数据修改后按钮颜色会变，这里保存后要恢复
	mcsstable_setSaveBtn(mcsstable.tableid);	
	if (mcsstable.params.afterSave)
		mcsstable.params.afterSave(mcsstable);
}
function mcsstable_cancel(tableid,e)
{
	var mctable=mcsstable_getMCSSTable(tableid);
	if (mctable.cellEditor.params.mcssData.state=="CHANGED")
	{
		var h="<div id='"+tableid+"_addTask'>"+lang.notsavedtoask+"</div>"
		+"<div class='fuceng_buttom'>"
		+"<input type='button' id='"+tableid+"_save' class='btn btn-green' value='"+lang.save+"' />"
		+"<span id='"+tableid+"_cancel' class='btn'>"+lang.notsave+"</span>"
		+"</div>";
		
		mcpopup=mcdom.showPopup(e,h,null,null,null,320,360,"返回");	//直接调用mcdom.showPopup（定义在dom.js中）可以把html内容以浮层显示出来
		$("#"+tableid+"_save").focus();
		$("#"+tableid+"_save").click(function()
		{
			mcsstable_save(tableid);
			mcsstable_gotoViewState(tableid);
			$(mcpopup).remove();
		})
		$("#"+tableid+"_cancel").click(function()
		{
			mcsstable_gotoViewState(tableid);
			$(mcpopup).remove();			
		})		
	}else
	{
		mcsstable_gotoViewState(tableid);	
	}
	mcsstable_setSaveBtn(tableid);
	if (mctable.params.aterExecuteAction)
		mctable.params.aterExecuteAction(mctable,e);
}

//恢复保存按钮样式
function mcsstable_setSaveBtn(tableid)
{
	$("#"+tableid+"_action_save").css('border-color','');//数据修改后按钮颜色会变，这里保存后要恢复
}
function mcsstable_gotoViewState(tableid)
{
	var mctable=mcsstable_getMCSSTable(tableid);
	mctable.view="";
	mctable.cellEditor.params.mcssData.state="";
	mcsstable_loaddatarows(tableid);
	mctable.showAllActions();
	mctable.showAction("insert",false);
	mctable.showAction("cancel",false);
	mctable.showAction("save",false);
	$("#"+tableid+"_pagebar").show();
	
	$(".mcsstable_record_edit").show();
	$(".mcsstable_record_open").show();
	$("#"+mctable.params.tableid+ "_checkbox_selectall").show();
	$("input[name='"+mctable.params.tableid+ "_checkbox']").show();
	$("#addgroupbtn_atlast").remove();
	mcsstable_setSaveBtn(tableid);
	mcsstable_cellEdit(tableid);//默认回复后单元格编辑模式。这个可再议
	
}
//mctable的新增记录功能ssss
function mcsstable_addnew(e,tableid,url,lang_add,open_window_style,isinsert)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (mcsstable.params.beforeAddNew){
		if (mcsstable.params.beforeAddNew(mcsstable)==false)
			return;
	}
	if (mcsstable.view=='tableedit') //表格编辑
	{
		mcsstable_addNewEditRow(mcsstable,isinsert);
	}
	else
	{
		mcsstable_showMcssPopup(e,tableid,url,lang_add,open_window_style);
	}
}

//添加一条编辑状态的记录
//isinsert:true,after,false
function mcsstable_addNewEditRow(mcsstable,isinsert)
{
	if (mcsstable.view!='tableedit')
		return;
		
		var fields=mcsstable.fields;
		var tableid=mcsstable.tableid;
		var s=mcsstable_addonerow(null,tableid);
		var rowid=mcsstable.lastRowId;			
		if (!mcsstable.currentRow)
			mcsstable.currentRow=$(mcsstable.table).get(0).rows[1];
		var cur=mcsstable.currentRow;
		if(isinsert)
		{
			if(cur && cur.rowIndex>0)
			{
				if (cur.className=="rowgroup" || isinsert=='after')
					$(cur).after(s);
				else
					$(cur).before(s);
			}
			else
				$("#"+tableid).append(s);
		}else		
			$("#"+tableid).append(s);
		var row=document.getElementById(rowid);
		$(row).data("mcss_recordtype",'NEW');
		mcsstable.cellEditor.createRowEditor(row);
		$(row).find("td").css("padding","0px");
		$(row).find("input").eq(0).focus();

		mcsstable_createNewDataByRow(mcsstable,row);
		if (mcsstable.params.afterAppendRow)
		{
			mcsstable.params.afterAppendRow(mcsstable,row);
		}
		mcsstable.currentRow=row;
		return row;

}

//生成随机字符串
function randomString(l)
{
   var   x="0123456789qwertyuioplkjhgfdsazxcvbnm";
   var   tmp="";
   if (l==undefined)	l=8;
   for(var   i=0;i<l;i++)   {
   tmp   +=   x.charAt(Math.ceil(Math.random()*100000000)%x.length);
   }
   return   tmp;
}

function mcsstable_createNewDataByRow(mcsstable,row)
{
	//根据mcsstable的一条tr对象生成新的数据并更新到类的数据组中
	var mcssData=mcsstable.cellEditor.params.mcssData;
	var fields=mcssData.modeldata.fields;
	var recordid=$(row).attr("recordid");
	var newData={mcss_recordtype:"NEW",mcss_rowindex:row.rowIndex,recordid:recordid,fieldid:mcsstable.keyfield,value:recordid};
	mcssData.editNewData(newData);	
	for(var i=0;i<fields.length;i++)
	{
		if (fields[i].defaultdata)//每个字段的默认值，都要生成一条记录更新到数据组中
		{
			newData={mcss_recordtype:"NEW",mcss_rowindex:row.rowIndex,recordid:recordid};
			newData.fieldid=fields[i].id;
			newData.value=fields[i].defaultdata;
			mcssData.editNewData(newData);
		}
	}
}

//mctable的新增记录功能
function mcsstable_editRecord(e,tableid,url,lang_add,open_window_style,recordid)
{
	mcsstable_showMcssPopup(e,tableid,url,lang_add,open_window_style,recordid);
}


//打开新增或查看窗口
function mcsstable_showMcssPopup(e,tableid,url,title,open_window_style,recordid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (open_window_style=='samewindow')
	{
		document.location.href=url;
		return;
	}
	else if (open_window_style=='newwindow')
	{
		window.open(url,"_blank");
		return;
	}
	
	mcsstable.click_obj=e;//临时给mcsstable添加变量，目的是给关闭浮出层找到是哪个对象（如按钮）触发了浮出层
	if (!recordid 
	&& (!mcsstable.modeldata.addurl || (((mcsstable.modeldata.addurl).indexOf("/")<0) && mcsstable.modeldata.addurl!="default")) && title==lang.add
	||  ((!mcsstable.modeldata.editurl || (((mcsstable.modeldata.editurl).indexOf("/")<0) && mcsstable.modeldata.editurl!="default")) && recordid && title==lang.edit)
	|| ((!mcsstable.modeldata.openurl || (((mcsstable.modeldata.openurl).indexOf("/")<0) && mcsstable.modeldata.openurl!="default")) && recordid && title==lang.open)
	)
	{
		mcdom.closePopup(mcsstable.click_obj);//为避免已经创建的浮层未关闭，又创建了，就先移除，无论其是否存在
		if (recordid)
			mcsstable.goto_firstpage_when_reload=false;//临时给mcsstable添加变量，目的是给关闭浮出层保存后决定如何刷新。如果是新增，则刷新到第一页，否则当前页
		else
			mcsstable.goto_firstpage_when_reload=true;//临时给mcsstable添加变量，目的是给关闭浮出层保存后决定如何刷新。如果是新增，则刷新到第一页，否则当前页
		var addBtn="";	
		if (mcsstable.params.showSaveAndAddButton && !recordid)
			addBtn="<a id='"+tableid+"_saveandnew' class='savebutton btn' title='保存并新增'>保存并新增</a>";
		var s="<div id='"+tableid+"_autoform'></div>"
		+"<div class='fuceng_buttom'>";
		if(title!=lang.open)
			s+="<a id='"+tableid+"_save' href='javascript:void(0)' class='mcssingbutton btn btn-green'>"+lang.save+"</a>"+addBtn;
		s+="</div>";		
		var w=700;
		var h=300;
		if (open_window_style=='popup:middle' || open_window_style=='popup'){
			w=700;
			h=500;
		}
		else if (open_window_style=='popup:large'){
			w=900;
			h=700;
		}
 
		mcdom.showPopup(e,s,null,null,null,300,360,title);
		$("#"+tableid+"_autoform").parent().parent().css("width","auto");
		var editmodelid;
		if (recordid)
		{
			if(title==lang.edit && mcsstable.modeldata.editurl)
				editmodelid=mcsstable.modeldata.editurl;
			else if(mcsstable.modeldata.openurl && title==lang.open)				
				editmodelid=mcsstable.modeldata.openurl;
			else
				editmodelid=mcsstable.params.modelid
		}
		else
		{
			if(mcsstable.modeldata.addurl)
				editmodelid=mcsstable.modeldata.addurl;
			else
				editmodelid=mcsstable.params.modelid;
		}
		
		var obj = $("#"+tableid+"_save");
		var params={modelid:editmodelid,defaultValue:mcsstable.params.defaultValue,
		recordid:recordid,refObject:mcsstable,popupObj:e,saveButton:obj};
		if (mcsstable.params.afterGetModel)
			params.afterGetModel=mcsstable.params.afterGetModel;
		var Mcsstableform=new Autoform(tableid+"_autoform",params);   
		Mcsstableform.run(autowithpopup);
		if(title==lang.open)
			Mcsstableform.isshow=true;
		$("#"+tableid+"_save").click(function(){
			Mcsstableform.save(false,refresh_mctable,false);
		})
		//保存并新增
		$("#"+tableid+"_saveandnew").click(function(){
			Mcsstableform.save(false,null,false);			
			mcsstable_newAutoForm(Mcsstableform);
			mcsstable_loaddatarows(tableid);
		});
		return;
	}
	//个性化页面用弹出iframe	
	var u=url+"/tableid/"+tableid;
	if (mcsstable.params.defaultValue)
		u+="/defaultValue/"+mcsstable.params.defaultValue;
	mcsstable_showpopup(u,title,open_window_style);

}


//重新生成一个autoform
function mcsstable_newAutoForm(obj)
{
	obj.run();
}

//自动调整浮层的大小
function autowithpopup(form)
{
	if(form.isshow)
	{
		form.SetReadonly();
	}
	var mcsstable=form.params.refObject;
	if (mcsstable && mcsstable.params.afterOpenEditForm)
		mcsstable.params.afterOpenEditForm(form);

	//下面是原来的代码
	return;
	var w=parseInt($("#"+form.id).width()+50);
	var h=parseInt($("#"+form.id).height()+80);
	var popupid2=$("#"+form.id).get(0).parentNode.parentNode.parentNode.id;//parentNode.parentNode.parentNode.parentNode.parentNode.id;
	var fucengIdWidth=w+36;var fucengIdHeight=h+28;
	$("#"+popupid2).css("width",fucengIdWidth+"px").css("height",fucengIdHeight+"px");
	var popupid=$("#"+form.id).get(0).parentNode.parentNode.id;
	$("#"+popupid).css("width",w+"px");
	//$("#"+popupid).css("height",h+"px");

	return;
	
}

//保存新增表单后刷新mctable
function refresh_mctable(recordid,hint,mcform)
{
	var mcsstable=mcform.params.refObject;
	var tableid=mcsstable.params.tableid;
	if (mcsstable.goto_firstpage_when_reload)
		mcsstable.currentpage=1;
	mcdom.closePopup(mcform.params.popupObj);
	mcsstable_loaddatarows(tableid);
}

function mcsstable_row_onmouseover(e)
{
//	e=$(this);
//	alert($(e).get(0));
	$(e).addClass('mcsstable_tr_hover');
//	alert($(this).css());
}
function mcsstable_row_onmouseout(e)
{
	$(e).removeClass('mcsstable_tr_hover');
}
//得到选中的所有记录ID
function mcsstable_whencheck(e,tableid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	var ids=mcsstable.selectedRowsID;
	var id=$(e).val();
	var arr=new Array();
	arr=ids.split(',');
	var t=false;
	for(var i=0;i<arr.length;i++)
	{
		if(id==arr[i])
		{
			arr.splice(i,1);
			t=true;
		}
	}
	ids=arr.join();
	if(!t){
		if (ids)
			ids+=",";
		ids+=id;
	}
	mcsstable.selectedRowsID=ids;
}
//全选得到选中的所有记录ID
function mcsstable_whencheckall(e,tableid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	var ids=mcsstable.selectedRowsID;
	var id=$(e).val();
	var arr=new Array();
	arr=ids.split(',');
	for(var i=0;i<arr.length;i++)
	{
		if(id==arr[i])
		{
			arr.splice(i,1);
		}
	}
	ids=arr.join();
	if (ids)
		ids+=",";
	ids+=id;
	mcsstable.selectedRowsID=ids;
}
//选中已选择的记录
function mcsstable_setSelectedRow(mcsstable,fvalue)
{
	if (mcsstable.selectedRowsID)
	{
		var ids=mcsstable.selectedRowsID;
		var id=fvalue;
		var arr=new Array();
		arr=ids.split(',');
		for(var i=0;i<arr.length;i++)
		{
			if(id==arr[i])
			{
				return " checked='checked' ";
			}
		}
	}else{
		return '';
	}		
}
//根据文本框输入的数据跳到指定的页面
function mcsstable_gotoPagex(tableid,page,event)
{
	//alert(event.keyCode);
	if (!event) { //火狐
                   var event = window.event;
         }
	var e = event.keyCode;
	if (e==13||e==32) 
	{
		var reg = /^(?:0|[1-9][0-9]?|100)$/;
		var table=mcsstable_getMCSSTable(tableid);
		if(!reg.test(page)||page==0)
		{	
			alert("请输入指定范围内的整数！");
			$("#"+tableid+"_currentpage").attr("value",table.currentpage);
		}else{
			var maxpage = $("#"+tableid+"_action_totalpage").html();
			if(parseInt(page) > parseInt(maxpage)){
				alert("超出了最大页数!");
				$("#"+tableid+"_action_currentpage").attr("value",table.currentpage);
				return;
			}			
			table.currentpage=page;
			mcsstable_loaddatarows(tableid);
		}
	}
	
}

//根据模型获取数据，获取之后调用回调函数处理数据
function mcss_getDataByModel(callback,params)
{
	if (!params)
		params={};
	var url=g_homeurl+g_systempath+"/getData";
	$.getJSON(url, params,function(data) {
		callback(data,params.parentObject);
	});
}
				
function mcsstable_refresh(tableid)
{			
	mcsstable_gotoFirstPage(tableid);
	mcdom.alert("已刷新！",'','info','fadeout');
}

//获取td中的显示值,考虑了td中的输入组件的类别。干特图用到
function mcsstable_getCellDisplayText(td)
{
					var text=td.value;
					if (text==undefined)
					{
						var editor=$(td).children().get(0);
						if (editor)
						{
							var typename=editor.__proto__.constructor.name;
						 	if (typename=="HTMLSelectElement")
								text=getSelectedText($(td).children().get(0));
							else if (typename="HTMLCheckBox")
							{
								//还有其它各种情况未处理
							}
						}
						else
							text=td.innerHTML;
					}
					return text;
}
//获取下拉列表选中项的文本   
function getSelectedText(obj){ 
	for(i=0;i<obj.options.length;i++){   
	   if(obj.options[i].selected==true){   
		return obj.options[i].innerHTML; 
	   }   
	}   
}  

//点击放大图片
function viewImage(evt){
	var evt=getEvent();
	var element=evt.srcElement || evt.target;
	var rooturl=getrooturl();
   var imgTag=(window.event)?event.srcElement:evt.target;
   var imgPath=imgTag.src.replace("a.jpg",".jpg");//取得弹出的大图url
   var tagTop=Math.max(document.documentElement.scrollTop,document.body.scrollTop);
   var tag=document.createElement("div");
    tag.style.cssText="width:100%;height:"+Math.max(document.body.clientHeight,document.body.offsetHeight)+"px;position:absolute;background:white;top:0;filter: Alpha(Opacity=80);Opacity:0.8;";
    tag.ondblclick=function(){var clsOK=confirm("确定要取消图片显示吗?"); if(clsOK){closes();}};
   var tagImg=document.createElement("div");
    tagImg.style.cssText="overflow:auto;text-align:center;position:absolute;width:200px;border:5px solid #B4D14E;background:gray;color:white;left:"+(parseInt(document.body.offsetWidth)/2-100)+"px;top:"+(document.documentElement.clientHeight/3+tagTop)+"px;"
    tagImg.innerHTML="<div style='padding:10px;background:#cccccc;border:1px solid white'><img src='"+rooturl+"/Public/Images/loading.gif' /><br /><br /><b style='color:#999999;font-weight:normal'>图片加载中...</b><br /></div>";
   var closeTag=document.createElement("div");
    closeTag.style.cssText="display:block;position:absolute;right:0px;top:0px;cursor:pointer;";
    closeTag.innerHTML="<img src='"+rooturl+"/Public/Images/closebutton.png' title='点击关闭浏览'>";
    closeTag.onclick=closes;
   document.body.appendChild(tag);
   document.body.appendChild(tagImg);
   var img=new Image();
    img.src=imgPath;
	var wi=false;////图片可以存在的最大值
	if(img.height>(window.screen.height-200)||img.width>(window.screen.width-200)){
		wi=true
	}
    img.style.cssText="border:1px solid #cccccc;filter: Alpha(Opacity=0);Opacity:0;";
	tagImg.oncontextmenu=function(){var clsOK=confirm("确定要取消图片显示吗?"); if(clsOK){closes();};return false};
   var barShow,imgTime;
   img.complete?ImgOK():img.onload=ImgOK;
   function ImgOK(){
    var Stop1=false,Stop2=false,temp=0;
    var tx=tagImg.offsetWidth,ty=tagImg.offsetHeight;
    var ix=img.width,iy=img.height;
    var sx=document.documentElement.clientWidth,sy=Math.min(document.documentElement.clientHeight,document.body.clientHeight/*opera*/);
    var xx=ix>sx?sx-50:ix+4,yy=iy>sy?sy-50:iy+3;
    var maxTime=setInterval(function(){
     temp+=35;
     if((tx+temp)<xx){
      tagImg.style.width=(tx+temp)+"px";
      tagImg.style.left=(sx-(tx+temp))/2+"px";
     }else{
      Stop1=true;
      tagImg.style.width=xx+"px";
      tagImg.style.left=(sx-xx)/2+"px";
     }
     if((ty+temp)<yy){
      tagImg.style.height=(ty+temp)+"px";
      tagImg.style.top=(tagTop+((sy-(ty+temp))/2))+"px";
     }else{
      Stop2=true;
      tagImg.style.height=yy+"px";
      tagImg.style.top=(tagTop+((sy-yy)/2))+"px";
     }
     if(Stop1&&Stop2){
      clearInterval(maxTime);
      tagImg.appendChild(img);
	  tagImg.style.width=img.offsetWidth+"px"; ////
	  tagImg.style.left=((sx-img.offsetWidth)/2)+"px";
      temp=0;
      imgOPacity();
     }
    },1);
    function imgOPacity(){
     temp+=10;
     img.style.filter="alpha(opacity="+temp+")";
     img.style.opacity=temp/100;
     imgTime=setTimeout(imgOPacity,1)
     if(temp>100) clearTimeout(imgTime);
    }
    tagImg.innerHTML="";
    tagImg.appendChild(closeTag);
    if(ix>xx||iy>yy){
     img.alt="左键拖动,双击放大缩小";
	 img.title="左键拖动,双击放大缩小";
     img.ondblclick=function (){
      if(tagImg.offsetWidth<img.offsetWidth||tagImg.offsetHeight<img.offsetHeight){
       img.style.width="auto";
       img.style.height="100%";
       img.alt="双击可以放大";
       img.onmousedown=null;
       closeTag.style.top="10px";
       closeTag.style.left="10px";
       tagImg.style.overflow="visible";
       tagImg.style.width=img.offsetWidth+"px";
       tagImg.style.left=((sx-img.offsetWidth)/2)+"px";
      }else{
       img.style.width=ix+"px";
       img.style.height=iy+"px";
       img.alt="双击可以缩小";
       img.onmousedown=dragDown;
       tagImg.style.overflow="auto";
       tagImg.style.width=xx+"px";
       tagImg.style.left=((sx-xx)/2)+"px";
      }
     }
     img.onmousedown=dragDown;
     tagImg.onmousemove=barHidden;
     tagImg.onmouseout=moveStop;
     document.onmouseup=moveStop;
    }else{
     tagImg.style.overflow="visible";
     tagImg.onmousemove=barHidden;
    }
	////图片过大时
	   if(wi){
		   img.style.width="auto";
		   img.style.height="100%";
		   img.alt="双击可以放大";
		   img.onmousedown=null;
		   tagImg.style.overflow="visible";
		   tagImg.style.height=img.offsetHeight+"px";
		}
   }
   function dragDown(a){
    img.style.cursor="pointer";
    var evts=a||window.event;
    var onx=evts.clientX,ony=evts.clientY;
    var sox=tagImg.scrollLeft,soy=tagImg.scrollTop;
    var sow=img.width+2-tagImg.clientWidth,soh=img.height+2-tagImg.clientHeight;
    var xxleft,yytop;
    document.onmousemove=function(e){
     var evt=e||window.event;
     xxleft=sox-(evt.clientX-onx)<0?"0":sox-(evt.clientX-onx)>sow?sow:sox-(evt.clientX-onx);
     yytop=soy-(evt.clientY-ony)<0?"0":soy-(evt.clientY-ony)>soh?soh:soy-(evt.clientY-ony);
     tagImg.scrollTop=yytop;
     tagImg.scrollLeft=xxleft;
     closeTag.style.top=(yytop+10)+"px";
     closeTag.style.left=(xxleft+10)+"px";
     return false;
    }
    return false;
   }
   function barHidden(){
    clearTimeout(barShow);
    if(closeTag.style.display=="none")closeTag.style.display="block";
    barShow=setTimeout(function(){closeTag.style.display="none";},2000);
   }
   function closes(){
    document.body.removeChild(tag);
    document.body.removeChild(tagImg);
    clearTimeout(barShow);
    clearTimeout(imgTime);
    document.onmouseup=null;
    tag=img=tagImg=closeTag=null;
   }
   function moveStop(){
    document.onmousemove=null;
    tagImg.onmousemove=barHidden;
    img.style.cursor="default";
   }
   
  }
 