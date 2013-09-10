<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>项目中心</title>
<link href="__PUBLIC__/themes/default/css/common.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/themes/default/css/otherweb.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/themes/default/css/mcssTable.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="__PUBLIC__/jusaas/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/auoform.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js" ></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/MCSSTable.js"></script>
<script type="text/javascript" src='__PUBLIC__/js/popup.js'></script>
<script type="text/javascript" src="__PUBLIC__/Landz/js/scrolltopcontrol.js"></script>
  
<script type="text/javascript">
$(document).ready(function() {
	
	var field_dealing=[{'status':show_status}];
	var mcssTable=new MCSSTable({tableid:"mcss_table",modelid:"oa_task_unfinished",showfirst:true,showRecordActionAtLast:true});
	mcssTable.run();
	var mcssTable1=new MCSSTable({tableid:"mcss_table1",modelid:"oa_task_myall",showfirst:true,special_field_show:field_dealing,showRecordActionAtLast:true});
	mcssTable1.run();
	var mcssTable2=new MCSSTable({tableid:"mcss_table2",modelid:"oa_project_my",showfirst:true,special_field_show:field_dealing,showRecordActionAtLast:true});
	mcssTable2.run();
	
	$(".WorksMenu ul li").click(function(){
		$(this).addClass("nav").siblings().removeClass("nav");
		var index=$(".WorksMenu ul li").index($(this));
		$(".WorksContent > .xxinfo").eq(index).show().siblings().hide();
	});
});

function show_status(fieldvalue,record)
{

	if(record.finishper)
	{
		if(record.finishper == '0')
		{
			return "计划";
		}else if( 0.1 <= record.finishper && record.finishper<= 0.95)
		{
			return "执行";
		}else if(record.finishper == '1')
		{
			return "完成";
		}
	}

}


</script>
</head>

<body class="paddings">
<div class="McssingListTable">
	<div class="WorksMenu">
		<ul>
		<li class="nav">我负责的项目</li>
		<li>我未完成的工作</li>
		<li>我的所有工作</li>
		</ul>
	</div>
	<!--基本信息 start-->
	<div class="WorksContent">
		<div id="mcss0" class="xxinfo">
			<table id="mcss_table2" cellpadding="0" cellspacing="0"></table>
		</div>
		<div id="mcss1" class="xxinfo" style='display:none'>
			<table id="mcss_table" cellpadding="0" cellspacing="0"></table>
		</div>
		<div id="mcss2" class="xxinfo" style='display:none'>
			<table id="mcss_table1" cellpadding="0" cellspacing="0"></table>
		</div>
	</div>			
</div>
<div id='background' class='background'></div><div id='progressBar' class='progressBar'>数据加载中，请稍等...</div>
</body>
</html>