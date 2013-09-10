<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>表单</title>
<link rel='stylesheet' type='text/css' href='__PUBLIC__/themes/<?php echo ($mcss_theme); ?>/css/common.css' />
<script src="__PUBLIC__/js/jquery.js" ></script>
<script src="__PUBLIC__/jusaas/js/common.js"></script>
<script src="__PUBLIC__/lang/<?php echo ($mcss_lang); ?>/language.js"></script>
<script src="__PUBLIC__/themes/<?php echo ($mcss_theme); ?>/js/theme.js"></script>
<script src="__PUBLIC__/jusaas/js/autoform.js"></script>

<!--script src="__PUBLIC__/js/DatePicker/WdatePicker.js"></script-->
<script >
setCookie("mcss_iframeurl",document.location.href);
var val;
var form1;
var defaultValue=common_getParam("defaultValue");
$(document).ready(function() {
	 init();
});

function init()
{

	$("#newwindow").html(lang.newwindow);
	$("#savebtn").val(lang.save);
	$("#resetbtn").val(lang.clear);
	s=location.href;
	var modelid=getParamValue(s,"model");//模型id
	if (!modelid)
		modelid=getParamValue(s,"modelid");
		
	var newwinow=getParamValue(s,"newwinow");//模型id
	val=getParamValue(s,"val");//模型id
	if (newwinow)
	{
		$("#mcss_pagebar").show();
	}
	var obj = $("#savebtn");
	form1=newMCSSForm('formdata',modelid,{recordid:'url:KEYFIELD',defaultValue:defaultValue,saveButton:obj});  
 	//form1=new Autoform('formdata',{recordid:'url:KEYFIELD',"modelid":modelid,defaultValue:defaultValue});   //KEYFIELD是虚拟的关键字段,实际运行会会解析成模型的keyfield
    form1.run(afterrun);
}
function afterrun()
{
	$("#savebtn").show();
	$("#resetbtn").show();

	if(val)
		$("#resetbtn").hide();
	else
		$("#resetbtn").show();
}

function saveRecord()
{
	saveThenNew=false;
 	form1.save(true,reload_parent_mctable,false,{beginTransaction:false,commitTransaction:false});//,
}
function reload_parent_mctable(id,hint,mcform)
{
	if (window.parent.mcss_callback_reload_mctable)
		parent.mcss_callback_reload_mctable();
	else
	{
		var tableid=getParamValue(location.href,"tableid");
		if (tableid)
			parent.mcsstable_loaddatarows(tableid);
	}		
	if (saveThenNew)
	{
		init();
	}
	window.parent.ClosePop();
}
var saveThenNew=false;
function saveAndNew()
{
	saveThenNew=true;
	form1.save(true,reload_parent_mctable);

}
</script>
</head>

<body class="body_form">
	<div id="mcss_pagebar"></div>
	<div style="margin:0 auto;">
<div class="formBox">
	<!--<h3><span>新增记录</span> <font color="#FF0000">*为必填项</font></h3>-->
	<div id="formdata"  class="formdataCon"></div>
</div>
<div class="Submitbutton" style="text-align:center">
	<a href="javascript:void(0)" onclick='saveRecord()' id="savebtn" title="保存" class='big_btn big_btn-green'>保存</a>
	<!--<a href="javascript:void(0)" onclick='window.history.go(-1);' id="goback" title="返回上一页" class='formbutton'>返回</a>-->
</div>
</div>
</body>
</html>