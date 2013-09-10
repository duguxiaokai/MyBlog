<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel='stylesheet' type='text/css' href='__PUBLIC__/themes/<?php echo ($mcss_theme); ?>/css/common.css' />
	
	<script src="__PUBLIC__/js/jquery.js" ></script>
	<script src="__PUBLIC__/jusaas/mctree/MCTree.js"></script>
	<script src="__PUBLIC__/jusaas/js/common.js"></script>
	<script src="__PUBLIC__/jusaas/js/autoform.js"></script>	
	<script src="__PUBLIC__/jusaas/js/MCSSTable.js"></script>
	<script src="__PUBLIC__/jusaas/js/dom.js"></script>
	
<style>

</style>
<title>功能菜单管理</title>

</head>
<body>
<div id="mcss_pagebar"></div>

过滤:<select id='apps' onchange="filterApp(this)"></select>
<!--
<input type='button' value='展开' onclick="showAllNodes()">
<input type='button' value='折叠' onclick="hideAllNodes()">
-->
<input type='button' value='导出' onclick="exportFuncs(this)">
<input type='button' value='新增' onclick="addnew()">
<input type='text' id='searchtext' value='' onKeyup="gotoSearch(event);" onFocus="onFocusSearchtext()" />
<input type='button' value='搜索' id='searchtext' onclick="search()">
<input type='radio' name='mode' id='designmode' value='design' checked="true" onchange='changeMode(this);' /><label  for='designmode'>设计模式</label>
<input type='radio' name='mode' id='runningmode' value='running' onchange='changeMode(this);' /><label for='runningmode'>运行模式</label>


<table id="main_table"><tr>
<td style='vertical-align:top;'> 
<div id="mctree_div" class='ztree'></div> 
<div id="status"></div> 
</td>
<td style="vertical-align:top;">
<div id="mainform" style="display:none;">

	<div id="mcform"></div>

	<table id="mcss_table"  border='0' width="100%"></table>
	<div style='text-align:right'>
		<input type='button' value='创建同级菜单' onclick="addnew()">
		<input type='button' value='创建子菜单' onclick="createSubMenu()">
		<input type='button' value='保存' id='saveBtn' onclick="saveRecord()">
		<input type='button' value='上移' onclick="moveup()">
		<input type='button' value='下移' onclick="movedown()">
		<input type='button' value='复制' onclick="copy()">
		<input type='button' value='删除' onclick="deleteNode()">
		
	</div>
</div>
<iframe id='model_view' style="display:none;width:1200px;height:1000px;"></iframe>

</td></tr></table>
</body>
</html>
<script>
var form1;
var wl;
var mcssTable;
var currentNodeId;
var count=1;
var needReloadTree;
$(document).ready(function(){
	$.get("__APP__/Home/Index/getAllOrgs",{field:"code"},function(data){
	//alert(data);
		$("#apps").html( createSelect(","+data));
		var app=getCookie("mcss_orgid");
		$("#apps").val(app);
		var obj=$("#apps").get(0);
		filterApp(obj);
		
	})	
})

function initTree(filter,callback)
{
	$("#mctree_div").empty();
 	var url="__APP__/Mcss/Model/getTree";
	if (!filter)
		filter="";
	filter = filter.replace(/\'/g,"<yh>");
 	$.getJSON(url,{table:"sys_function",id:"no",pid:"groupno",name:"name",filter:filter,orderby:"sort"},function(data){
		//alert(data);return;
		if (!data || data.length==0)
		{
			$("#beginCreate").show();
		}
  		var orgdata=getTreeData(data,'no','groupno');
		wl=new MCTree("mctree_div",orgdata,{srcData:data,field_id:"no",field_pid:"groupno",click:clickNode,loadstyle:'loadallshowone',focusNode:currentNodeId,searchTextId:'searchtext'});
		wl.run(callback);
	})
}
 
 

//删除节点
function deleteNode(id)
{
	if(confirm('确定要删除此节点吗？')){
		
		form1.deleteRecord();
		form1.run();
		wl.deleteCurentNode();
	}

}
 
function clickNode(obj)
{
	var mode=mcdom.getRadioValue("mode");
	var objectid=$(obj.parentNode).attr('objectid');
	if (mode=="design")
	{
		$.post("__APP__/Mcss/Model/getFuncIdByCode",{code:objectid},function(id){
			$("#mainform").show();
			$("#mcss_table").hide();
			needReloadTree=false;
			if (form1)
			{
				form1.recordid=id;
				form1.fetchData(autoform_initData,form1);	
			}
			else
			{
				form1=new Autoform('mcform',{"modelid":"sys_function",'recordid':id,saveButton:$("#saveBtn").get(0),afterEditField:afterEditField});   
				form1.run(afterRun);
			}
		})
	}
	else if (mode=="running")
	{
		var func_code=objectid;
		openModel(func_code);
	}
}

//编辑字段后触发事件
function afterEditField(autoform,e)
{
	var f=$(e).attr('fieldid');
	if (f=='name' || f=='groupno')
	{
		needReloadTree=true;
	}
}

//创建模型表单后
function afterRun(mcform)
{
	setFieldVisible(mcform)	;
	$("#"+mcform.getFieldID("type")).change(function(){
		setFieldVisible(mcform)	
	});
	$("#"+mcform.getFieldID("modelid")).change(function(){
		var models=form1.getFieldValue("models");
		if (models.indexOf(this.value)==-1)
		{
			form1.setFieldValue("models",this.value);
		}
	});
	if ($("#select_parentmenu").size()==0)
	{
		var h="<span class='labellink' title='选择模型' id='select_model'  style='cursor:pointer;'>选择模型</span>";
		h+="-<span class='labellink' title='编辑模型' id='edit_model'  style='cursor:pointer;'>编辑模型</span>";
		mcform.addText("modelid",h);
		h="<span class='labellink' title='选择上级菜单' id='select_parentmenu' style='cursor:pointer;'>选择</span>";
		mcform.addText("groupno",h);
		$("#select_model").click(function(){
			ShowIframe("__APP__/Mcss/Model/modeltree",400,500,"选择模型",'1');
		})
		$("#select_parentmenu").click(function(){
			ShowIframe("__APP__/Mcss/Model/selectmenu",400,500,"选择模型",'1');
		})
		$("#edit_model").click(function(){
			ShowIframe("__APP__/Mcss/Model/modelmanager/modelid/"+mcform.getFieldValue("modelid"),800,600,"编辑模型");
		})
	}

	
	
}

function setFieldVisible(mcform)
{
		var type=mcform.getFieldValue("type");
		if (type=="form" || type=="table")
		{
			mcform.setFieldVisible("modelid",true);
			mcform.setFieldVisible("url",false);
			mcform.setFieldVisible("content",false);
		}
		else
		if (!type|| type=="url")
		{
			mcform.setFieldVisible("modelid",false);
			mcform.setFieldVisible("url",true);
			mcform.setFieldVisible("content",false);
		}		
		else
		if (type=="html")
		{
			mcform.setFieldVisible("modelid",false);
			mcform.setFieldVisible("url",false);
			mcform.setFieldVisible("content",true);
		}
}
function addnew()
{
	needReloadTree=true;
	$("#mainform").show();
	if (!form1)
	{
		form1=new Autoform('mcform',{"modelid":'sys_function',saveButton:$("#saveBtn").get(0)});   
		form1.run(setDefaultFuncCode);
	}
	else
	{
		var currentid=form1.getFieldValue("groupno");
		var status=form1.getFieldValue("status");
		var apps=form1.getFieldValue("apps");
		form1=new Autoform('mcform',{"modelid":"sys_function",'defaultValue':"groupno:"+currentid+",status:"+status+",apps:"+apps,saveButton:$("#saveBtn").get(0)});   
		form1.run(setDefaultFuncCode);

	}
}

//用时间创建惟一值
function getUniqueCode(prevchar)
{
	var d = new Date();
	var y = d.getFullYear();
	var m = d.getMonth() + 1;
	var day = d.getDate();
	var h=d.getHours();
	var mi=d.getMinutes();
	var s=d.getSeconds();
	var ms=d.getMilliseconds();
	return prevchar+y+m+day+mi+s+ms+getCookie("mcss_loginuser");
	
}

//给新建菜单设置默认值
function setDefaultFuncCode(form)
{
	newValue=getUniqueCode("func_");
	form.setFieldValue("no",newValue);
	form.setFieldValue("name","菜单");
	form.setFieldValue("apps",getCookie("mcss_app"));
	afterRun(form);	
	form.funcAfterRun=null;
}
function saveRecord()
{
	form1.save(false,afterSaveForm);
}

function afterSaveForm()
{
	if (needReloadTree)
	{
		reloadTree();
		needReloadTree=true;
	}
}

//复制节点
function copy(){
	if(form1.getFieldValue('no')){
		name = form1.getFieldValue('name');
		no = form1.getFieldValue('no');
		groupno = form1.getFieldValue('groupno');
		url = form1.getFieldValue('url');
		sort = form1.getFieldValue('sort');
		apps = form1.getFieldValue('apps');
		models = form1.getFieldValue('models');
		status = form1.getFieldValue('status');
		notes = form1.getFieldValue('notes');
		form2=new Autoform('mcform',{"modelid":'sys_function',saveButton:$("#saveBtn").get(0)});   
		form2.run(setValue);
	}else{
		alert('请选择一个要复制的节点!');
	}	
}
function setValue(){
		form2.setFieldValue('name',name+'_copy'+count);
		form2.setFieldValue('no',no+'_copy'+count);
		form2.setFieldValue('groupno',groupno);
		form2.setFieldValue('url',url);
		form2.setFieldValue('sort',sort);
		form2.setFieldValue('apps',apps);
		form2.setFieldValue('models',models);
		form2.setFieldValue('status',status);
		form2.setFieldValue('notes',notes);
		count++;
		form2.save(false,reloadTree2,true);
}
function reloadTree(id)
{
	
	currentNodeId=form1.getFieldValue("no");	
	var obj=$("#apps").get(0);
	filterApp(obj);	
}
function reloadTree2(id)
{
	alert('复制成功!');
	var filter="";
	if (app)
		filter="apps like '%"+app+"%'";
	currentNodeId=form1.getFieldValue("no");	
	initTree(filter);
}

//根据组织id刷新功能树
function refreshTree(orgid)
{
	$.get("__APP__/Sys/System/get1bysql",{sql:"select code from sys_org where id="+orgid},function(orgcode){
		filter="apps like '%"+orgcode+"%'";
		initTree(filter);
	})
} 

var app;
function filterApp(obj)
{
if (obj.value)
{
	refreshTree(obj.value);
}
else
	initTree("");

}

function search()
{
var s=$("#searchtext").val();
wl.searchNode(s);
}

function gotoSearch(event)
{	
	if (!event) {
	   var event = window.event;
	}
	if (event.keyCode==13){
		search();
	}
}
function onFocusSearchtext()
{
var s=$("#searchtext").val();
if (s=="输入搜索词")
	$("#searchtext").val("");

}

//上移节点
function moveup()
{
	var nodeid=$("li[objectid='"+wl.currentNodeId+"']").attr("id");
	var func2=$("#"+nodeid).prev().attr("objectid");
	$.post("__APP__/Mcss/Model/switch_menu_sort",{func_code1:wl.currentNodeId,func_code2:func2},function(resulr){
	})
	wl.moveup(wl.currentNodeId);
}
//下移节点
function movedown()
{
	var nodeid=$("li[objectid='"+wl.currentNodeId+"']").attr("id");
	var func2=$("#"+nodeid).next().attr("objectid");
	$.post("__APP__/Mcss/Model/switch_menu_sort",{func_code1:wl.currentNodeId,func_code2:func2},function(resulr){
	})
	wl.movedown(wl.currentNodeId);
	
}


function openModel(func_code)
{
	$.post("__APP__/Sys/System/getFuncUrl",{func_code:func_code},function(url){
		var url=getrooturl()+"/index.php/"+url;
		$("#model_view").attr("src",url);
	})
}

function setSelectedModel(node,nodetype)
{
	if (nodetype=="model")
	{
		var modelid=node.innerHTML;
		var m=modelid;
		var arr=modelid.split(".");
		if (arr.length==2)
			m=arr[0];
		form1.setFieldValue("modelid",m);
	} else
	if (nodetype=="menu")
	{
		form1.setFieldValue("groupno",$(node).attr('objectid'));
		needReloadTree=true;
	}
	popp.close();
}

//切换模式
function changeMode(e)
{
	if (e.value=="design")
	{
		$("#model_view").hide();
		$("#mainform").show();

	}
	else if (e.value=="running")
	{
		$("#mainform").hide();
		$("#model_view").show();
		//如何自动调整iframe的宽度？谁能搞定？
		//alert(document.body.clientWidth);
		//alert(parseInt($("#main_table").css("width")));
		var w=document.body.clientWidth-parseInt($("#mctree_div").css("width"))-100;
		$("#model_view").css("width",w+"px");	
	}
	var node=$("#mctree_div_"+wl.currentNodeId+"_name").get(0);
	clickNode(node);
}

//创建子菜单
function createSubMenu()
{
	var code=form1.getFieldValue("no");
	var status=form1.getFieldValue("status");
	var apps=form1.getFieldValue("apps");
	needReloadTree=true;
	form1=new Autoform('mcform',{"modelid":"sys_function",'defaultValue':"groupno:"+code+",status:"+status+",apps:"+apps,saveButton:$("#saveBtn").get(0)});   
	form1.run(setDefaultFuncCode);
}

function showAllNodes()
{
	$("ul").show();
}
function hideAllNodes()
{
	$("ul").hide();
}


function exportFuncs(e)
{
	if (!wl.params.showCheckbox)
	{
		wl.params.showCheckbox=true;
		wl.refresh();
		//alert("请勾选要导出的菜单，然后再单击［导出］!");
	}
	else
	{
	var nodeid,no,funcNos="",funcNos1="";
	$("input[type='checkbox']:checked").each(function(i,item){
			if (item.id.indexOf('mctree_div_checkbox_')==0)
			{
				nodeid=$(item).parent().attr('objectid');
				if (nodeid)
				{
					if (funcNos!="")
						funcNos+=",";
					funcNos+=nodeid;
					if (funcNos1!="")
						funcNos1+=",";
					funcNos1+="'"+nodeid+"'";
				}
			}
	})
	if (funcNos1.length>1000)
	{
		alert("您所选择的功能菜单太多了，请减少。");
		return;
	}
	
	$.getJSON("__APP__/Mcss/Model/getFunctionDataList",{funcCodes:funcNos},function(data){
		var s="";
		for(var i=0;i<data.length;i++)
		{
			s+="insert into sys_function(no,groupno,name,type,modelid,url,content,apps,models,status,notes,sort) values("
			+"'"+data[i].no+"','"+data[i].groupno+"','"+data[i].name+"','"+data[i].type+"','"+data[i].modelid+"','"+data[i].url+"','"+data[i].content+"','"+data[i].apps+"','"+data[i].models+"','"+data[i].status+"','"+data[i].notes+"','"+data[i].sort+"'"
			+");\n";

		}
		s="delete from sys_function where no in ("+funcNos1+");\n\n"+s;
		var html="<textarea rows='10' style='height:400px;width:440px;'>"+s+"</textarea>";
		mcdom.showPopup(e,html,null,null,null,500,500,'导出sql');	

	})
	}
}
</script>