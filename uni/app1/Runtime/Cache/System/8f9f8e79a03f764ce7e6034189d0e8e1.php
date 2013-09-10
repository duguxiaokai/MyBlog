<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>表单</title>
<link rel='stylesheet' type='text/css' href='__PUBLIC__/themes/<?php echo ($mcss_theme); ?>/css/common.css' />

<script src="__PUBLIC__/js/jquery.js" ></script>
<script src="__PUBLIC__/js/popup.js" ></script>
<script src="__PUBLIC__/jusaas/js/common.js"></script>
<script src="__PUBLIC__/jusaas/js/autoform.js"></script>
<script src="__PUBLIC__/themes/<?php echo ($mcss_theme); ?>/js/theme.js"></script>
<script src="__PUBLIC__/jusaas/js/mcsstable.js"></script>
<script >
setCookie("mcss_iframeurl",document.location.href);
var form1;
var id="";
$(document).ready(function() {
	s=location.href;
	var modelid=getParamValue(s,"model");//模型id
	if (!modelid)
		modelid=getParamValue(s,"modelid");
	id=getParamValue(s,"id");
 	form1=new Autoform('formdata',{recordid:'url:KEYFIELD',"modelid":modelid});   //KEYFIELD是虚拟的关键字段,实际运行会会解析成模型的keyfield
    form1.run(dosome);
});

function dosome()
{
	form1.SetReadonly();
	if(form1.modeldata.children)
	{
		createtable();
	}
}
function createtable()
{	
	var children=form1.modeldata.children;
	var html="";
	var input="";
	for(var i=0;i<children.length;i++)
	{
		if(i==0)
		{
			input+="<input type='checkbox' id='checkbox"+i+"' onClick='showtable(this)' checked='true'  title='mcss_table"+i+"' />"+children[i].name;
			html+="<table id='mcss_table"+i+"' style=''></table><br />";
		}
		else
		{
			input+="<input type='checkbox' id='checkbox"+i+"' onClick='showtable(this)'  title='mcss_table"+i+"' />"+children[i].name;
			html+="<table id='mcss_table"+i+"' style='display:none'></table><br />";
		}
	}
	$("#checkboxlist").html(input);
	$("#children").html(html);
	createmcss();
}
function showtable(obj)
{
	if($("#"+obj.title).css("display")=="none")
		$("#"+obj.title).show();
	else
		$("#"+obj.title).hide();
}
function createmcss()
{
	var mcss=form1.modeldata.children;
	
	for(var i=0;i<mcss.length;i++)
	{
		var modelid=mcss[i].modelid;
		var filter=mcss[i].child_field+"='"+form1.getFieldValue(mcss[i].parent_field)+"'";
		var mcssTable="mcssTable"+i;
		var tableid="mcss_table"+i;
		var defaultValue=mcss[i].child_field+":"+form1.getFieldValue(mcss[i].parent_field);
		mcssTable=new MCSSTable({tableid:tableid,modelid:modelid,filter:filter,actions:"add,edit,search,page",homeUrl:"__APP__",showtitle:true,showfirst:true,first_td_name:"操作",afterLoadRows:null,defaultValue:defaultValue},null);
		mcssTable.run();
	}
}
</script>
<style type="text/css">
	.lefttitle{ width:100px; text-align:right;}
</style>
</head>

<body class='bodyfontsize'>
<div id='main'>
	<div id="mcss_pagebar"></div>
	<div id="formdata"></div>
	<div id="checkboxlist"></div>
	<div id="children"></div>
</div>	
</body>
</html>