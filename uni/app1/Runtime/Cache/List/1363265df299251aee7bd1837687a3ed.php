<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title></title>
<link href='__PUBLIC__/themes/<?php echo ($mcss_theme); ?>/css/common.css' rel='stylesheet' type='text/css'>
<link href='__PUBLIC__/plugins/colResizable/css/main.css' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js" ></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/lang/<?php echo ($mcss_lang); ?>/language.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/MCSSTable.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/MCSSPicture.js"></script>
<script src="__PUBLIC__/projects/oa/js/pm.js" ></script>
<script src="__PUBLIC__/jusaas/js/MCDateTime.js" ></script>
<script type="text/javascript" src="__PUBLIC__/plugins/colResizable/js/colResizable-1.3.min.js"></script>
<script src="__PUBLIC__/jusaas/js/dom.js?dialog=succinct"></script>
</head>

<body class='list2Padding'>
<div id='mcss_pagebar' style="margin-bottom:5px;"></div>
<div id='main'>
<div id="mcss_table" style="width:100%;"></div>
<div id='background' class='background'></div>
</div>
</body>
</html>

<script type="text/javascript">



$(document).ready(function() {
	var lang='<?php echo ($mcss_lang); ?>';
	var modelid=geturlparam("table");
	var tableid=geturlparam("tableid");
	var page=getParamValue(location.href,'page');
	var tableid=getParamValue(location.href,'tableid');
	var mcsstable=window.opener.mcsstable_getMCSSTable(tableid);
	//得到上一个列表的所有的参数
	var params;
	if(tableid=="gantetu_gantetu")
		params=window.opener.parent.params;
	else
	    params=mcsstable.params;
	params.currentpage=page;
	params.afterLoadRows=myafterLoadRows;
	params.width='100%';
	params.pageposition="rightup";
	if(tableid=="gantetu_gantetu")
	{
		params=window.opener.parent.params;
		gantetu_work=new McssGantetu("mcss_table",params);
		gantetu_work.run();
	}else
	{
		var mcssTable=newMCSSTable("mcss_table",modelid,params);//params参数非必须
		mcssTable.run();
	}
});
function myafterLoadRows(mcsstable)
{
	hideFields(mcsstable);
	window.print();
}

function ifFieldExist(fieldsList,fieldText)
{
		for(var i = 0;i<fieldsList.length;i++){
			var name=$('#'+this.tableid).find('tr th:eq('+i+')').html();
			var temp=0;
			for(var j = 0;j<fieldsList.length;j++){
				var linename =fieldsList[j].name;
				if(fieldText==linename){
					return true;
				}
			}
		}
}

function hideFields(mcsstable){
	var title=decodeURI(getParamValue(location.href,'title'));
	var title1='<span>'+title+'</span>';
	$("#"+mcsstable.tableid+"_caption").html(title1);
	var fieldsList=mcsstable.modeldata["fields"];
	var firstName=$('#'+mcsstable.tableid).find('tr th:eq(0)').get(0).innerText;
	if (!ifFieldExist(fieldsList,firstName))
	{
		$('#'+mcsstable.tableid).find('tr th:eq(0)').hide();
		$('#'+mcsstable.tableid).find('tr').find('td:eq(0)').hide();	
	}
	var LastNameLength=$('#'+mcsstable.tableid).find('tr th').length-1;
	var LastName=$('#'+mcsstable.tableid).find('tr th').get(LastNameLength).innerText;
	if (!ifFieldExist(fieldsList,LastName))
	{
		$('#'+mcsstable.tableid).find('tr th:eq('+LastNameLength+')').hide();
		$('#'+mcsstable.tableid).find('tr').find('td:eq('+LastNameLength+')').hide();	
	}
		
}
</script>